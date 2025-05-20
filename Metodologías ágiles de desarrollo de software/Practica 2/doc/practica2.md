# PRACTICA 2

## Listado de nuevas clases y métodos implementados:

- **HomeController**
    - `addAtributes`: Esta función estará en varias clases más, la utilizaremos para saber si hay un usuario registrado o no para mostrar distintas barras de menú.
    - `about`: Función para devolver la vista "about".

- **LoginController**
    - Agregadas las inicializaciones de los atributos 'admin' y 'bann' en el `postMapping` de `/login`.

- **TareaController**
    - `addAtributes`: Misma función que la de la clase HomeController.

- **UsuariosController**
    - `addAtributes`
    - `listadoUsuarios`: Función para obtener una lista de todos los usuarios registrados, solo lo podrá visualizar el admin.
    - `verDetalleUsuarios`: Función para obtener los atributos de un usuario.
    - `bloquearUsuario`: Función para bloquear el acceso a un usuario, se utilizará a través de la lista que obtenemos gracias a la función `listadoUsuarios`.
    - `desbloquearUsuario`: Función para desbloquear el acceso a un usuario, se utilizará igual que la anterior.

- **LoginData**
    - `getAdmin`: Para obtener el atributo admin del usuario.

- **RegistroData**
    - `getAdmin`
    - `setAdmin`

- **UsuarioData y Usuario**
    - `getAdmin`
    - `setAdmin`
    - `getBann`
    - `setBann`

- **UsuarioRepository**
    - `existsByAdminTrue`: Para consultar en la base de datos la existencia de un usuario ADMIN.

- **UsuarioService**
    - `findAllUsers`: Servicio para obtener todos los usuarios.
    - `existeAdmin`: Función que devuelve un booleano dependiendo si existe o no un ADMIN.
    - `esAdmin`: Devuelve true si el usuario es ADMIN.
    - `bloquearUsuario`: Servicio para bannear un usuario y restringirle el acceso.
    - `desbloquearUsuario`: Servicio para desbannear un usuario.

## Listado de plantillas Thymeleaf añadidas:

- `about.html`
- En `fragments.html` se añadió el fragmento navbar que representa la barra de menú.
- `accesoDenegado.html`: No es como tal una plantilla Thymeleaf, es un código muy simple y básico de HTML.
- `usuarioBanneado.html`: Igual que el anterior.
- `detallesUsuario.html`
- `listaTareas.html`
- `registrados.html`

## Explicación de los tests implementados:

- **AcercaDeWebTest**
    - `getAboutDevuelveNombreAplicacion`: Test para comprobar que se obtiene correctamente la vista 'about'.

- **DetallesUsuariosWebTest**
    - `getDetallesUsuarioDevuelveUsuarioCorrecto`: Test para comprobar que la información detallada del usuario se muestra correctamente.

- **NavBarWebTest**
    - `getHomeDevuelveEnlacesCorrectos`: Comprobamos que para un usuario no registrado se muestren ciertas opciones en la barra.

- **RegistroControllerTest**
    - `testNoMostrarCheckboxAdminCuandoYaHayAdmin`: Prueba para comprobar que no se muestra el checkbox durante el registro si ya hay un ADMIN en la BD.
    - `testMostrarCheckboxAdminCuandoNoHayAdmin`: Al contrario que la anterior, aquí se comprueba que sí se muestra el checkbox cuando no existe un ADMIN.

- **UsuariosRegistradosWebTest**
    - `getUsuariosRegistradosDevuelveTituloSiEsAdmin`: Comprobar que se muestre la vista 'registrados' si se ha accedido a la vista siendo ADMIN.
    - `getUsuariosRegistradosDevuelveAccesoDenegado`: Comprobar que se muestre la vista 'accesoDenegado' si se ha accedido a la vista no siendo ADMIN.
    - `getUsuariosRegistradosContieneTablaConIdYEmail`: Comprobar que la vista 'registrados' muestre la información debida.

- **UsuarioWebTest**
    - `servicioLoginUsuarioBanneado`: Prueba que comprueba si se muestra la vista 'usuarioBanneado' al iniciar sesión un usuario que está banneado.

## Explicación de código fuente relevante de las nuevas funcionalidades implementadas:

```java
@ModelAttribute
public void addAttributes(Model model) {
    boolean isUsuarioLogueado = managerUserSession.isUsuarioLogeado();
    long usuarioId = isUsuarioLogueado ? managerUserSession.usuarioLogeado() : 0L;

    model.addAttribute("usuarioLogueado", isUsuarioLogueado);
    model.addAttribute("usuarioId", usuarioId);

    // Agregar el objeto usuario completo al modelo si está logueado
    if (isUsuarioLogueado) {
        UsuarioData usuario = usuarioService.findById(usuarioId);
        model.addAttribute("usuario", usuario);
    } else {
        model.addAttribute("usuario", null); // Asegúrate de que usuario esté presente
    }
}

Explicaré la función `addAtributes`, ya que se repite tres veces. Esta función la utilizaremos para saber qué opciones de la barra de búsqueda se mostrarán en la página. Estas opciones difieren cuando hay un usuario autenticado y cuando no lo hay.

Primero, almacenamos en la variable `isUsuarioLogeado` un booleano que indica, gracias a la función `isUsuarioLogeado()`, si hay un usuario logueado. Luego, guardamos en `usuarioId` el ID del usuario logueado si está logueado o 0 de forma predeterminada. 

Después de eso, almacenamos en el modelo los atributos anteriormente mencionados, `'isUsuarioLogueado'` y `'usuarioId'`. Finalmente, mediante una estructura `if`, si el usuario está logueado, se guarda en la variable `usuario` de tipo `UsuarioData` el usuario de la misma ID que la almacenada en `usuarioId` y se almacena en el modelo. Si no está autenticado, se almacena en el modelo un usuario nulo.


