// El paquete client contiene la lógica de interacción con el usuario y de comunicación con el servidor mediante HTTPS.
package client

import (
	"bytes"
	"crypto/ed25519"
	"crypto/rand"
	"crypto/tls"
	"encoding/base64"
	"encoding/json"
	"fmt"
	"io"
	"log"
	"net/http"
	"os"
	"runtime"
	"sprout/pkg/api"
	"sprout/pkg/crypto"
	"sprout/pkg/ui"
	"strings"
	"time"
)

// serverURL apunta al servidor HTTPS local.
const serverURL = "https://localhost:8443/api"

// Estructura interna que controla el estado de la sesión
type client struct {
	log         *log.Logger
	currentUser string
	authToken   string
	role        string // admin o user
	dataKey     []byte // clave E2E derivada de la contraseña del usuario, solo en memoria
	edPrivKey   ed25519.PrivateKey
	x25PrivKey  []byte
	httpClient  *http.Client
}

// Run es la función exportada que inicia el bucle del cliente.
func Run() {
	c := &client{
		log: log.New(os.Stdout, "[cli] ", log.LstdFlags),
		httpClient: &http.Client{
			Timeout: 30 * time.Second,
			Transport: &http.Transport{
				TLSClientConfig: &tls.Config{
					InsecureSkipVerify: true, // certificado autofirmado del profesor
				},
			},
		},
	}
	c.runLoop()
}

// runLoop gestiona el menú principal.
func (c *client) runLoop() {
	for {
		ui.ClearScreen()

		var title string
		if c.currentUser == "" {
			title = "=== Disco Duro Virtual Seguro ==="
		} else if c.role == "admin" {
			title = fmt.Sprintf("=== Disco Duro Virtual Seguro [%s] [ADMIN] ===", c.currentUser)
		} else {
			title = fmt.Sprintf("=== Disco Duro Virtual Seguro [%s] ===", c.currentUser)
		}

		var options []string
		if c.currentUser == "" {
			options = []string{
				"Registrar usuario",
				"Iniciar sesión",
				"Salir",
			}
		} else {
			options = []string{
				"Subir fichero al disco virtual  (cifrado E2E)",
				"Descargar fichero del disco virtual",
				"Crear carpeta",
				"Listar directorio",
				"Buscar ficheros por tag",
				"Borrar fichero o carpeta",
				"Enviar mensaje privado",
				"Leer mensajes recibidos",
			}
			if c.role == "admin" {
				options = append(options, "Realizar copia de seguridad del servidor")
			}
			options = append(options, "Cerrar sesión", "Salir")
		}

		choice := ui.PrintMenu(title, options)

		if c.currentUser == "" {
			switch choice {
			case 1:
				c.registerUser()
			case 2:
				c.loginUser()
			case 3:
				c.log.Println("Saliendo del cliente...")
				return
			}
		} else {
			switch choice {
			case 1:
				c.uploadFile()
			case 2:
				c.downloadFile()
			case 3:
				c.makedir()
			case 4:
				c.listDir()
			case 5:
				c.listByTag()
			case 6:
				c.deleteEntry()
			case 7:
				c.sendMessage()
			case 8:
				c.fetchMessages()
			case 9:
				if c.role == "admin" {
					c.backupServer()
				} else {
					// opción 9 es Cerrar sesión para usuarios normales
					c.logoutUser()
				}
			case 10:
				if c.role == "admin" {
					c.logoutUser()
				} else {
					c.log.Println("Saliendo del cliente...")
					return
				}
			case 11:
				if c.role == "admin" {
					c.log.Println("Saliendo del cliente...")
					return
				}
			}
		}

		ui.Pause("Pulsa [Enter] para continuar...")
	}
}

// =============================================================================
// Autenticación
// =============================================================================

// registerUser registra un usuario y hace login automático.
// Al hacer login automático, también deriva la clave E2E.
func (c *client) registerUser() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Registro de usuario **" + ui.ColorReset)

	username := ui.ReadInput("Nombre de usuario")
	password, err := ui.ReadPassword("Contraseña")
	if err != nil {
		c.log.Println("No se ha podido obtener la contraseña:", err)
		return
	}

	edPub, edPriv, _ := crypto.GenerateEd25519KeyPair()
	x25Pub, x25Priv, _ := crypto.GenerateX25519KeyPair()

	res := c.sendRequest(api.Request{
		Action:        api.ActionRegister,
		Username:      username,
		Password:      password,
		Ed25519PubKey: edPub,
		X25519PubKey:  x25Pub,
	})

	if res.Success {
		fmt.Println(ui.ColorGreen + "Éxito: " + res.Message + ui.ColorReset)
	} else {
		fmt.Println(ui.ColorRed + "Error: " + res.Message + ui.ColorReset)
	}

	if res.Success {
		loginRes := c.sendRequest(api.Request{
			Action:   api.ActionLogin,
			Username: username,
			Password: password,
		})
		if loginRes.Success {
			c.currentUser = username
			c.authToken = loginRes.Token
			c.role = loginRes.Role
			// Derivar la clave E2E una sola vez al iniciar sesión.
			c.dataKey = crypto.DeriveKey(password, username)
			c.edPrivKey = edPriv
			c.x25PrivKey = x25Priv

			// Guardar las claves privadas localmente de forma cifrada en una Cloud Wallet local
			walletData := map[string][]byte{"ed": edPriv, "x25": x25Priv}
			rawWallet, _ := json.Marshal(walletData)
			encWallet, _ := crypto.Encrypt(rawWallet, c.dataKey)
			os.MkdirAll("data", 0755)
			os.WriteFile(fmt.Sprintf("data/wallet_%s.dat", username), encWallet, 0600)

			fmt.Println("Login automático exitoso. Claves generadas y guardadas.")
			c.remoteLog("INFO", "Usuario registrado e inició sesión automáticamente")
		}
	}
}

// loginUser autentica al usuario y deriva la clave E2E local que nunca se transmite al servidor
func (c *client) loginUser() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Inicio de sesión **" + ui.ColorReset)

	username := ui.ReadInput("Nombre de usuario")
	password, err := ui.ReadPassword("Contraseña")
	if err != nil {
		c.log.Println("No se ha podido obtener la contraseña:", err)
		return
	}

	res := c.sendRequest(api.Request{
		Action:   api.ActionLogin,
		Username: username,
		Password: password,
	})

	if res.Success {
		fmt.Println(ui.ColorGreen + "Éxito: " + res.Message + ui.ColorReset)
	} else {
		fmt.Println(ui.ColorRed + "Error: " + res.Message + ui.ColorReset)
	}

	if res.Success {
		c.currentUser = username
		c.authToken = res.Token
		c.role = res.Role
		// Derivamos la clave E2E de forma determinista, la misma contraseña produce siempre la misma clave.
		c.dataKey = crypto.DeriveKey(password, username)

		// Cargar la wallet local
		encWallet, err := os.ReadFile(fmt.Sprintf("data/wallet_%s.dat", username))
		if err == nil {
			rawWallet, err := crypto.Decrypt(encWallet, c.dataKey)
			if err == nil {
				var walletData map[string][]byte
				json.Unmarshal(rawWallet, &walletData)
				c.edPrivKey = walletData["ed"]
				c.x25PrivKey = walletData["x25"]
			}
		}

		fmt.Println("Sesión iniciada. Claves cargadas.")
		c.remoteLog("INFO", "Usuario inició sesión")
	}
}

// logoutUser cierra la sesión y borra la clave E2E de memoria.
func (c *client) logoutUser() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Cerrar sesión **" + ui.ColorReset)

	res := c.sendRequest(api.Request{
		Action:   api.ActionLogout,
		Username: c.currentUser,
		Token:    c.authToken,
	})

	if res.Success {
		fmt.Println(ui.ColorGreen + "Éxito: " + res.Message + ui.ColorReset)
	} else {
		fmt.Println(ui.ColorRed + "Error: " + res.Message + ui.ColorReset)
	}

	if res.Success {
		c.remoteLog("INFO", "Usuario cerró sesión")
		c.currentUser = ""
		c.authToken = ""
		for i := range c.dataKey {
			c.dataKey[i] = 0
		}
		c.dataKey = nil
	}
}

// =============================================================================
// Sistema de ficheros virtual
// =============================================================================

// uploadFile sube un fichero con cifrado E2E, metadatos y tags.
func (c *client) uploadFile() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Subir fichero al disco virtual (cifrado E2E) **" + ui.ColorReset)

	localPath := ui.ReadInput("Ruta del fichero local")
	remotePath := ui.ReadInput("Ruta virtual destino  (ej: /docs/informe.pdf)")

	// Recoger tags (opcional).
	fmt.Println("Tags (separados por coma, o deja vacío):")
	tagStr := ui.ReadInput("Tags")
	var tags []string
	for _, t := range strings.Split(tagStr, ",") {
		t = strings.TrimSpace(t)
		if t != "" {
			tags = append(tags, t)
		}
	}

	// Leer fichero local.
	data, err := os.ReadFile(localPath)
	if err != nil {
		fmt.Println("Error al leer el fichero:", err)
		return
	}

	// Capturar metadatos del fichero local.
	info, err := os.Stat(localPath)
	if err != nil {
		fmt.Println("Error al obtener metadatos:", err)
		return
	}

	fmt.Printf("Fichero: %s  |  Tamaño: %d bytes  |  Plataforma: %s\n",
		info.Name(), info.Size(), runtime.GOOS)

	// Ciframos con la clave del cliente antes de enviar.
	encrypted, err := crypto.Encrypt(data, c.dataKey)
	if err != nil {
		fmt.Println("Error al cifrar localmente:", err)
		return
	}

	meta := &api.FileEntry{
		Name:        info.Name(),
		Size:        info.Size(),
		Permissions: info.Mode().String(),
		ModTime:     info.ModTime().UTC().Format(time.RFC3339),
		Platform:    runtime.GOOS,
		Tags:        tags,
		E2E:         true, // flag: el cliente cifró el contenido
	}

	// Generar Nonce y Timestamp para prevenir Replay Attacks
	nonceBytes := make([]byte, 16)
	rand.Read(nonceBytes)
	nonce := base64.StdEncoding.EncodeToString(nonceBytes)
	timestamp := time.Now().UTC().Format(time.RFC3339)

	// Firmar el contenido cifrado + Nonce + Timestamp para garantizar el origen y prevenir reenvíos.
	payloadToSign := append([]byte(nil), encrypted...)
	payloadToSign = append(payloadToSign, []byte(nonce)...)
	payloadToSign = append(payloadToSign, []byte(timestamp)...)
	signature := crypto.Sign(c.edPrivKey, payloadToSign)

	res := c.sendRequest(api.Request{
		Action:    api.ActionUpload,
		Username:  c.currentUser,
		Token:     c.authToken,
		Path:      remotePath,
		Data:      base64.StdEncoding.EncodeToString(encrypted),
		Meta:      meta,
		Signature: signature,
		Nonce:     nonce,
		Timestamp: timestamp,
	})

	if res.Success {
		fmt.Println(ui.ColorGreen + "Éxito: " + res.Message + ui.ColorReset)
	} else {
		fmt.Println(ui.ColorRed + "Error: " + res.Message + ui.ColorReset)
	}
	if res.Success && len(tags) > 0 {
		fmt.Println("Tags guardados:", strings.Join(tags, ", "))
	}
}

// downloadFile descarga un fichero y lo descifra localmente si tiene E2E flag.
// Además muestra los metadatos del fichero original.
func (c *client) downloadFile() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Descargar fichero del disco virtual **" + ui.ColorReset)

	remotePath := ui.ReadInput("Ruta virtual origen")
	localPath := ui.ReadInput("Ruta local donde guardar")

	res := c.sendRequest(api.Request{
		Action:   api.ActionDownload,
		Username: c.currentUser,
		Token:    c.authToken,
		Path:     remotePath,
	})

	if !res.Success {
		fmt.Println(ui.ColorRed + "Error: " + res.Message + ui.ColorReset)
		return
	}

	// Decodificar base64.
	data, err := base64.StdEncoding.DecodeString(res.Data)
	if err != nil {
		fmt.Println("Error al decodificar los datos:", err)
		return
	}

	// Desciframos con la clave que solo existe en memoria del cliente.
	if res.Meta != nil && res.Meta.E2E {
		data, err = crypto.Decrypt(data, c.dataKey)
		if err != nil {
			fmt.Println("Error al descifrar el fichero (¿contraseña incorrecta?):", err)
			return
		}
		fmt.Println("[OK] Fichero descifrado localmente (E2E - el servidor nunca vio el contenido).")
	}

	if err := os.WriteFile(localPath, data, 0600); err != nil {
		fmt.Println("Error al guardar el fichero:", err)
		return
	}

	fmt.Printf("Fichero guardado: %s (%d bytes)\n", localPath, len(data))

	// Mostrar metadatos del fichero original.
	if res.Meta != nil && (res.Meta.Size > 0 || res.Meta.Platform != "") {
		fmt.Println("\n── Metadatos del fichero ─────────────────────")
		if res.Meta.Size > 0 {
			fmt.Printf("  Tamaño original : %d bytes\n", res.Meta.Size)
		}
		if res.Meta.Permissions != "" {
			fmt.Printf("  Permisos        : %s\n", res.Meta.Permissions)
		}
		if res.Meta.ModTime != "" {
			fmt.Printf("  Modificado      : %s\n", res.Meta.ModTime)
		}
		if res.Meta.Platform != "" {
			fmt.Printf("  Plataforma      : %s\n", res.Meta.Platform)
		}
		if res.Meta.UploadedAt != "" {
			fmt.Printf("  Subido el       : %s\n", res.Meta.UploadedAt)
		}
		if len(res.Meta.Tags) > 0 {
			fmt.Printf("  Tags            : %s\n", strings.Join(res.Meta.Tags, ", "))
		}
		e2eStr := "No"
		if res.Meta.E2E {
			e2eStr = "Sí (descifrado localmente)"
		}
		fmt.Printf("  Cifrado E2E     : %s\n", e2eStr)
		fmt.Println("──────────────────────────────────────────────")
	}
}

// makedir crea una carpeta en el disco virtual.
func (c *client) makedir() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Crear carpeta **" + ui.ColorReset)
	path := ui.ReadInput("Ruta de la carpeta (ej: /documentos/trabajo)")
	res := c.sendRequest(api.Request{
		Action:   api.ActionMkdir,
		Username: c.currentUser,
		Token:    c.authToken,
		Path:     path,
	})
	if res.Success {
		fmt.Println(ui.ColorGreen + "Éxito: " + res.Message + ui.ColorReset)
	} else {
		fmt.Println(ui.ColorRed + "Error: " + res.Message + ui.ColorReset)
	}
}

// listDir lista el contenido de un directorio incluyendo metadatos
func (c *client) listDir() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Listar directorio **" + ui.ColorReset)
	path := ui.ReadInput("Ruta a listar (/ para la raíz)")

	res := c.sendRequest(api.Request{
		Action:   api.ActionList,
		Username: c.currentUser,
		Token:    c.authToken,
		Path:     path,
	})

	if !res.Success {
		fmt.Println(ui.ColorRed + "Error: " + res.Message + ui.ColorReset)
		return
	}

	fmt.Printf("\nContenido de %s - %s\n", path, res.Message)
	fmt.Println("──────────────────────────────────────────────────────────────")

	if len(res.FileEntries) > 0 {
		// Listado enriquecido con metadatos.
		for _, entry := range res.FileEntries {
			if entry.IsDir {
				fmt.Printf("  [DIR]  %s\n", entry.Name)
			} else {
				tags := ""
				if len(entry.Tags) > 0 {
					tags = " [" + strings.Join(entry.Tags, ",") + "]"
				}
				e2e := ""
				if entry.E2E {
					e2e = " [E2E]"
				}
				dateStr := ""
				if len(entry.UploadedAt) >= 10 {
					dateStr = entry.UploadedAt[:10] // solo la fecha YYYY-MM-DD
				}
				fmt.Printf("  [FILE] %-30s %8d B  %s%s%s\n",
					entry.Name, entry.Size, dateStr, tags, e2e)
			}
		}
	} else if len(res.Entries) > 0 {
		// Listado en texto plano.
		for _, entry := range res.Entries {
			fmt.Println(" ", entry)
		}
	} else {
		fmt.Println("  (directorio vacío)")
	}
	fmt.Println("──────────────────────────────────────────────────────────────")
}

// listByTag filtra y muestra todos los ficheros del usuario con un tag específico.
func (c *client) listByTag() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Buscar ficheros por tag **" + ui.ColorReset)
	tag := ui.ReadInput("Tag a buscar (ej: trabajo)")
	if tag == "" {
		fmt.Println("El tag no puede estar vacío.")
		return
	}

	res := c.sendRequest(api.Request{
		Action:   api.ActionListByTag,
		Username: c.currentUser,
		Token:    c.authToken,
		Tag:      tag,
	})

	if !res.Success {
		fmt.Println(ui.ColorRed + "Error: " + res.Message + ui.ColorReset)
		return
	}

	fmt.Printf("\nResultados para tag '%s' — %s\n", tag, res.Message)
	fmt.Println("──────────────────────────────────────────────────────────────")

	if len(res.FileEntries) == 0 {
		fmt.Println("  No se encontraron ficheros con ese tag.")
	} else {
		for _, entry := range res.FileEntries {
			e2e := ""
			if entry.E2E {
				e2e = " [E2E]"
			}
			plat := ""
			if entry.Platform != "" {
				plat = " [" + entry.Platform + "]"
			}
			fmt.Printf("  [FILE] %-30s %8d B%s%s\n",
				entry.Name, entry.Size, plat, e2e)
		}
	}
	fmt.Println("──────────────────────────────────────────────────────────────")
}

// deleteEntry borra un fichero o carpeta y sus metadatos/tags asociados.
func (c *client) deleteEntry() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Borrar fichero o carpeta **" + ui.ColorReset)
	path := ui.ReadInput("Ruta a borrar")
	if !ui.Confirm(fmt.Sprintf("¿Confirmas el borrado de '%s'?", path)) {
		fmt.Println("Cancelado.")
		return
	}
	res := c.sendRequest(api.Request{
		Action:   api.ActionDelete,
		Username: c.currentUser,
		Token:    c.authToken,
		Path:     path,
	})
	if res.Success {
		fmt.Println(ui.ColorGreen + "Éxito: " + res.Message + ui.ColorReset)
	} else {
		fmt.Println(ui.ColorRed + "Error: " + res.Message + ui.ColorReset)
	}
}

// =============================================================================
// Comunicación HTTP
// =============================================================================

// sendRequest envía un POST JSON al servidor HTTPS y devuelve la respuesta.
func (c *client) sendRequest(req api.Request) api.Response {
	jsonData, err := json.Marshal(req)
	if err != nil {
		c.log.Println("No se ha podido serializar la petición JSON:", err)
		return api.Response{Success: false, Message: "Error interno del cliente"}
	}

	httpReq, err := http.NewRequest(http.MethodPost, serverURL, bytes.NewBuffer(jsonData))
	if err != nil {
		c.log.Println("No se ha podido construir la petición HTTP:", err)
		return api.Response{Success: false, Message: "Error interno del cliente"}
	}
	httpReq.Header.Set("Content-Type", "application/json")

	resp, err := c.httpClient.Do(httpReq)
	if err != nil {
		fmt.Println("Error al contactar con el servidor:", err)
		return api.Response{Success: false, Message: "Error de conexión"}
	}
	defer resp.Body.Close()

	body, err := io.ReadAll(resp.Body)
	if err != nil {
		c.log.Println("No se ha podido leer la respuesta:", err)
		return api.Response{Success: false, Message: "Respuesta inválida del servidor"}
	}

	var res api.Response
	if err := json.Unmarshal(body, &res); err != nil {
		c.log.Println("No se ha podido descodificar la respuesta JSON:", err)
		return api.Response{Success: false, Message: "Respuesta inválida del servidor"}
	}

	return res
}

// =============================================================================
// Mensajería, Backups y Logs
// =============================================================================

func (c *client) sendMessage() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Enviar mensaje privado (E2E) **" + ui.ColorReset)

	// Obtener y mostrar la lista de usuarios registrados
	usersRes := c.sendRequest(api.Request{
		Action:   api.ActionListUsers,
		Username: c.currentUser,
		Token:    c.authToken,
	})
	if usersRes.Success && len(usersRes.Entries) > 0 {
		fmt.Println(ui.ColorYellow + "Usuarios disponibles:" + ui.ColorReset)
		for i, u := range usersRes.Entries {
			fmt.Printf("  %d. %s\n", i+1, u)
		}
		fmt.Println()
	} else {
		fmt.Println(ui.ColorYellow + "(No hay otros usuarios registrados todavía)" + ui.ColorReset)
	}

	target := ui.ReadInput("Destinatario")
	msg := ui.ReadInput("Mensaje")

	// Obtener clave pública del destinatario
	res := c.sendRequest(api.Request{
		Action:     api.ActionGetPublicKey,
		Username:   c.currentUser,
		Token:      c.authToken,
		TargetUser: target,
	})
	if !res.Success {
		fmt.Println("Error obteniendo clave pública:", res.Message)
		return
	}

	// Derivar clave compartida con X25519
	sharedSecret, err := crypto.DeriveSharedSecret(c.x25PrivKey, res.X25519PubKey)
	if err != nil {
		fmt.Println("Error derivando clave compartida:", err)
		return
	}
	sessionKey := crypto.DeriveSessionKey(sharedSecret)

	// Cifrar el mensaje con la sesión (AES-256)
	encryptedMsg, err := crypto.Encrypt([]byte(msg), sessionKey)
	if err != nil {
		fmt.Println("Error cifrando mensaje:", err)
		return
	}

	// Generar Nonce y Timestamp para prevenir Replay Attacks
	nonceBytes := make([]byte, 16)
	rand.Read(nonceBytes)
	nonce := base64.StdEncoding.EncodeToString(nonceBytes)
	timestamp := time.Now().UTC().Format(time.RFC3339)

	// Firmar el mensaje cifrado + Nonce + Timestamp con Ed25519
	payloadToSign := append([]byte(nil), encryptedMsg...)
	payloadToSign = append(payloadToSign, []byte(nonce)...)
	payloadToSign = append(payloadToSign, []byte(timestamp)...)
	signature := crypto.Sign(c.edPrivKey, payloadToSign)

	resMsg := c.sendRequest(api.Request{
		Action:     api.ActionSendMessage,
		Username:   c.currentUser,
		Token:      c.authToken,
		TargetUser: target,
		Data:       base64.StdEncoding.EncodeToString(encryptedMsg),
		Signature:  signature,
		Nonce:      nonce,
		Timestamp:  timestamp,
	})
	if resMsg.Success {
		fmt.Println(ui.ColorGreen + "Mensaje enviado a " + target + ui.ColorReset)
		c.remoteLog("INFO", "Mensaje privado enviado a "+target)
	} else {
		fmt.Println(ui.ColorRed + "Error: " + resMsg.Message + ui.ColorReset)
	}
}

func (c *client) fetchMessages() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Bandeja de entrada **" + ui.ColorReset)

	// Obtiene la lista de conversaciones con conteo de no leídos
	convRes := c.sendRequest(api.Request{
		Action:   api.ActionListConversations,
		Username: c.currentUser,
		Token:    c.authToken,
	})
	if !convRes.Success {
		fmt.Println("Error:", convRes.Message)
		return
	}
	if len(convRes.Conversations) == 0 {
		fmt.Println("No tienes mensajes.")
		return
	}

	// Mostrar lista de conversaciones
	fmt.Println()
	for i, conv := range convRes.Conversations {
		if conv.UnreadCount > 0 {
			fmt.Printf(ui.ColorYellow+"  %d. %s - %d mensaje(s) sin leer (total: %d)"+ui.ColorReset+"\n",
				i+1, conv.Sender, conv.UnreadCount, conv.TotalCount)
		} else {
			fmt.Printf("  %d. %s - %d mensaje(s)\n", i+1, conv.Sender, conv.TotalCount)
		}
	}
	fmt.Println()

	// Seleccionar usuario del que leer
	sender := ui.ReadInput("Selecciona un usuario (escribe su nombre)")
	if sender == "" {
		return
	}

	// Obtener mensajes de ese usuario
	ui.ClearScreen()
	fmt.Printf(ui.ColorCyan+"** Conversación con %s **"+ui.ColorReset+"\n\n", sender)

	res := c.sendRequest(api.Request{
		Action:     api.ActionFetchMessagesFrom,
		Username:   c.currentUser,
		Token:      c.authToken,
		TargetUser: sender,
	})
	if !res.Success {
		fmt.Println("Error:", res.Message)
		return
	}
	if len(res.Messages) == 0 {
		fmt.Println("No hay mensajes de este usuario.")
		return
	}

	// Obtener clave pública del emisor una sola vez
	pubRes := c.sendRequest(api.Request{
		Action:     api.ActionGetPublicKey,
		Username:   c.currentUser,
		Token:      c.authToken,
		TargetUser: sender,
	})
	if !pubRes.Success {
		fmt.Println("[Error: no se pudo obtener la clave pública del emisor]")
		return
	}
	sharedSecret, _ := crypto.DeriveSharedSecret(c.x25PrivKey, pubRes.X25519PubKey)
	sessionKey := crypto.DeriveSessionKey(sharedSecret)

	// Mapa de nonces vistos en esta sesión de lectura para detectar Replay Attacks
	seenNonces := make(map[string]bool)

	for _, msg := range res.Messages {
		fmt.Printf("--- [%s] ---\n", msg.Time)

		encData, _ := base64.StdEncoding.DecodeString(msg.Data)

		// Verificar firma (Nonce + Timestamp)
		payloadToVerify := append([]byte(nil), encData...)
		payloadToVerify = append(payloadToVerify, []byte(msg.Nonce)...)
		payloadToVerify = append(payloadToVerify, []byte(msg.Timestamp)...)

		if !crypto.VerifySignature(pubRes.Ed25519PubKey, payloadToVerify, msg.Signature) {
			fmt.Println(ui.ColorRed + "[ATENCIÓN: Firma inválida. El mensaje ha sido alterado.]" + ui.ColorReset)
			fmt.Println()
			continue
		}

		// Anti-Replay: comprobar que el Nonce no ha sido visto antes en esta sesión.
		// Si el atacante duplica el mensaje en la base de datos, tendrá el mismo Nonce.
		if msg.Nonce != "" && seenNonces[msg.Nonce] {
			fmt.Println(ui.ColorRed + "[ATENCIÓN: ATAQUE DE REPLAY DETECTADO]" + ui.ColorReset)
			fmt.Println(ui.ColorRed + "Este mensaje es una cópia duplicada (Nonce ya visto)." + ui.ColorReset)
			fmt.Println(ui.ColorRed + "Ha sido bloqueado por el sistema Anti-Replay." + ui.ColorReset)
			fmt.Println()
			continue
		}
		seenNonces[msg.Nonce] = true

		decData, err := crypto.Decrypt(encData, sessionKey)
		if err != nil {
			fmt.Println("[Error al descifrar el mensaje]")
			continue
		}
		fmt.Println(ui.ColorGreen + string(decData) + ui.ColorReset)
		fmt.Println()
	}
	fmt.Println("--------------------------------")
}

func (c *client) backupServer() {
	ui.ClearScreen()
	fmt.Println(ui.ColorCyan + "** Realizar copia de seguridad del servidor **" + ui.ColorReset)

	res := c.sendRequest(api.Request{
		Action:   api.ActionBackup,
		Username: c.currentUser,
		Token:    c.authToken,
	})
	if !res.Success {
		fmt.Println(ui.ColorRed + "Error: " + res.Message + ui.ColorReset)
		return
	}

	data, _ := base64.StdEncoding.DecodeString(res.Data)
	fileName := fmt.Sprintf("backup_%s.db", time.Now().Format("20060102_150405"))
	if err := os.WriteFile(fileName, data, 0600); err != nil {
		fmt.Println("Error guardando el backup localmente:", err)
		return
	}
	fmt.Printf(ui.ColorGreen+"Copia de seguridad guardada con éxito (%d bytes) en %s\n"+ui.ColorReset, len(data), fileName)
	c.remoteLog("INFO", "Copia de seguridad realizada")
}

func (c *client) remoteLog(level, msg string) {
	if c.currentUser == "" {
		return
	}
	c.sendRequest(api.Request{
		Action:   api.ActionRemoteLog,
		Username: c.currentUser,
		Token:    c.authToken,
		LogEntry: &api.LogEntry{
			Level:   level,
			Message: msg,
			Time:    time.Now().UTC().Format(time.RFC3339),
		},
	})
}
