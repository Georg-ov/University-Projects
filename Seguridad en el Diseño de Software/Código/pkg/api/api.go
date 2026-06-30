// El paquete api define el contrato de comunicación JSON entre cliente y servidor.
// Toda la información viaja en estructuras Request y Response serializadas como JSON.
package api

// El cliente rellena req.Action y el servidor decide qué handler ejecutar.
const (
	ActionRegister   = "register"
	ActionLogin      = "login"
	ActionFetchData  = "fetchData"
	ActionUpdateData = "updateData"
	ActionLogout     = "logout"
	// Sistema de ficheros virtual
	ActionUpload   = "upload"
	ActionDownload = "download"
	ActionMkdir    = "mkdir"
	ActionList     = "list"
	ActionDelete   = "delete"
	// Desafíos extra
	ActionListByTag         = "listByTag"
	ActionGetPublicKey      = "getPublicKey"
	ActionSendMessage       = "sendMessage"
	ActionFetchMessages     = "fetchMessages"
	ActionRemoteLog         = "remoteLog"
	ActionBackup            = "backup"
	ActionListUsers         = "listUsers"
	ActionListConversations = "listConversations"
	ActionFetchMessagesFrom = "fetchMessagesFrom"
)

// Estructura para la conversación con otro usuario
type ConversationEntry struct {
	Sender      string `json:"sender"`
	TotalCount  int    `json:"total_count"`
	UnreadCount int    `json:"unread_count"`
}

// Estructura para los logs
type LogEntry struct {
	Level   string `json:"level"`
	Message string `json:"message"`
	Time    string `json:"time"`
}

// Estructura para los mensajes enviados por un usuario a otro
type MessageEntry struct {
	From      string `json:"from"`
	Data      string `json:"data"`                // base64 del contenido cifrado con X25519+AES
	Signature []byte `json:"signature,omitempty"` // firma digital del emisor (Ed25519)

	Nonce     string `json:"nonce,omitempty"`     // Nonce para evitar replay attacks
	Timestamp string `json:"timestamp,omitempty"` // Timestamp para evitar replay attacks
	Time      string `json:"time"`
}

// Estructura para los ficheros o carpetas del disco virtual
// Se usa tanto en listados como al devolver metadatos de una descarga individual
type FileEntry struct {
	Name        string   `json:"name"`
	IsDir       bool     `json:"is_dir"`
	Size        int64    `json:"size,omitempty"`        // bytes del fichero original
	Permissions string   `json:"permissions,omitempty"` // permisos del fichero
	ModTime     string   `json:"mod_time,omitempty"`    // RFC3339 del fichero local
	Platform    string   `json:"platform,omitempty"`    // SO origen: linux, windows
	UploadedAt  string   `json:"uploaded_at,omitempty"` // RFC3339 del momento de subida
	Tags        []string `json:"tags,omitempty"`        // etiquetas del fichero
	E2E         bool     `json:"e2e,omitempty"`         // indica si el contenido fue cifrado por el cliente
}

// Estructura de datos que el cliente envía al servidor
type Request struct {
	Action   string     `json:"action"`
	Username string     `json:"username"`
	Password string     `json:"password,omitempty"`
	Token    string     `json:"token,omitempty"`
	Data     string     `json:"data,omitempty"` // contenido base64 del fichero
	Path     string     `json:"path,omitempty"` // ruta virtual destino u origen
	Tag      string     `json:"tag,omitempty"`  // para listByTag
	Meta     *FileEntry `json:"meta,omitempty"` // metadatos del fichero en upload

	// Nuevos campos para cifrado y mensajería
	Ed25519PubKey []byte    `json:"ed25519_pub,omitempty"`
	X25519PubKey  []byte    `json:"x25519_pub,omitempty"`
	TargetUser    string    `json:"target_user,omitempty"`
	LogEntry      *LogEntry `json:"log_entry,omitempty"`
	Signature     []byte    `json:"signature,omitempty"`

	Nonce     string `json:"nonce,omitempty"`
	Timestamp string `json:"timestamp,omitempty"`
}

// Estructura de datos que el servidor devuelve al cliente
type Response struct {
	Success     bool        `json:"success"`
	Message     string      `json:"message"`
	Token       string      `json:"token,omitempty"`
	Role        string      `json:"role,omitempty"`
	Data        string      `json:"data,omitempty"`         // contenido base64 del fichero
	Entries     []string    `json:"entries,omitempty"`      // listado legible en texto
	FileEntries []FileEntry `json:"file_entries,omitempty"` // lista de entradas de un directorio
	Meta        *FileEntry  `json:"meta,omitempty"`         // metadatos de un fichero individual

	// Nuevos campos para cifrado y mensajería
	Ed25519PubKey []byte              `json:"ed25519_pub,omitempty"`
	X25519PubKey  []byte              `json:"x25519_pub,omitempty"`
	Messages      []MessageEntry      `json:"messages,omitempty"`
	Conversations []ConversationEntry `json:"conversations,omitempty"`
}
