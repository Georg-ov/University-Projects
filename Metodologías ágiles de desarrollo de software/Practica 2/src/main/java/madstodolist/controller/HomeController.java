package madstodolist.controller;

import madstodolist.authentication.ManagerUserSession;
import madstodolist.dto.UsuarioData;
import madstodolist.service.UsuarioService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;

@Controller
public class HomeController {

    @Autowired
    private ManagerUserSession managerUserSession;

    @Autowired
    UsuarioService usuarioService;

    @ModelAttribute
    public void addAttributes(Model model) {
        boolean isUsuarioLogueado = managerUserSession.isUsuarioLogeado();
        long usuarioId = isUsuarioLogueado ? managerUserSession.usuarioLogeado() : 0L;

        model.addAttribute("usuarioLogueado", isUsuarioLogueado);
        model.addAttribute("usuarioId", usuarioId);

        // Agregar el objeto usuario completo al modelo si está logueado
        if (isUsuarioLogueado) {
            UsuarioData usuario = usuarioService.findById(usuarioId);
            model.addAttribute("usuario", usuario);
        } else {
            model.addAttribute("usuario", null); // Asegúrate de que usuario esté presente
        }
    }

    @GetMapping("/about")
    public String about(Model model) {
        return "about";
    }

}
