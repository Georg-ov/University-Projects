// El paquete crypto agrupa las herramientas criptográficas del proyecto
package crypto

import (
	"crypto/aes"
	"crypto/cipher"
	"crypto/ed25519"
	"crypto/rand"
	"crypto/sha512"
	"crypto/subtle"
	"encoding/base64"
	"encoding/hex"
	"fmt"
	"strings"

	"golang.org/x/crypto/argon2"
	"golang.org/x/crypto/curve25519"
)

// Parámetros de Argon2id recomendados por la especificación OWASP para uso interactivo:
const (
	argonTime    uint32 = 1         // número de iteraciones sobre la memoria
	argonMemory  uint32 = 64 * 1024 // 64 MB de RAM usada
	argonThreads uint8  = 4         // hilos para concurrencia
	argonKeyLen  uint32 = 32        // longitud del hash resultante en bytes
	saltLen             = 16        // 128 bits de sal aleatoria
)

// HashPassword genera un hash Argon2id de la contraseña con sal aleatoria.
// Devuelve el hash en formato: "argon2id$<sal_base64>$<hash_base64>"
// Este formato portable permite verificar el hash sin almacenar la sal por separado.
func HashPassword(password string) (string, error) {
	// La sal garantiza que dos usuarios con la misma contraseña tengan hashes distintos,
	salt := make([]byte, saltLen)
	if _, err := rand.Read(salt); err != nil {
		return "", fmt.Errorf("no se pudo generar la sal: %w", err)
	}

	hash := argon2.IDKey([]byte(password), salt, argonTime, argonMemory, argonThreads, argonKeyLen)

	return fmt.Sprintf("argon2id$%s$%s",
		base64.RawStdEncoding.EncodeToString(salt),
		base64.RawStdEncoding.EncodeToString(hash),
	), nil
}

// VerifyPassword comprueba si 'password' corresponde al hash almacenado.
func VerifyPassword(password, encoded string) bool {
	parts := strings.Split(encoded, "$")
	if len(parts) != 3 || parts[0] != "argon2id" {
		return false
	}

	salt, err := base64.RawStdEncoding.DecodeString(parts[1])
	if err != nil {
		return false
	}

	expected, err := base64.RawStdEncoding.DecodeString(parts[2])
	if err != nil {
		return false
	}

	// Recalculamos el hash con la misma sal y los mismos parámetros.
	hash := argon2.IDKey([]byte(password), salt, argonTime, argonMemory, argonThreads, uint32(len(expected)))

	// Usa subtle.ConstantTimeCompare para evitar ataques de timing-oracle
	return subtle.ConstantTimeCompare(hash, expected) == 1
}

// NewToken genera un token seguro de 'size' bytes representado en hexadecimal.
// Reemplaza el generador inseguro "token_N" de Sprout original.
func NewToken(size int) (string, error) {
	buf := make([]byte, size)
	if _, err := rand.Read(buf); err != nil {
		return "", fmt.Errorf("no se pudo generar el token: %w", err)
	}
	// hex.EncodeToString produce una cadena de 2*size caracteres hexadecimales.
	return hex.EncodeToString(buf), nil
}

// Encrypt cifra 'data' con AES-256-CTR usando la clave 'key' (32 bytes).
// El IV de 16 bytes se genera aleatoriamente y se pega al texto cifrado: [IV (16 bytes)] + [ciphertext].
// Al ser AES-CTR, no se necesita padding: funciona con datos de cualquier tamaño.
func Encrypt(data, key []byte) ([]byte, error) {
	// IV aleatorio: garantiza que el mismo plaintext produzca ciphertexts distintos.
	out := make([]byte, 16+len(data))
	if _, err := rand.Read(out[:16]); err != nil {
		return nil, fmt.Errorf("no se pudo generar el IV: %w", err)
	}

	blk, err := aes.NewCipher(key)
	if err != nil {
		return nil, fmt.Errorf("error creando cifrado AES: %w", err)
	}

	// NewCTR convierte el cifrado por bloques AES en un cifrado de flujo
	// XORKeyStream cifra 'data' y lo almacena en out[16:].
	cipher.NewCTR(blk, out[:16]).XORKeyStream(out[16:], data)
	return out, nil
}

// Decrypt descifra datos cifrados con Encrypt (formato: [IV (16B)] + [ciphertext]).
// AES-CTR es simétrico: la misma operación XOR sirve para cifrar y descifrar.
func Decrypt(data, key []byte) ([]byte, error) {
	if len(data) < 16 {
		return nil, fmt.Errorf("datos demasiado cortos: necesita al menos 16 bytes de IV")
	}

	blk, err := aes.NewCipher(key)
	if err != nil {
		return nil, fmt.Errorf("error creando cifrado AES: %w", err)
	}

	out := make([]byte, len(data)-16)
	cipher.NewCTR(blk, data[:16]).XORKeyStream(out, data[16:])
	return out, nil
}

// DeriveKey genera una clave AES-256 determinista a partir de la contraseña y el nombre de usuario. Usado en E2E.
func DeriveKey(password, username string) []byte {
	h := sha512.New()
	h.Write([]byte("sds-e2e-v1:"))
	h.Write([]byte(username))
	h.Write([]byte(":"))
	h.Write([]byte(password))
	sum := h.Sum(nil) // 64 bytes SHA-512
	return sum[:32]   // primeros 32 bytes -> clave AES-256
}

// =============================================================================
// Firma Digital (Ed25519)
// =============================================================================

// GenerateEd25519KeyPair genera un par de claves para firma digital.
func GenerateEd25519KeyPair() (ed25519.PublicKey, ed25519.PrivateKey, error) {
	return ed25519.GenerateKey(nil)
}

// Sign firma un mensaje usando una clave privada Ed25519.
func Sign(privateKey ed25519.PrivateKey, message []byte) []byte {
	return ed25519.Sign(privateKey, message)
}

// VerifySignature verifica la firma de un mensaje usando la clave pública Ed25519.
func VerifySignature(publicKey ed25519.PublicKey, message, signature []byte) bool {
	if len(publicKey) != ed25519.PublicKeySize {
		return false
	}
	return ed25519.Verify(publicKey, message, signature)
}

// =============================================================================
// Intercambio de Claves y Cifrado E2E Asíncrono (X25519)
// =============================================================================

// GenerateX25519KeyPair genera un par de claves para el intercambio X25519.
func GenerateX25519KeyPair() (publicKey, privateKey []byte, err error) {
	privateKey = make([]byte, curve25519.ScalarSize)
	if _, err := rand.Read(privateKey); err != nil {
		return nil, nil, fmt.Errorf("error generando clave privada X25519: %w", err)
	}
	publicKey, err = curve25519.X25519(privateKey, curve25519.Basepoint)
	if err != nil {
		return nil, nil, fmt.Errorf("error generando clave pública X25519: %w", err)
	}
	return publicKey, privateKey, nil
}

// DeriveSharedSecret deriva un secreto compartido usando la clave privada local y la clave pública remota.
func DeriveSharedSecret(privateKey, peerPublicKey []byte) ([]byte, error) {
	return curve25519.X25519(privateKey, peerPublicKey)
}

// DeriveSessionKey deriva una clave AES-256 a partir de un secreto compartido X25519.
func DeriveSessionKey(sharedSecret []byte) []byte {
	h := sha512.New()
	h.Write([]byte("sds-x25519-session:"))
	h.Write(sharedSecret)
	sum := h.Sum(nil)
	return sum[:32] // primeros 32 bytes → clave AES-256
}
