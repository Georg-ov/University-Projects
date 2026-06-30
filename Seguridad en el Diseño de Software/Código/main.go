/*
'sprout' es una base para el desarrollo de prácticas en clase con Go.

Se puede compilar con "go build" en el directorio donde resida main.go
o "go build -o nombre" para que el ejecutable tenga un nombre distinto

curso: 2026
asignatura: SDS
estudiantes: Georg Usin Osipov
*/
package main

import (
	"fmt"
	"log"
	"net"
	"os"
	"strings"
	"time"

	"golang.org/x/term"

	"sprout/pkg/client"
	"sprout/pkg/server"
	"sprout/pkg/ui"
)

func main() {

	// Creamos un logger con prefijo 'main' para identificar
	// los mensajes en la consola.
	log := log.New(os.Stdout, "[main] ", log.LstdFlags)

	// Comprobamos si el puerto 8443 está libre para arrancar el servidor
	ln, err := net.Listen("tcp", ":8443")
	if err == nil {
		ln.Close() // Puerto libre, levantamos el servidor

		fmt.Print("Introduce la Master Key del servidor para arrancar: ")
		passBytes, err := term.ReadPassword(int(os.Stdin.Fd()))
		fmt.Println() // salto de línea tras la entrada oculta
		if err != nil {
			log.Fatalf("Error leyendo la Master Key: %v", err)
		}
		masterPassword := strings.TrimSpace(string(passBytes))
		if masterPassword == "" {
			log.Fatalf("Error: la master key no puede estar vacía")
		}

		log.Println("Iniciando servidor en segundo plano...")
		go func() {
			if err := server.Run(masterPassword); err != nil {
				log.Fatalf("Error del servidor: %v\n", err)
			}
		}()

		// Esperamos un tiempo prudencial a que arranque el servidor.
		const totalSteps = 20
		for i := 1; i <= totalSteps; i++ {
			ui.PrintProgressBar(i, totalSteps, 30)
			time.Sleep(100 * time.Millisecond)
		}
	} else {
		log.Println("El servidor ya está en ejecución en el puerto 8443. Iniciando solo el cliente...")
	}

	// Inicia cliente.
	log.Println("Iniciando cliente...")
	client.Run()
}
