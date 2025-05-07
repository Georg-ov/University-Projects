# IO.Swagger - ASP.NET Core 2.0 Server

API para la gestión de salas, niveles, dispositivos y notificaciones en un edificio inteligente.

## Run

Linux/OS X:

```
sh build.sh
```

Windows:

```
build.bat
```

## Run in Docker

```
cd src/IO.Swagger
docker build -t io.swagger .
docker run -p 5000:5000 io.swagger
```
