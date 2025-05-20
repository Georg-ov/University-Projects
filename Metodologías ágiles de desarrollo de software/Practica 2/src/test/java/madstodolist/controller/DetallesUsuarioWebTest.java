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

import static org.hamcrest.Matchers.*;
import static org.mockito.Mockito.*;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.*;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

@SpringBootTest
@AutoConfigureMockMvc
public class DetallesUsuarioWebTest {

    @Autowired
    private MockMvc mockMvc;

    @MockBean
    private UsuarioService usuarioService;

    @Test
    public void getDetallesUsuarioDevuelveUsuarioCorrecto() throws Exception {
        // Given
        Long userId = 1L;
        UsuarioData usuarioData = new UsuarioData();
        usuarioData.setId(userId);
        usuarioData.setEmail("user1@example.com");
        usuarioData.setNombre("User1");
        usuarioData.setFechaNacimiento(new Date());
        usuarioData.setAdmin(true); // Asegúrate de que este usuario es un administrador

        when(usuarioService.findById(userId)).thenReturn(usuarioData);
        when(usuarioService.esAdmin(userId)).thenReturn(true); // Asegúrate de que la verificación de admin también se simula

        // When y Then
        this.mockMvc.perform(get("/registrados/" + userId)
                        .sessionAttr("idUsuarioLogeado", userId)) // Simula la sesión con el usuario logueado
                .andExpect(status().isOk())
                .andExpect(view().name("detallesUsuario"))
                .andExpect(model().attributeExists("usuario"))
                .andExpect(model().attribute("usuario", hasProperty("id", is(userId))))
                .andExpect(model().attribute("usuario", hasProperty("email", is("user1@example.com"))))
                .andExpect(model().attribute("usuario", hasProperty("nombre", is("User1"))));
    }
}
