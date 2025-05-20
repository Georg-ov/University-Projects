package madstodolist.controller;

import madstodolist.authentication.ManagerUserSession; // Asegúrate de importar ManagerUserSession
import madstodolist.repository.UsuarioRepository;
import madstodolist.service.UsuarioService;
import org.junit.jupiter.api.Test;
import org.mockito.Mockito;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.autoconfigure.web.servlet.WebMvcTest;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.mock.web.MockHttpSession;
import org.springframework.test.web.servlet.MockMvc;

import static org.hamcrest.Matchers.containsString;
import static org.hamcrest.Matchers.not;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

@WebMvcTest(LoginController.class)
@AutoConfigureMockMvc
public class RegistroControllerTest {

    @Autowired
    private MockMvc mockMvc;

    @MockBean
    private UsuarioRepository usuarioRepository;

    @MockBean
    private UsuarioService usuarioService;

    @MockBean // Agregado para simular ManagerUserSession
    private ManagerUserSession managerUserSession;

    @Test
    public void testNoMostrarCheckboxAdminCuandoYaHayAdmin() throws Exception {
        // Simulamos que ya hay un administrador registrado
        Mockito.when(usuarioRepository.existsByAdminTrue()).thenReturn(true);

        // Simulamos la petición a la vista de registro
        mockMvc.perform(get("/registro"))
                .andExpect(status().isOk())
                .andExpect(content().string(not(containsString("Registrar como administrador"))));
    }

    @Test
    public void testMostrarCheckboxAdminCuandoNoHayAdmin() throws Exception {
        // Simulamos que no hay administradores registrados
        Mockito.when(usuarioRepository.existsByAdminTrue()).thenReturn(false);

        // Simulamos la petición a la vista de registro
        mockMvc.perform(get("/registro"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Registrar como administrador")));
    }
}
