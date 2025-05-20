package demoapp;

import demoapp.service.SaludoService;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.boot.test.mock.mockito.MockBean;
import org.springframework.test.web.servlet.MockMvc;

import static org.hamcrest.Matchers.containsString;
import static org.mockito.Mockito.when;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.post;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.content;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.status;

@SpringBootTest
@AutoConfigureMockMvc
public class MockServiceTest {

    @Autowired
    private MockMvc mockMvc;

    // Podemos también mockear el servicio
    @MockBean
    private SaludoService service;

    @Test
    public void greetingShouldReturnMessageFromService() throws Exception {

        // Y especificar lo que debe devolver una llamada a uno de sus métodos
        when(service.saluda("Domingo")).thenReturn("Hola Mock Domingo");

        this.mockMvc.perform(get("/saludo/Domingo"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Hola Mock Domingo")));
    }
}
