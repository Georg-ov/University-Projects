package demoapp.controller;

import demoapp.service.EvenService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;

import javax.validation.Valid;


@Controller
public class EvenControllerPlantilla {

    @Autowired
    private EvenService service;

    // Mostrar el formulario
    @GetMapping("/formPar")
    public String mostrar(NumberData numberData) {
        return "formPar";
    }

    // Procesar el formulario
    @PostMapping("/formPar")
    public String procesarFormulario(@ModelAttribute @Valid NumberData numberData, BindingResult bindingResult, Model model) {
        if(bindingResult.hasErrors()){
            return "formPar";
        }
        model.addAttribute("mensaje", service.even(numberData.getNumero()));
        return "esPar";
    }
}
