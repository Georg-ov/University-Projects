# KEBAB AMIGO
Hada group project
## Miembros
- Carla Penalva Box
- Sonia Mendivil Loraiga
- Raúl Ruiz Flores (Coordinador)
- Ignacio Guerrero Martínez
- Adrián Bartel Prieto
- Vahe Maranyan
- Georg Usin

## Índice
- Deadline 1
- Deadline 2
- Deadline 4


# DEADLINE 1

## Descripción

Nuestra aplicación web de kebab ofrece una experiencia de pedido en línea completa y personalizada para los amantes de los kebabs. La plataforma permite a los usuarios navegar fácilmente por un menú detallado y tentador, con descripciones e imágenes de los deliciosos platos que ofrecemos. Los ingredientes frescos, las salsas caseras y las recetas auténticas garantizan una experiencia culinaria excepcional.

## Parte pública

- **Visualización del menú**: Los usuarios podrán ver toda la carta y platos sueltos disponibles en la tienda.
- **Registro de usuarios**: Los usuarios podrán crear una cuenta para acceder a la parte privada de la web.
- **Inicio de sesión**: Los usuarios registrados podrán iniciar sesión en la plataforma.
- **Información sobre el local**: Los usuarios podrán ver información sobre el local, como dirección, horario y contacto.
- **Listado EN Pública**: Menú, Usuario, Local.

## Parte privada

- **Visualización del menú**: Los usuarios registrados podrán ver toda la carta y otros productos disponibles en la tienda.
- **Gestión de pedidos**: Los usuarios registrados podrán realizar pedidos, modificarlos y cancelarlos antes de ser procesados.
- **Historial de pedidos**: Los usuarios registrados podrán ver sus pedidos anteriores.
- **Actualización de datos de usuario**: Los usuarios registrados podrán actualizar su información personal, como dirección de envío y métodos de pago.
- **Listado EN Privada**: Menú, Usuario, Pedido, Historial de pedidos.

## Posibles mejoras

- **Programa de fidelidad**: Implementar un programa de recompensas para los usuarios que realicen compras frecuentes (sistema de puntos).
- **Calificaciones y reseñas**: Permitir a los usuarios registrados calificar y dejar comentarios sobre los productos y su experiencia en la tienda.
- **Promociones y ofertas especiales**: Implementar promociones y ofertas especiales para atraer a nuevos clientes y fidelizar a los existentes.
- **Integración con redes sociales**: Facilitar el acceso y la interacción con la tienda a través de las redes sociales con un branding atractivo.
- **Opción de recogida en tienda**: Permitir a los usuarios recoger sus pedidos directamente en la tienda, evitando gastos de envío.
- **Programa de seguimiento**: Implementar un programa de seguimiento para ver el estado del pedido (en espera, en preparación y en camino).
- **Recomendación**: Muestra al cliente los productos más vendidos.
- **Geolocalización**: Se selecciona el local más cercano en función de la localización del cliente.

## 
1. **Propietario o gerente**: Es la persona responsable de administrar la cadena y tomar decisiones importantes para el negocio.

2. **Empleados**: Los empleados se encargan de preparar y servir los kebabs, así como de realizar las tareas de limpieza y mantenimiento.

3. **Proveedores**: Son las empresas o individuos que suministran los ingredientes, equipos y suministros necesarios para la cadena de kebab.

4. **Clientes**: Las personas que consumen nuestros productos.

## 1. Propietario (Admin)
| Entidad de Negocio | Parte Pública | Parte Privada | 
|:-|:-|:-|
|ENAdmin|-|Total control de la web|
|ENAlbaran|-|Almacenar una factura|
|ENInforme|-|Productos más vendidos |

## 2. Empleados
| Entidad de Negocio | Parte Pública | Parte Privada | 
|:-|:-|:-|
|ENEmpleados|-|Modificar datos empleados|
|ENEntrega|-|Ver el estado de las entregas y las completadas|
 
## 3. Clientes
| Entidad de Negocio | Parte Pública | Parte Privada | 
|:-|:-|:-|
|ENUsuario|Poder registrarse|Almacenar datos usuario|
|ENComentario|Publicar, editar, eliminar... un comentario|Almacenar comentario|
|ENAlbaran|-|Almacenar factura|
|ENPedido|-|Almacenar pedido|
|ENIncidencias|-|Almacenar incidencias|

## 4. Local kebab
| Entidad de Negocio | Parte Pública | Parte Privada | 
|:-|:-|:-|
|ENLocal|Ver productos del kebab|Modificar datos|
|ENOferta|Visualizar ofertas|El local podrá crear, eliminar y modificar ofertas a sus propios productos|
|ENProducto|Visualizar productos|Crear, modificar o eliminar productos|
|ENMenu|Visualizar menus|Crear, modificar o eliminar menus|




# DEADLINE 2
El esquema de la BBDD se encuentra subido en la rama main en formato PDF.


# DEADLINE4

## Idea inicial
Este proyecto se enfoca en la creación de una página web para un restaurante de kebab con varios locales en la ciudad, KEBAB AMIGO. Al entrar en esta se puede observar un diseño atractivo y homogeneo. En el menÚ, a la izquierda, podemos encontrar el logo de Kebab Amigo, al pulsar sobre este te llevará a Incio. Esto seguido de Mapa, Inicio, Contacto, Nosotros, las diferentes redes sociales y el apartado para loggearse, entraremos en profundidad más adelante. La página de Inicio te da la bienvenida con el branding del restaruante, bajando temos un section con fotos atractivas de diferentes productos con un botón de order now que nos llevará a Productos. Siguiendo tenemos un section con distintos reviews de clientes. Debajo de este medio section posicionado a la izquierda con información basica del local y un botón que lleva a Nosotros. Por úlitmo tenemos el footer con nuestras calle, coordenadas y un pequeño menu funcional, el logo de ganadores de un premio gastronomico y las redes de nuevo.

- Mapa (Mejora): En este apartado puedes ver el mapa de google maps con la posición de nuestros locales que podrás seleccionar con un dropdown. Abajo, el nombre del local y su calle propía que variaran dependiendo del local que se seleccione.

- Productos: En productos tenemos una barra de acceso rápido y abajo un buscador para encontrar un produto en especifico. Abajo se muestran los menus disponibles en ese local y momento, los entrantes, etc. Al pulsar sobre el producto se ve la información y deja añadirlo a la cesta. Oferta nos abre un slider con las ofertas disponibles en el momento y a su lado el carrito donde vemos el total, el local al cual queremos pedir y el botón para pasar a la plataforma de pago.

- Contacto: abre un slider con infromación del restaurante y enlaces directos para llamar o mandarnos un correo.

- Nosotros: este apartado te recibe con un botón de order now para incentivar a la compra y debajo de este información sobre Kebab Amigo.

- Redes Sociales (Mejora): se han creado redes tanto en facebook como twitter e instagram. La última la más activa donde nuestros clientes están interactuando ya con nuestras fotos.

- Login/Register: al pulsar aparecerá un container donde podremos inciar sesión o registrarnos con una animación unica que encontraras en nuestro .js.

Al iniciar sesion nos permitirá ahora hacer y crear comentarios (solo se pueden borrar los de uno mismo), también podremos hacer pedidos y entrar en nuestro perfil. En este podremos modificar nuestro email confirmando la contraseña, modificar la contraseña, ver los pedidos realizados y cerrar sesión.

En la parte de admin tendremos un menú personalizado con los informes, incidencias, locales y el perfil del admin.
- Informes: en infrome podremos ver una gráfica con las ventas de nuestros diferentes locales.
- Incidencias: el admin puede ver las diferentes incidencias de los locales y puede eliminarlas cuando estén solucionadas.
- Locales: en locales se puede ver toda la información de los estos y puede eliminarlos.

Como panel de admin tenemos formularios para crear comentarios, incidencias, locales, crear ofertas, meter la información de un nuevo empleado, crear menues y crear productos.

En la parte de empleado tendremos un menu parecido al de amdmin pero con menos capacidades.

# Presentación
https://docs.google.com/presentation/d/1lroWi02L45gz6lVNVTCAlE6pbPSwxTuM/edit?usp=sharing&ouid=100200713660829368853&rtpof=true&sd=true

## Oraganización y trabajo
Para esta deadline se crearon los issues con las tareas y se asignaron a los miembros correspondiente. Cada uno comenzó a trabajar en su tarea y nos conectabamos a un servidor privado de discord para comunicarnos entre nostros con más eficiencia y poder ayudar cuando un compañero lo necesitase. Esto esta reflejado en forma de tabla en el archivo Excel que se ha subido como TAREAS.xlsx, como indica la leyenda las estrellas muestran quien ha hecho cada tarea y los compañeros que han participado para ayudarle a resolver algún problema. Al final todos hemos tocado un poco de código de cada cosa y en especial resaltar la participación de Sonia que se ha mostrado entusiasmada con el proyecto y ha resuelto problemas y dudas a compañeros. También agradecer a Adri por resolvernos las dudas que teniamos, gracias a que es de cursos superiores.

## Cambios y Dificultades
El equipo está satisfecho con el trabajo realizado pero no hemos podido perfeccionar la página como teniamos planeado desde un principio debido a ciertos problemas. En esta entrega no se contó con la participación de un miembro el cual se encargaba de los EN/CAD de pedido y el procesamiento de pagos, algo fundamental para nuestra página. Esto nos ha llevado a problemas de organización porque se nos comunicó en el úlitmo momento. Para resolver esto la última semana se creaban listas de tareas las cuales podían elegir los miembros de grupo y al terminar con la elegida poder continuar con otra. Hemos tenido que sacrificar mejoras que ibamos a implementar y el perfilar la página para cubrir estas necesidades. El pedido iba a ser mucho más complejo según la demo del comienzo de la practica, el panel del admin y empleado iban a ser mucho más complejas, la mejora de comentarios iba ser también más compleja.

Sobre las posibles mejoras que queriamos implementar hemos tenido que cambiar la geolocaliza, evidentemente :).

## Instrucciones de instalación
Para ejecutar la practica debe crear el archivo de base de datos sql en la carpeta App_data con el nombre "BD_kebab" y ejecutar los scrips sql. En primer lugar el archivo llamado tablas.sql y después insertar.sql. Para pedir hay que seleccionar un local y para ver que se han registrado los pedidos tiene que hacer una consulta a albarán.

## Usuario y Contraseña
La página web ofrece distintas funcionalidades dependiendo de que tipo de usuario se loggee.
Si entras en la web sin loggearte podrás ver los productos pero no pedirlos, ver los comentarios pero no hacerlos fomentando así que el usuario introduzca sus datos.
Si te loggeas como cliente ya tendrás acceso a estas funcionalidades y podrás entrar a tu perfil para manejar tus datos.
- Usuario: usuario1@gmail.com
- Contraseña: Contraseña1
- Si entras como Admin tendrás acceso a un menú privado donde podrás ver informes; ver, crear y eliminar incidencias; ver, crear y eliminar comentarios; ver, crear y eliminar locales; ver, crear y eliminar menus, etc.
- Usuario: admin@kebab.com
- Contraseña: admin123
- Si entras en la web empleado se te abrirá además un nuevo menú donde podrás ver la información de los locales, datos del local, comentarios, incidencias, etc.
- Usuario: empleado@gmail.com
- Contraseña: empleado2


