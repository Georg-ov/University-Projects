package demoapp.service;

import org.springframework.stereotype.Service;

@Service
public class EvenService {
    public String even(Integer numero)
    {
        if(numero % 2 == 0)
            return "Enhorabuena, el " + numero + " es par!!!";
        else
            return "Lamentablemente, el " + numero + " es impar...";
    }
}