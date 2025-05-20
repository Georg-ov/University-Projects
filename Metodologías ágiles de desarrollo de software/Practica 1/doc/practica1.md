## Práctica 1

He implementado un formulario que recibe un número no nulo ni negativo y lo procesa.  
Si el número es par se mostrará un mensaje de carácter alegre, y si no lo es, uno de carácter más triste.

Primero he hecho el servicio que será el encargado de procesar el número con la ayuda de un `if`.  
Si al dividirlo entre dos el resto es 0, el número será par; si es distinto de 0, será impar.  
En los dos casos se almacenará un `String` que luego se mostrará.

Luego he realizado dos vistas:
1. **formPar**: mostrará el formulario en sí con una sola casilla que aceptará números no negativos y un botón "Enviar" para enviar la información al controlador.
2. **esPar**: se utilizará tras procesar la información. Aquí se mostrará un mensaje u otro, que habrá sido obtenido del servicio.

El controlador se encarga de manejar las vistas:
- La primera función muestra la vista **formPar** cuando la URL es `/formPar`.
- La segunda función llama al servicio y devuelve la segunda vista **esPar**.

Adicionalmente, para el correcto funcionamiento de la aplicación y para las validaciones de los atributos, se ha creado una clase **NumberData** con dos restricciones: que sea no nulo y mayor que 0.

#### Tests

Respecto a los tests de la capa de servicio, se han añadido dos tests para el servicio **EvenService**: 
- El primero es un test que recibe como atributo un número par. 
- El segundo recibe un número impar.

Ambos tests esperan los mensajes correspondientes para cada tipo de número. Si los mensajes son incorrectos, los tests fallarán.

Los tests de la capa de presentación harán las mismas pruebas que los tests de servicio, pero utilizando mocks y orientados a la capa de presentación.

##### URL Github
https://github.com/mads-UA-24-25/spring-boot-demo-guu1-ua

##### URL DockerHub
https://hub.docker.com/r/guu1/spring-boot-demoapp/tags

