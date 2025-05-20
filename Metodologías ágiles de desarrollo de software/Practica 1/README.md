[![Review Assignment Due Date](https://classroom.github.com/assets/deadline-readme-button-22041afd0340ce965d47ae6ef1cefeee28c7c493a6346c4f15d667ab976d596c.svg)](https://classroom.github.com/a/ad03hwVn)
# Aplicación inicial Spring Boot

Aplicación básica usando Spring Boot y plantillas Thymeleaf.

## Requisitos

Necesitas tener instalado en tu sistema:

- Java 8

## Ejecución

Puedes ejecutar la aplicación usando el _goal_ `run` del _plugin_ Maven 
de Spring Boot:

```
$ ./mvnw spring-boot:run 
```   

También puedes generar un `jar` y ejecutarlo:

```
$ ./mvnw package
$ java -jar target/demoapp-0.0.1-SNAPSHOT.jar 
```

Una vez lanzada la aplicación puedes abrir un navegador y probar los distintos _controllers_:

- [http://localhost:8080](http://localhost:8080)
- [http://localhost:8080/saludo/Pepito](http://localhost:8080/saludo/Pepito)
- [http://localhost:8080/saludoplantilla/Pepito](http://localhost:8080/saludoplantilla/Pepito)
- [http://localhost:8080/saludoform](http://localhost:8080/saludoform)

# Vista pública de la imagen en Docker Hub
https://hub.docker.com/r/guu1/spring-boot-demoapp
