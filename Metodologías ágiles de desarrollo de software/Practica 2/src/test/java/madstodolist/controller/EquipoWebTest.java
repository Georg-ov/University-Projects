package madstodolist.controller;

import madstodolist.authentication.ManagerUserSession;
import madstodolist.model.Equipo;
import madstodolist.model.Usuario;
import madstodolist.repository.EquipoRepository;
import madstodolist.repository.UsuarioRepository;
import madstodolist.service.EquipoService;
import madstodolist.dto.EquipoData;
import org.assertj.core.api.Assertions;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.mock.web.MockHttpSession;
import org.springframework.test.web.servlet.MockMvc;

import javax.servlet.http.HttpSession;
import java.util.Arrays;
import java.util.List;
import java.util.Map;

import static org.assertj.core.api.AssertionsForClassTypes.assertThat;
import static org.junit.jupiter.api.Assertions.assertNotNull;
import static org.mockito.Mockito.when;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.*;
import static org.springframework.test.web.servlet.result.MockMvcResultHandlers.print;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;
import static org.hamcrest.Matchers.*;

@SpringBootTest
@AutoConfigureMockMvc
public class EquipoWebTest {

    @Autowired
    private MockMvc mockMvc;

    @MockBean
    private EquipoService equipoService;

    @Autowired
    private EquipoRepository equipoRepository;

    @MockBean
    private UsuarioRepository usuarioRepository;

    @MockBean
    private ManagerUserSession managerUserSession;

    @Test
    public void testListadoEquipos() throws Exception {
        // GIVEN
        // Creamos una lista de equipos de prueba
        EquipoData equipo1 = new EquipoData();
        equipo1.setNombre("Equipo A");

        EquipoData equipo2 = new EquipoData();
        equipo2.setNombre("Equipo B");

        List<EquipoData> equipos = Arrays.asList(equipo1, equipo2);

        // Configuramos el mock del servicio para que devuelva esta lista
        when(equipoService.findAllOrdenadoPorNombre()).thenReturn(equipos);

        // Simulamos un objeto usuario
        Usuario usuario = new Usuario();
        usuario.setId(1L); // Ajusta según la estructura de tu clase Usuario

        // WHEN, THEN
        this.mockMvc.perform(get("/equipos")
                        .sessionAttr("usuario", usuario)) // Pasamos el usuario a la sesión
                .andExpect(status().isOk())
                .andExpect(view().name("listaEquipos"))
                .andExpect(model().attributeExists("equipos"))
                .andExpect(model().attribute("equipos", hasSize(2)))
                .andExpect(model().attribute("equipos", hasItem(
                        allOf(
                                hasProperty("nombre", is("Equipo A"))
                        )
                )))
                .andExpect(model().attribute("equipos", hasItem(
                        allOf(
                                hasProperty("nombre", is("Equipo B"))
                        )
                )));
    }

    private Long addUsuarioBD() {
        Usuario usuario = new Usuario("email@prueba.com");
        usuarioRepository.save(usuario);
        return usuario.getId();
    }

    @Test
    public void getNuevoEquipoDevuelveForm() throws Exception {
        // GIVEN
        // Un usuario con dos tareas en la BD
        Long usuarioId = addUsuarioBD();

        // Ver el comentario en el primer test
        when(managerUserSession.usuarioLogeado()).thenReturn(usuarioId);

        // WHEN, THEN
        // si ejecutamos una petición GET para crear una nueva tarea de un usuario,
        // el HTML resultante contiene un formulario y la ruta con
        // la acción para crear la nueva tarea.

        String urlPeticion = "/equipos/nuevo";
        String urlAction = "action=\"/equipos/nuevo\""; // Corregido aquí

        this.mockMvc.perform(get(urlPeticion))
                .andExpect(content().string(allOf(
                        containsString("form method=\"post\""),
                        containsString(urlAction)
                )));
    }

    @Test
    public void postNuevoEquipoDevuelveRedirectYAñadeEquipo() throws Exception {
        // GIVEN
        // Un usuario con dos tareas en la BD
        Long usuarioId = addUsuarioBD();

        // Ver el comentario en el primer test
        when(managerUserSession.usuarioLogeado()).thenReturn(usuarioId);

        // WHEN, THEN
        // realizamos la petición POST para añadir una nueva tarea,
        // el estado HTTP que se devuelve es un REDIRECT al listado
        // de tareas.

        String urlPost = "/equipos/nuevo";
        String urlRedirect = "/equipos";

        this.mockMvc.perform(post(urlPost)
                        .param("nombre", "Los HipopotamosFC"))
                .andExpect(status().is3xxRedirection())
                .andExpect(redirectedUrl(urlRedirect));
        
    }

    @Test
    public void testEntrarUsuarioEnEquipo() throws Exception {
        // GIVEN
        Long equipoId = 1L;
        Long usuarioId = 2L;

        // WHEN, THEN
        // Realizamos la petición GET al enlace generado dinámicamente para "Entrar"
        this.mockMvc.perform(get("/equipos/{idE}/usuario/{idU}/agregar", equipoId, usuarioId))
                .andExpect(status().is3xxRedirection())  // Verificamos que la redirección es correcta
                .andExpect(redirectedUrl("/equipos"));  // Verificamos que la redirección es a la lista de equipos
    }

    @Test
    public void testSalirUsuarioDeEquipo() throws Exception {
        // GIVEN
        Long equipoId = 1L;
        Long usuarioId = 2L;

        // WHEN, THEN
        // Realizamos la petición GET al enlace generado dinámicamente para "Salir"
        this.mockMvc.perform(get("/equipos/{idE}/usuario/{idU}/eliminar", equipoId, usuarioId))
                .andExpect(status().is3xxRedirection())  // Verificamos que la redirección es correcta
                .andExpect(redirectedUrl("/equipos"));  // Verificamos que la redirección es a la lista de equipos
    }

    @Test
    public void testFormEditaEquipo() throws Exception {
        // GIVEN
        Long equipoId = 1L;
        EquipoData equipoData = new EquipoData();
        equipoData.setNombre("Equipo Original");

        // Mock de la lógica del servicio
        when(equipoService.findById(equipoId)).thenReturn(equipoData);

        // WHEN, THEN
        this.mockMvc.perform(get("/equipos/{id}/editar", equipoId))
                .andExpect(status().isOk())  // Verificamos que la respuesta es correcta
                .andExpect(view().name("formEditarEquipo"))  // Verificamos que se carga la vista correcta
                .andExpect(model().attributeExists("equipo"))  // Verificamos que el modelo contiene el equipo
                .andExpect(model().attribute("equipo", hasProperty("nombre", is("Equipo Original"))));  // Verificamos que el nombre del equipo es correcto
    }

    @Test
    public void deleteTareaDevuelveOKyBorraTarea() throws Exception {

        Long equipoId = 1L;
        EquipoData equipoData = new EquipoData();
        equipoData.setNombre("Equipo Original");

        // Mock de la lógica del servicio
        when(equipoService.findById(equipoId)).thenReturn(equipoData);

        String urlDelete = "/equipos/" + equipoId.toString();

        this.mockMvc.perform(delete(urlDelete))
                .andExpect(status().isOk());

        // y cuando se pide un listado de tareas del usuario, la tarea borrada ya no aparece.

        String urlListado = "/equipos/";

        this.mockMvc.perform(get(urlListado))
                .andExpect(content().string(
                        allOf(not(containsString("Equipo Original")))));
    }


}

