package demoapp.controller;

import demoapp.service.SaludoService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.validation.BindingResult;
import org.springframework.web.bind.annotation.*;

import javax.validation.Valid;

@Controller
public class SaludoControllerForm {

    @Autowired
    private SaludoService service;

    @GetMapping("/saludoform")
    // Hay que declarar un parámetro con el tipo usado en el modelo del formulario (UserData)
    public String saludoForm(UserData userData) {
        return "formRegistro";
    }

    @PostMapping("/saludoform")
    public String checkPersonInfo(@ModelAttribute @Valid UserData userData, BindingResult bindingResult, Model model) {
        if (bindingResult.hasErrors()) {
            return "formRegistro";
        }
        model.addAttribute("mensaje", service.saluda(userData.getNombre()));
        return "saludo";
    }
}
