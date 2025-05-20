package demoapp;

import demoapp.service.EvenService;
import demoapp.service.SaludoService;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.context.SpringBootTest;
import static org.assertj.core.api.Assertions.assertThat;

@SpringBootTest
public class ServiceTest {

    @Autowired
    SaludoService saludo;
    @Autowired
    EvenService evenService;

    @Test
    public void contexLoads() throws Exception {
        assertThat(saludo).isNotNull();
    }

    @Test
    public void serviceSaludo() throws Exception {
        assertThat(saludo.saluda("Domingo")).isEqualTo("Hola Domingo");
    }

    @Test
    public void serviceEvenPar() throws Exception{
        assertThat(evenService.even(2)).isEqualTo("Enhorabuena, el 2 es par!!!");
    }

    @Test
    public void serviceEvenImpar() throws Exception{
        assertThat(evenService.even(3)).isEqualTo("Lamentablemente, el 3 es impar...");
    }
}
