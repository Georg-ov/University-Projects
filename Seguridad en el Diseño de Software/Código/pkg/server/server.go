// El paquete server contiene el código del servidor.
// Interactúa con el cliente mediante una API JSON/HTTPS.
package server

import (
	"bytes"
	"crypto/subtle"
	"encoding/base64"
	"encoding/json"
	"errors"
	"fmt"
	"io"
	"log"
	"net/http"
	"os"
	"sprout/pkg/api"
	"sprout/pkg/crypto"
	"sprout/pkg/store"
	"strings"
	"time"
)

// Estructura que encapsula el estado de nuestro servidor.
type server struct {
	db        store.Store
	log       *log.Logger
	masterKey []byte // 32 bytes (AES-256)
}

// Run inicia la base de datos y arranca el servidor HTTPS.
func Run(masterPassword string) error {
	if err := os.MkdirAll("data", 0755); err != nil {
		return fmt.Errorf("error creando la carpeta 'data': %w", err)
	}

	db, err := store.NewStore("bbolt", "data/server.db")
	if err != nil {
		return fmt.Errorf("error abriendo base de datos: %v", err)
	}

	if masterPassword == "" {
		return fmt.Errorf("la master key no puede estar vacía")
	}

	// Derivamos una clave AES-256 (32 bytes) a partir de la contraseña escrita
	masterKey := crypto.DeriveKey(masterPassword, "server-admin-salt")
	fmt.Println("[OK] Master Key derivada y cargada en memoria RAM.")

	srv := &server{
		db:        db,
		log:       log.New(os.Stdout, "[srv] ", log.LstdFlags),
		masterKey: masterKey,
	}
	defer srv.db.Close()

	mux := http.NewServeMux()
	mux.Handle("/api", http.HandlerFunc(srv.apiHandler))

	httpSrv := &http.Server{
		Addr:              ":8443",
		Handler:           mux,
		ReadHeaderTimeout: 5 * time.Second,
	}

	srv.log.Println("Servidor HTTPS iniciado en https://localhost:8443")
	return httpSrv.ListenAndServeTLS("localhost.crt", "localhost.key")
}

// apiHandler decodifica la solicitud JSON y la despacha al handler correspondiente.
func (s *server) apiHandler(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		http.Error(w, "Método no permitido", http.StatusMethodNotAllowed)
		return
	}

	r.Body = http.MaxBytesReader(w, r.Body, 10<<20) // 10 MiB

	var req api.Request
	dec := json.NewDecoder(r.Body)
	dec.DisallowUnknownFields()
	if err := dec.Decode(&req); err != nil {
		http.Error(w, "Error en el formato JSON", http.StatusBadRequest)
		return
	}
	if err := dec.Decode(&struct{}{}); err != io.EOF {
		http.Error(w, "Error en el formato JSON", http.StatusBadRequest)
		return
	}

	var res api.Response
	switch req.Action {
	case api.ActionRegister:
		res = s.registerUser(req)
	case api.ActionLogin:
		res = s.loginUser(req)
	case api.ActionFetchData:
		res = s.fetchData(req)
	case api.ActionUpdateData:
		res = s.updateData(req)
	case api.ActionLogout:
		res = s.logoutUser(req)
	case api.ActionUpload:
		res = s.uploadFile(req)
	case api.ActionDownload:
		res = s.downloadFile(req)
	case api.ActionMkdir:
		res = s.makedir(req)
	case api.ActionList:
		res = s.listDir(req)
	case api.ActionDelete:
		res = s.deleteEntry(req)
	case api.ActionListByTag:
		res = s.listByTag(req)
	case api.ActionGetPublicKey:
		res = s.getPublicKey(req)
	case api.ActionSendMessage:
		res = s.sendMessage(req)
	case api.ActionFetchMessages:
		res = s.fetchMessages(req)
	case api.ActionRemoteLog:
		res = s.remoteLog(req)
	case api.ActionBackup:
		res = s.backup(req)
	case api.ActionListUsers:
		res = s.listUsers(req)
	case api.ActionListConversations:
		res = s.listConversations(req)
	case api.ActionFetchMessagesFrom:
		res = s.fetchMessagesFrom(req)
	default:
		res = api.Response{Success: false, Message: "Acción desconocida"}
	}

	w.Header().Set("Content-Type", "application/json")
	_ = json.NewEncoder(w).Encode(res)
}

// registerUser registra un nuevo usuario con Argon2id + AES en descanso.
func (s *server) registerUser(req api.Request) api.Response {
	if req.Username == "" || req.Password == "" {
		return api.Response{Success: false, Message: "Faltan credenciales"}
	}

	exists, err := s.userExists(req.Username)
	if err != nil {
		return api.Response{Success: false, Message: "Error al verificar usuario"}
	}
	if exists {
		return api.Response{Success: false, Message: "El usuario ya existe"}
	}

	hash, err := crypto.HashPassword(req.Password)
	if err != nil {
		return api.Response{Success: false, Message: "Error al procesar la contraseña"}
	}

	encHash, err := crypto.Encrypt([]byte(hash), s.masterKey)
	if err != nil {
		return api.Response{Success: false, Message: "Error de cifrado"}
	}

	if err := s.db.Put("auth", []byte(req.Username), encHash); err != nil {
		return api.Response{Success: false, Message: "Error al guardar credenciales"}
	}

	if err := s.db.Put("userdata", []byte(req.Username), []byte("")); err != nil {
		return api.Response{Success: false, Message: "Error al inicializar datos de usuario"}
	}

	// Guardar claves públicas si se proporcionan
	if len(req.Ed25519PubKey) > 0 && len(req.X25519PubKey) > 0 {
		pubKeys := map[string][]byte{
			"ed25519": req.Ed25519PubKey,
			"x25519":  req.X25519PubKey,
		}
		data, _ := json.Marshal(pubKeys)
		_ = s.db.Put("publickeys", []byte(req.Username), data)
	}

	// El primer usuario registrado recibe el rol admin; el resto user.
	role := "user"
	existingUsers, _ := s.db.ListKeys("auth")
	if len(existingUsers) == 1 {
		role = "admin"
	}
	_ = s.db.Put("roles", []byte(req.Username), []byte(role))

	return api.Response{Success: true, Message: "Usuario registrado"}
}

// loginUser valida credenciales y emite un token seguro de 256 bits.
func (s *server) loginUser(req api.Request) api.Response {
	if req.Username == "" || req.Password == "" {
		return api.Response{Success: false, Message: "Faltan credenciales"}
	}

	encHash, err := s.db.Get("auth", []byte(req.Username))
	if err != nil {
		// Anti timing-oracle: si el usuario no existe tardamos lo mismo.
		crypto.VerifyPassword(req.Password, "argon2id$AAAAAAAAAAAAAAAAAAAAAA$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA")
		return api.Response{Success: false, Message: "Credenciales inválidas"}
	}

	hashBytes, err := crypto.Decrypt(encHash, s.masterKey)
	if err != nil {
		return api.Response{Success: false, Message: "Error interno del servidor"}
	}

	if !crypto.VerifyPassword(req.Password, string(hashBytes)) {
		return api.Response{Success: false, Message: "Credenciales inválidas"}
	}

	token, err := crypto.NewToken(32)
	if err != nil {
		return api.Response{Success: false, Message: "Error al crear sesión"}
	}

	if err := s.db.Put("sessions", []byte(req.Username), []byte(token)); err != nil {
		return api.Response{Success: false, Message: "Error al guardar sesión"}
	}

	// Devolver el rol del usuario para que el cliente adapte el menú
	role := "user"
	if roleBytes, err := s.db.Get("roles", []byte(req.Username)); err == nil {
		role = string(roleBytes)
	}

	return api.Response{Success: true, Message: "Login exitoso", Token: token, Role: role}
}

func (s *server) fetchData(req api.Request) api.Response {
	if req.Username == "" || req.Token == "" {
		return api.Response{Success: false, Message: "Faltan credenciales"}
	}
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "Token inválido o sesión expirada"}
	}
	rawData, err := s.db.Get("userdata", []byte(req.Username))
	if err != nil {
		return api.Response{Success: false, Message: "Error al obtener datos del usuario"}
	}
	return api.Response{Success: true, Message: "Datos privados de " + req.Username, Data: string(rawData)}
}

func (s *server) updateData(req api.Request) api.Response {
	if req.Username == "" || req.Token == "" {
		return api.Response{Success: false, Message: "Faltan credenciales"}
	}
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "Token inválido o sesión expirada"}
	}
	if err := s.db.Put("userdata", []byte(req.Username), []byte(req.Data)); err != nil {
		return api.Response{Success: false, Message: "Error al actualizar datos del usuario"}
	}
	return api.Response{Success: true, Message: "Datos de usuario actualizados"}
}

func (s *server) logoutUser(req api.Request) api.Response {
	if req.Username == "" || req.Token == "" {
		return api.Response{Success: false, Message: "Faltan credenciales"}
	}
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "Token inválido o sesión expirada"}
	}
	if err := s.db.Delete("sessions", []byte(req.Username)); err != nil {
		return api.Response{Success: false, Message: "Error al cerrar sesión"}
	}
	return api.Response{Success: true, Message: "Sesión cerrada correctamente"}
}

// =============================================================================
// Sistema de ficheros virtual
// =============================================================================

// Recibe un fichero (base64), lo reencifra con la clave maestra y lo guarda en bbolt
func (s *server) uploadFile(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	key, ok := s.safePath(req.Username, req.Path)
	if !ok {
		return api.Response{Success: false, Message: "Ruta no válida o insegura"}
	}
	if req.Data == "" {
		return api.Response{Success: false, Message: "Sin contenido para subir"}
	}

	raw, err := base64.StdEncoding.DecodeString(req.Data)
	if err != nil {
		return api.Response{Success: false, Message: "Contenido base64 inválido"}
	}

	// Cifrado en descanso con la clave maestra del servidor.
	enc, err := crypto.Encrypt(raw, s.masterKey)
	if err != nil {
		return api.Response{Success: false, Message: "Error al cifrar el fichero"}
	}
	if err := s.db.Put("files", []byte(key), enc); err != nil {
		return api.Response{Success: false, Message: "Error al guardar el fichero"}
	}

	// Construir la entrada de metadatos.
	name := req.Path
	if idx := strings.LastIndex(name, "/"); idx >= 0 {
		name = name[idx+1:]
	}

	meta := api.FileEntry{
		Name:       name,
		UploadedAt: time.Now().UTC().Format(time.RFC3339),
	}
	if req.Meta != nil {
		meta.Size = req.Meta.Size
		meta.Permissions = req.Meta.Permissions
		meta.ModTime = req.Meta.ModTime
		meta.Platform = req.Meta.Platform
		meta.Tags = req.Meta.Tags
		meta.E2E = req.Meta.E2E
	}

	if err := s.storeMeta(key, meta); err != nil {
		s.log.Printf("Aviso: no se pudieron guardar los metadatos de %s: %v", req.Path, err)
	}

	// Indexar cada tag en el namespace "filetags" como "user:tag:/path".
	for _, tag := range meta.Tags {
		tagKey := req.Username + ":" + tag + ":" + req.Path
		_ = s.db.Put("filetags", []byte(tagKey), []byte("1"))
	}

	return api.Response{Success: true, Message: "Fichero subido: " + req.Path}
}

// Recupera un fichero de bbolt, lo descifra con la masterKey y devuelve el resultado en base64 junto con sus metadatos.
func (s *server) downloadFile(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	key, ok := s.safePath(req.Username, req.Path)
	if !ok {
		return api.Response{Success: false, Message: "Ruta no válida o insegura"}
	}

	enc, err := s.db.Get("files", []byte(key))
	if err != nil {
		return api.Response{Success: false, Message: "Fichero no encontrado: " + req.Path}
	}

	raw, err := crypto.Decrypt(enc, s.masterKey)
	if err != nil {
		return api.Response{Success: false, Message: "Error al descifrar el fichero"}
	}

	// Cargamos los metadatos para adjuntarlos a la respuesta.
	meta, _ := s.loadMeta(key) // metadatos son opcionales

	return api.Response{
		Success: true,
		Message: "Fichero descargado",
		Data:    base64.StdEncoding.EncodeToString(raw),
		Meta:    &meta,
	}
}

// Crea una entrada de directorio en el namespace "dirs".
func (s *server) makedir(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	key, ok := s.safePath(req.Username, req.Path)
	if !ok {
		return api.Response{Success: false, Message: "Ruta no válida o insegura"}
	}
	if err := s.db.Put("dirs", []byte(key), []byte("1")); err != nil {
		return api.Response{Success: false, Message: "Error al crear el directorio"}
	}
	return api.Response{Success: true, Message: "Directorio creado: " + req.Path}
}

// Devuelve los hijos directos de una ruta virtual.
// Cada fichero incluye sus metadatos (FileEntries) además del listing en texto (Entries).
func (s *server) listDir(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	key, ok := s.safePath(req.Username, req.Path)
	if !ok {
		return api.Response{Success: false, Message: "Ruta no válida o insegura"}
	}

	prefix := []byte(key + "/")
	dirKeys, _ := s.db.KeysByPrefix("dirs", prefix)
	fileKeys, _ := s.db.KeysByPrefix("files", prefix)

	var fileEntries []api.FileEntry
	var textEntries []string // listado texto para compatibilidad

	for _, k := range dirKeys {
		name := strings.TrimPrefix(string(k), string(prefix))
		if name == "" || strings.Contains(name, "/") {
			continue // saltar nietos
		}
		fileEntries = append(fileEntries, api.FileEntry{Name: name, IsDir: true})
		textEntries = append(textEntries, "[DIR]  "+name)
	}

	for _, k := range fileKeys {
		name := strings.TrimPrefix(string(k), string(prefix))
		if name == "" || strings.Contains(name, "/") {
			continue
		}
		// Intentamos cargar metadatos; si no existen, entrada mínima.
		entry := api.FileEntry{Name: name, IsDir: false}
		if meta, err := s.loadMeta(string(k)); err == nil {
			entry = meta
			entry.Name = name
			entry.IsDir = false
		}
		fileEntries = append(fileEntries, entry)

		// Línea de texto enriquecida: nombre + tamaño + E2E
		e2eFlag := ""
		if entry.E2E {
			e2eFlag = " [E2E]"
		}
		tagStr := ""
		if len(entry.Tags) > 0 {
			tagStr = " [" + strings.Join(entry.Tags, ",") + "]"
		}
		textEntries = append(textEntries,
			fmt.Sprintf("[FILE] %-30s %8d B  %s%s%s",
				name, entry.Size, entry.UploadedAt, tagStr, e2eFlag))
	}

	if len(fileEntries) == 0 {
		return api.Response{Success: true, Message: "Directorio vacío", Entries: []string{}}
	}

	return api.Response{
		Success:     true,
		Message:     fmt.Sprintf("%d entradas en %s", len(fileEntries), req.Path),
		Entries:     textEntries,
		FileEntries: fileEntries,
	}
}

// Borra fichero o directorio y limpia sus metadatos y tags asociados.
func (s *server) deleteEntry(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	key, ok := s.safePath(req.Username, req.Path)
	if !ok {
		return api.Response{Success: false, Message: "Ruta no válida o insegura"}
	}

	// Cargar metadatos ANTES de borrar para poder limpiar los tags.
	meta, _ := s.loadMeta(key)

	errFile := s.db.Delete("files", []byte(key))
	errDir := s.db.Delete("dirs", []byte(key))
	if errFile != nil && errDir != nil {
		return api.Response{Success: false, Message: "Entrada no encontrada: " + req.Path}
	}

	// Limpiar metadatos y entradas de filetags para no dejar basura en la DB.
	_ = s.db.Delete("meta", []byte(key))
	for _, tag := range meta.Tags {
		tagKey := req.Username + ":" + tag + ":" + req.Path
		_ = s.db.Delete("filetags", []byte(tagKey))
	}

	return api.Response{Success: true, Message: "Entrada eliminada: " + req.Path}
}

// Busca todos los ficheros del usuario que tengan el tag especificado.
func (s *server) listByTag(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	if req.Tag == "" {
		return api.Response{Success: false, Message: "Se requiere un tag para filtrar"}
	}

	prefix := []byte(req.Username + ":" + req.Tag + ":")
	tagKeys, err := s.db.KeysByPrefix("filetags", prefix)
	if err != nil && !errors.Is(err, store.ErrNamespaceNotFound) {
		return api.Response{Success: false, Message: "Error al buscar por tag"}
	}

	var fileEntries []api.FileEntry
	for _, tk := range tagKeys {
		// tk = "alice:trabajo:/docs/informe.pdf"
		// Extraemos la ruta virtual eliminando el prefijo "alice:trabajo:"
		vpath := strings.TrimPrefix(string(tk), string(prefix))

		// Calculamos la clave bbolt para cargar los metadatos.
		bbKey, ok := s.safePath(req.Username, vpath)
		if !ok {
			continue
		}

		entry := api.FileEntry{IsDir: false}
		if meta, err := s.loadMeta(bbKey); err == nil {
			entry = meta
		}
		// Aseguramos que el nombre sea el segmento final de la ruta.
		if idx := strings.LastIndex(vpath, "/"); idx >= 0 {
			entry.Name = vpath[idx+1:]
		} else {
			entry.Name = vpath
		}
		entry.IsDir = false
		fileEntries = append(fileEntries, entry)
	}

	return api.Response{
		Success:     true,
		Message:     fmt.Sprintf("%d fichero(s) con tag '%s'", len(fileEntries), req.Tag),
		FileEntries: fileEntries,
	}
}

// =============================================================================
// Helpers de metadatos
// =============================================================================

// Serializa un FileEntry como JSON, lo cifra con la masterKey y lo guarda.
func (s *server) storeMeta(key string, meta api.FileEntry) error {
	data, err := json.Marshal(meta)
	if err != nil {
		return fmt.Errorf("error serializando metadatos: %w", err)
	}
	enc, err := crypto.Encrypt(data, s.masterKey)
	if err != nil {
		return fmt.Errorf("error cifrando metadatos: %w", err)
	}
	return s.db.Put("meta", []byte(key), enc)
}

// Recupera y descifra los metadatos de un fichero desde el namespace "meta".
func (s *server) loadMeta(key string) (api.FileEntry, error) {
	enc, err := s.db.Get("meta", []byte(key))
	if err != nil {
		return api.FileEntry{}, err
	}
	data, err := crypto.Decrypt(enc, s.masterKey)
	if err != nil {
		return api.FileEntry{}, fmt.Errorf("error descifrando metadatos: %w", err)
	}
	var meta api.FileEntry
	if err := json.Unmarshal(data, &meta); err != nil {
		return api.FileEntry{}, fmt.Errorf("error deserializando metadatos: %w", err)
	}
	return meta, nil
}

// =============================================================================
// Funciones auxiliares
// =============================================================================

// Construye la clave bbolt "username:/ruta/limpia" y valida que sea segura.
func (s *server) safePath(username, path string) (string, bool) {
	if path == "" {
		path = "/"
	}
	if !strings.HasPrefix(path, "/") {
		path = "/" + path
	}
	if strings.Contains(path, "..") {
		s.log.Printf("AVISO: ruta con traversal rechazada (user=%s, path=%s)", username, path)
		return "", false
	}
	path = strings.ReplaceAll(path, "//", "/")
	path = strings.TrimRight(path, "/")
	return username + ":" + path, true
}

// Comprueba si existe un usuario en el namespace 'auth'.
func (s *server) userExists(username string) (bool, error) {
	_, err := s.db.Get("auth", []byte(username))
	if err != nil {
		if errors.Is(err, store.ErrNamespaceNotFound) || errors.Is(err, store.ErrKeyNotFound) {
			return false, nil
		}
		return false, err
	}
	return true, nil
}

// Compara el token en tiempo constante (anti timing-oracle).
func (s *server) isTokenValid(username, token string) bool {
	storedToken, err := s.db.Get("sessions", []byte(username))
	if err != nil {
		return false
	}
	return subtle.ConstantTimeCompare([]byte(storedToken), []byte(token)) == 1
}

// =============================================================================
// Firma, Mensajería E2E, Logs y Backups
// =============================================================================

func (s *server) getPublicKey(req api.Request) api.Response {
	if req.TargetUser == "" {
		return api.Response{Success: false, Message: "TargetUser requerido"}
	}
	data, err := s.db.Get("publickeys", []byte(req.TargetUser))
	if err != nil {
		return api.Response{Success: false, Message: "Claves públicas no encontradas"}
	}
	var keys map[string][]byte
	if err := json.Unmarshal(data, &keys); err != nil {
		return api.Response{Success: false, Message: "Error al leer claves"}
	}
	return api.Response{
		Success:       true,
		Message:       "Claves obtenidas",
		Ed25519PubKey: keys["ed25519"],
		X25519PubKey:  keys["x25519"],
	}
}

// Devuelve la lista de todos los usuarios registrados excepto el solicitante.
func (s *server) listUsers(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	keys, err := s.db.ListKeys("auth")
	if err != nil {
		return api.Response{Success: false, Message: "Error al listar usuarios"}
	}
	var users []string
	for _, k := range keys {
		uname := string(k)
		if uname != req.Username {
			users = append(users, uname)
		}
	}
	return api.Response{Success: true, Message: "OK", Entries: users}
}

func (s *server) sendMessage(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	if req.TargetUser == "" || req.Data == "" {
		return api.Response{Success: false, Message: "Datos incompletos"}
	}

	exists, err := s.userExists(req.TargetUser)
	if err != nil || !exists {
		return api.Response{Success: false, Message: "Destinatario no existe"}
	}

	msg := api.MessageEntry{
		From:      req.Username,
		Data:      req.Data,
		Signature: req.Signature,
		Nonce:     req.Nonce,
		Timestamp: req.Timestamp,
		Time:      time.Now().UTC().Format(time.RFC3339),
	}
	data, _ := json.Marshal(msg)

	// El servidor cifra el mensaje con su clave maestra antes de guardarlo.
	encData, err := crypto.Encrypt(data, s.masterKey)
	if err != nil {
		return api.Response{Success: false, Message: "Error al cifrar mensaje"}
	}

	// Almacenamos: clave = "{destinatario}:{emisor}:{timestamp}" para filtrar por par.
	key := fmt.Sprintf("%s:%s:%d", req.TargetUser, req.Username, time.Now().UnixNano())
	if err := s.db.Put("messages", []byte(key), encData); err != nil {
		return api.Response{Success: false, Message: "Error al guardar mensaje"}
	}
	return api.Response{Success: true, Message: "Mensaje enviado"}
}

// Devuelve los hilos de conversación del usuario con conteo de no leídos.
func (s *server) listConversations(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}

	prefix := []byte(req.Username + ":")
	msgKeys, err := s.db.KeysByPrefix("messages", prefix)
	if err != nil && !errors.Is(err, store.ErrNamespaceNotFound) {
		return api.Response{Success: false, Message: "Error al listar conversaciones"}
	}

	// Agrupar por emisor y contar leídos/no leídos
	type counts struct{ total, unread int }
	senders := map[string]*counts{}
	for _, k := range msgKeys {
		// Formato clave: "{destinatario}:{emisor}:{timestamp}"
		parts := strings.SplitN(string(k), ":", 3)
		if len(parts) < 3 {
			continue
		}
		sender := parts[1]
		if senders[sender] == nil {
			senders[sender] = &counts{}
		}
		senders[sender].total++
		// Comprobar si está marcado como leído
		_, readErr := s.db.Get("messages_read", k)
		if readErr != nil {
			senders[sender].unread++
		}
	}

	var convs []api.ConversationEntry
	for sender, c := range senders {
		convs = append(convs, api.ConversationEntry{
			Sender:      sender,
			TotalCount:  c.total,
			UnreadCount: c.unread,
		})
	}
	return api.Response{Success: true, Message: "OK", Conversations: convs}
}

// Devuelve todos los mensajes de un emisor concreto y los marca como leídos.
func (s *server) fetchMessagesFrom(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	if req.TargetUser == "" {
		return api.Response{Success: false, Message: "Emisor requerido"}
	}

	prefix := []byte(fmt.Sprintf("%s:%s:", req.Username, req.TargetUser))
	msgKeys, err := s.db.KeysByPrefix("messages", prefix)
	if err != nil && !errors.Is(err, store.ErrNamespaceNotFound) {
		return api.Response{Success: false, Message: "Error al recuperar mensajes"}
	}

	var messages []api.MessageEntry
	for _, key := range msgKeys {
		encData, err := s.db.Get("messages", key)
		if err != nil {
			continue
		}
		data, err := crypto.Decrypt(encData, s.masterKey)
		if err != nil {
			continue
		}
		var msg api.MessageEntry
		if err := json.Unmarshal(data, &msg); err == nil {
			messages = append(messages, msg)
		}
		// Marcar como leído
		_ = s.db.Put("messages_read", key, []byte("1"))
	}

	return api.Response{Success: true, Message: "Mensajes recuperados", Messages: messages}
}

func (s *server) fetchMessages(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}

	prefix := []byte(req.Username + ":")
	msgKeys, err := s.db.KeysByPrefix("messages", prefix)
	if err != nil && !errors.Is(err, store.ErrNamespaceNotFound) {
		return api.Response{Success: false, Message: "Error al buscar mensajes"}
	}

	var messages []api.MessageEntry
	for _, key := range msgKeys {
		encData, err := s.db.Get("messages", key)
		if err != nil {
			continue
		}
		data, err := crypto.Decrypt(encData, s.masterKey)
		if err != nil {
			continue
		}
		var msg api.MessageEntry
		if err := json.Unmarshal(data, &msg); err == nil {
			messages = append(messages, msg)
		}
		// Los mensajes no se borran tras su lectura
	}

	return api.Response{Success: true, Message: "Mensajes recuperados", Messages: messages}
}

func (s *server) remoteLog(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	if req.LogEntry == nil {
		return api.Response{Success: false, Message: "Entrada de log requerida"}
	}
	// Guardar el log en data/audit.log
	f, err := os.OpenFile("data/audit.log", os.O_APPEND|os.O_CREATE|os.O_WRONLY, 0644)
	if err != nil {
		return api.Response{Success: false, Message: "Error al escribir log"}
	}
	defer f.Close()
	line := fmt.Sprintf("[%s] [%s] %s: %s\n", req.LogEntry.Time, req.LogEntry.Level, req.Username, req.LogEntry.Message)
	if _, err := f.WriteString(line); err != nil {
		return api.Response{Success: false, Message: "Error guardando log"}
	}
	return api.Response{Success: true, Message: "Log guardado"}
}

func (s *server) backup(req api.Request) api.Response {
	if !s.isTokenValid(req.Username, req.Token) {
		return api.Response{Success: false, Message: "No autenticado"}
	}
	var buf bytes.Buffer
	if err := s.db.Backup(&buf); err != nil {
		return api.Response{Success: false, Message: "Error al generar backup"}
	}

	// Devolvemos el backup codificado en base64
	return api.Response{
		Success: true,
		Message: "Copia de seguridad generada",
		Data:    base64.StdEncoding.EncodeToString(buf.Bytes()),
	}
}
