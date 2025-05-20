package madstodolist.controller;

import madstodolist.authentication.ManagerUserSession;
import madstodolist.controller.exception.EquipoServiceException;
import madstodolist.controller.exception.TareaNotFoundException;
import madstodolist.controller.exception.UsuarioNoLogeadoException;
import madstodolist.dto.EquipoData;
import madstodolist.dto.TareaData;
import madstodolist.dto.UsuarioData;
import madstodolist.model.Equipo;
import madstodolist.model.Usuario;
import madstodolist.service.EquipoService;
import madstodolist.service.UsuarioService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;
import org.springframework.web.servlet.mvc.support.RedirectAttributes;

import javax.servlet.http.HttpSession;
import java.util.List;

@Controller
public class EquipoController {

    @Autowired
    private ManagerUserSession managerUserSession;

    @Autowired
    UsuarioService usuarioService;

    @Autowired
    EquipoService equipoService;

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

    @GetMapping("/equipos")
    public String listadoEquipos(Model model, HttpSession session) {
        // Obtener los equipos
        List<EquipoData> equipos = equipoService.findAllOrdenadoPorNombre();
        model.addAttribute("equipos", equipos);

        // Suponiendo que el usuario está almacenado en la sesión
        Usuario usuario = (Usuario) session.getAttribute("usuario");
        if (usuario != null) {
            model.addAttribute("usuario", usuario);
        }

        return "listaEquipos";
    }

    @GetMapping("/equipos/nuevo")
    public String formNuevoEquipo(@ModelAttribute EquipoData equipoData,
                                  Model model, HttpSession session) {

        return "formNuevoEquipo";
    }

    @PostMapping("/equipos/nuevo")
    public String nuevoEquipo(@ModelAttribute EquipoData equipoData,
                             Model model, RedirectAttributes flash,
                             HttpSession session) {

        equipoService.crearEquipo(equipoData.getNombre());
        flash.addFlashAttribute("mensaje", "Equipo creado correctamente");
        return "redirect:/equipos";
    }

    @GetMapping("/equipos/{id}/editar")
    public String formEditaEquipo(@PathVariable("id") Long idEquipo, @ModelAttribute EquipoData equipoData,
                                  Model model, HttpSession session) {

        EquipoData equipo = equipoService.findById(idEquipo);
        if (equipo == null) {
            throw new EquipoServiceException();
        }

        model.addAttribute("equipo", equipo);
        equipoData.setNombre(equipo.getNombre());
        return "formEditarEquipo";
    }

    @PostMapping("/equipos/{id}/editar")
    public String grabaEquipoEditado(@PathVariable("id") Long idEquipo, @ModelAttribute EquipoData equipoData,
                                     Model model, RedirectAttributes flash, HttpSession session) {
        EquipoData equipo = equipoService.findById(idEquipo);
        if (equipo == null) {
            throw new EquipoServiceException();
        }

        equipoService.editarEquipo(idEquipo, equipoData.getNombre());
        flash.addFlashAttribute("mensaje", "Equipo editado correctamente");
        return "redirect:/equipos";
    }


    // Metodo para agregar usuario a equipo
    @GetMapping("/equipos/{idE}/usuario/{idU}/agregar")
    public String anyadirUsuario(@PathVariable Long idE, @PathVariable Long idU, Model model) {
        equipoService.anyadirUsuario(idE, idU); // Lógica para bloquear al usuario
        return "redirect:/equipos"; // Redirigir de vuelta a la lista de usuarios
    }

    // Metodo para bloquear un usuario
    @GetMapping("/equipos/{idE}/usuario/{idU}/eliminar")
    public String eliminarUsuario(@PathVariable Long idE, @PathVariable Long idU, Model model) {
        equipoService.eliminarUsuario(idE, idU); // Lógica para bloquear al usuario
        return "redirect:/equipos"; // Redirigir de vuelta a la lista de usuarios
    }

    @DeleteMapping("/equipos/{id}")
    @ResponseBody
    public String borrarEquipo(@PathVariable(value="id") Long idEquipo, RedirectAttributes flash, HttpSession session) {
        EquipoData equipo = equipoService.findById(idEquipo);
        if (equipo == null) {
            throw new EquipoServiceException();
        }
        equipoService.eliminarEquipo(idEquipo);
        return "";
    }

}
