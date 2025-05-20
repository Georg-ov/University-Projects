package madstodolist.controller;

import madstodolist.dto.EquipoData;
import madstodolist.dto.UsuarioData;
import madstodolist.model.Equipo;
import madstodolist.model.Usuario;
import madstodolist.service.EquipoService;
import madstodolist.service.UsuarioService;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.test.web.servlet.MockMvc;

import java.util.Collections;

import static org.hamcrest.Matchers.containsString;
import static org.mockito.Mockito.when;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.post;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

@SpringBootTest
@AutoConfigureMockMvc
//
// A diferencia de los tests web de tarea, donde usábamos los datos
// de prueba de la base de datos, aquí vamos a practicar otro enfoque:
// moquear el usuarioService.
public class UsuarioWebTest {

    @Autowired
    private MockMvc mockMvc;

    // Moqueamos el usuarioService.
    // En los tests deberemos proporcionar el valor devuelto por las llamadas
    // a los métodos de usuarioService que se van a ejecutar cuando se realicen
    // las peticiones a los endpoint.
    @MockBean
    private UsuarioService usuarioService;

    @MockBean
    private EquipoService equipoService;

    @Test
    public void servicioLoginUsuarioOK() throws Exception {
        // GIVEN
        // Moqueamos la llamada a usuarioService.login para que
        // devuelva un LOGIN_OK y la llamada a usuarioServicie.findByEmail
        // para que devuelva un usuario determinado.

        UsuarioData anaGarcia = new UsuarioData();
        anaGarcia.setNombre("Ana García");
        anaGarcia.setId(1L);
        anaGarcia.setBann(false);

        when(usuarioService.login("ana.garcia@gmail.com", "12345678"))
                .thenReturn(UsuarioService.LoginStatus.LOGIN_OK);
        when(usuarioService.findByEmail("ana.garcia@gmail.com"))
                .thenReturn(anaGarcia);

        // WHEN, THEN
        // Realizamos una petición POST al login pasando los datos
        // esperados en el mock, la petición devolverá una redirección a la
        // URL con las tareas del usuario

        this.mockMvc.perform(post("/login")
                        .param("eMail", "ana.garcia@gmail.com")
                        .param("password", "12345678"))
                .andExpect(status().is3xxRedirection())
                .andExpect(redirectedUrl("/usuarios/1/tareas"));
    }

    @Test
    public void servicioLoginUsuarioBanneado() throws Exception {
        // GIVEN
        // Moqueamos la llamada a usuarioService.login para que
        // devuelva un LOGIN_OK y la llamada a usuarioServicie.findByEmail
        // para que devuelva un usuario determinado.

        UsuarioData pedro = new UsuarioData();
        pedro.setNombre("Pedro Sanchez");
        pedro.setId(2L);
        pedro.setBann(true);

        when(usuarioService.findByEmail("pedro.sanchez@gmail.com")).thenReturn(pedro);
        when(usuarioService.login("pedro.sanchez@gmail.com", "12345678")).thenReturn(UsuarioService.LoginStatus.LOGIN_OK);

        // WHEN, THEN
        // Realizamos una petición POST al login pasando los datos
        // esperados en el mock, la petición devolverá una redirección a la
        // URL con las tareas del usuario

        this.mockMvc.perform(post("/login")
                        .param("eMail", "pedro.sanchez@gmail.com")
                        .param("password", "12345678"))
                .andExpect(status().isOk())
                .andExpect(view().name("usuarioBanneado"));
    }

    @Test
    public void servicioLoginUsuarioNotFound() throws Exception {
        // GIVEN
        // Moqueamos el método usuarioService.login para que devuelva
        // USER_NOT_FOUND
        when(usuarioService.login("pepito.perez@gmail.com", "12345678"))
                .thenReturn(UsuarioService.LoginStatus.USER_NOT_FOUND);

        // WHEN, THEN
        // Realizamos una petición POST con los datos del usuario mockeado y
        // se debe devolver una página que contenga el mensaja "No existe usuario"
        this.mockMvc.perform(post("/login")
                        .param("eMail","pepito.perez@gmail.com")
                        .param("password","12345678"))
                .andExpect(content().string(containsString("No existe usuario")));
    }

    @Test
    public void servicioLoginUsuarioErrorPassword() throws Exception {
        // GIVEN
        // Moqueamos el método usuarioService.login para que devuelva
        // ERROR_PASSWORD
        when(usuarioService.login("ana.garcia@gmail.com", "000"))
                .thenReturn(UsuarioService.LoginStatus.ERROR_PASSWORD);

        // WHEN, THEN
        // Realizamos una petición POST con los datos del usuario mockeado y
        // se debe devolver una página que contenga el mensaja "Contraseña incorrecta"
        this.mockMvc.perform(post("/login")
                        .param("eMail","ana.garcia@gmail.com")
                        .param("password","000"))
                .andExpect(content().string(containsString("Contraseña incorrecta")));
    }

    @Test
    public void listadoUsuariosEquipo() throws Exception {
        // GIVEN
        Long idEquipo = 1L;
        String nombreEquipo = "Equipo A";

        // Crear un único usuario
        Usuario usuario = new Usuario();
        usuario.setId(1L);
        usuario.setNombre("Juan");
        usuario.setEmail("juan@ejemplo.com"); // Asegúrate de agregar un email para ordenar

        // Crear el equipo y asignar el usuario
        Equipo equipo = new Equipo(nombreEquipo);
        equipo.setId(idEquipo);
        equipo.addUsuario(usuario);

        // Crear los objetos UsuarioData para mapearlos
        UsuarioData usuarioData = new UsuarioData();
        usuarioData.setId(usuario.getId());
        usuarioData.setNombre(usuario.getNombre());
        usuarioData.setEmail(usuario.getEmail());

        // Crear el objeto EquipoData para mapearlo
        EquipoData equipoData = new EquipoData();
        equipoData.setId(equipo.getId());
        equipoData.setNombre(equipo.getNombre());

        // Moquear los servicios
        when(equipoService.findById(idEquipo)).thenReturn(equipoData);  // Moqueamos el servicio para devolver el equipo mapeado
        when(usuarioService.allUsuariosEquipo(idEquipo)).thenReturn(Collections.singletonList(usuarioData)); // Moqueamos el servicio para devolver los usuarios mapeados

        // WHEN, THEN
        String url = "/equipos/" + idEquipo + "/usuarios";

        this.mockMvc.perform(get(url))
                .andExpect(status().isOk()) // Verificamos que la respuesta es correcta
                .andExpect(view().name("listaUsuariosEquipo")) // Verificamos que la vista es la correcta
                .andExpect(model().attributeExists("equipo", "usuario")) // Comprobamos que el modelo contiene estos atributos
                .andExpect(model().attribute("equipo", equipoData)) // Verificamos que el equipo mapeado esté en el modelo
                .andExpect(model().attribute("usuario", Collections.singletonList(usuarioData))) // Verificamos que el usuario mapeado esté en el modelo
                .andExpect(content().string(containsString("Juan"))); // Verificamos que el nombre de Juan esté en el HTML
    }

}
