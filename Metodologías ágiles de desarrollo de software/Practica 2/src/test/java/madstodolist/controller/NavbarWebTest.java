package madstodolist.controller;

import madstodolist.dto.UsuarioData;
import madstodolist.service.UsuarioService;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.test.web.servlet.MockMvc;

import java.util.Date;

import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.not;
import static org.mockito.Mockito.when;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.content;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.status;

@SpringBootTest
@AutoConfigureMockMvc
public class NavbarWebTest {

    @Autowired
    private MockMvc mockMvc;

    // MockBean crea un mock que será inyectado en lugar de la implementación real
    @MockBean
    private UsuarioService usuarioService;

    @Test
    public void getHomeDevuelveEnlacesCorrectos() throws Exception {
        this.mockMvc.perform(get("/about")) // Cambia la ruta según sea necesario
                .andExpect(content().string(containsString("ToDoList"))) // Verifica que el nombre de la aplicación esté presente
                .andExpect(content().string(containsString("Login")))   // Verifica que el enlace de login esté presente
                .andExpect(content().string(containsString("Registro"))); // Verifica que el enlace de registro esté presente
    }

    @Test
    public void getHomeDevuelveEquiposSiUsuarioRegistrado() throws Exception {
        // GIVEN
        Long userId = 1L;
        UsuarioData usuarioData = new UsuarioData();
        usuarioData.setId(userId);
        usuarioData.setEmail("user1@example.com");
        usuarioData.setNombre("User1");
        usuarioData.setFechaNacimiento(new Date());

        // Configura el comportamiento del mock
        when(usuarioService.findById(userId)).thenReturn(usuarioData);

        // WHEN y THEN
        this.mockMvc.perform(get("/about")
                        .sessionAttr("idUsuarioLogeado", userId)) // Simula la sesión con el usuario logueado
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("ToDoList"))) // Verifica que el nombre de la aplicación esté presente
                .andExpect(content().string(containsString("Equipos")))  // Verifica que el enlace de logout esté presente
                .andExpect(content().string(not(containsString("Login")))) // Verifica que el enlace de login no esté presente
                .andExpect(content().string(not(containsString("Registro")))); // Verifica que el enlace de registro no esté presente
    }
}
