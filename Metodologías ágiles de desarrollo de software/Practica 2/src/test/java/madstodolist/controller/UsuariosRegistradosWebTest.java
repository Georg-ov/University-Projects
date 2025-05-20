package madstodolist.controller;

import madstodolist.dto.UsuarioData;
import madstodolist.service.UsuarioService;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.test.web.servlet.MockMvc;

import static org.hamcrest.Matchers.containsString;
import static org.mockito.Mockito.when;
import static org.mockito.MockitoAnnotations.openMocks;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.*;

@SpringBootTest
@AutoConfigureMockMvc
public class UsuariosRegistradosWebTest {

    @Autowired
    private MockMvc mockMvc;

    @MockBean // Esto crea un mock de UsuarioService
    private UsuarioService usuarioService;

    @BeforeEach // Esto inicializa los mocks antes de cada prueba
    public void setUp() {
        openMocks(this);
    }

    @Test
    public void getUsuariosRegistradosDevuelveTituloSiEsAdmin() throws Exception {
        // Crea un usuario admin
        UsuarioData adminUser = new UsuarioData();
        adminUser.setId(1L);
        adminUser.setAdmin(true);

        // Simula el comportamiento del servicio
        when(usuarioService.findById(1L)).thenReturn(adminUser);
        when(usuarioService.esAdmin(1L)).thenReturn(true);

        // Realiza la llamada al endpoint y simula la sesión
        this.mockMvc.perform(get("/registrados")
                        .sessionAttr("idUsuarioLogeado", 1L)) // Simula la sesión
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Usuarios Registrados"))); // Cambia al texto esperado real
    }

    @Test
    public void getUsuariosRegistradosDevuelveAccesoDenegado() throws Exception {
        // Crea un usuario no administrador
        UsuarioData nonAdminUser = new UsuarioData();
        nonAdminUser.setId(2L);
        nonAdminUser.setAdmin(false);

        // Simula el comportamiento del servicio
        when(usuarioService.findById(2L)).thenReturn(nonAdminUser);
        when(usuarioService.esAdmin(2L)).thenReturn(false);

        // Realiza la petición GET a /registrados con la sesión simulada
        this.mockMvc.perform(get("/registrados")
                        .sessionAttr("idUsuarioLogeado", 2L)) // Simula la sesión
                .andExpect(status().isOk()) // Se espera que devuelva 200
                .andExpect(view().name("accesoDenegado")); // Verifica que se devuelve la vista de acceso denegado
    }



    @Test
    public void getUsuariosRegistradosContieneTablaConIdYEmail() throws Exception {

        // Crea un usuario admin
        UsuarioData adminUser = new UsuarioData();
        adminUser.setId(3L);
        adminUser.setAdmin(true);

        // Simula el comportamiento del servicio
        when(usuarioService.findById(3L)).thenReturn(adminUser);
        when(usuarioService.esAdmin(3L)).thenReturn(true);

        // Realiza la llamada al endpoint con la sesión simulada
        this.mockMvc.perform(get("/registrados")
                        .sessionAttr("idUsuarioLogeado", 3L)) // Simula la sesión
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("<th>ID</th>")))
                .andExpect(content().string(containsString("<th>Email</th>")));
    }
}
