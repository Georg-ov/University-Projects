package demoapp;

import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.autoconfigure.web.servlet.AutoConfigureMockMvc;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.test.web.servlet.MockMvc;

import static org.hamcrest.Matchers.containsString;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.get;
import static org.springframework.test.web.servlet.request.MockMvcRequestBuilders.post;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.content;
import static org.springframework.test.web.servlet.result.MockMvcResultMatchers.status;

@SpringBootTest
@AutoConfigureMockMvc
public class MockMvcTest {

    @Autowired
    private MockMvc mockMvc;

    // Hacemos una petición GET a un end point y comprobamos que
    // el HTML resultante es correcto
    @Test
    public void shouldReturnDefaultMessage() throws Exception {
        this.mockMvc.perform(get("/"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Georg Usin")));
    }

    // Podemos hacer también una petición POST y pasar los datos
    // del formulario con el método .param
    @Test
    public void postShoudReturnCorrectResponse() throws Exception {
        this.mockMvc.perform(post("/saludoform")
                .param("nombre", "Domingo"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Hola Domingo")));
    }

    @Test
    public void procesarFormularioPar() throws Exception {
        this.mockMvc.perform(post("/formPar")
                .param("numero", "2"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Enhorabuena, el 2 es par!!!")));
    }

    @Test
    public void procesarFormularioImpar() throws Exception {
        this.mockMvc.perform(post("/formPar")
                        .param("numero", "3"))
                .andExpect(status().isOk())
                .andExpect(content().string(containsString("Lamentablemente, el 3 es impar...")));
    }

}
