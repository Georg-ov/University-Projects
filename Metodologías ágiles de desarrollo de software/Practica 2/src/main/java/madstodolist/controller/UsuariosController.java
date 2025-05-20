package madstodolist.controller;

import madstodolist.authentication.ManagerUserSession;
import madstodolist.controller.exception.EquipoServiceException;
import madstodolist.dto.EquipoData;
import madstodolist.dto.TareaData;
import madstodolist.dto.UsuarioData;
import madstodolist.model.Usuario;
import madstodolist.service.EquipoService;
import madstodolist.service.UsuarioService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.GetMapping;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PathVariable;
import javax.servlet.http.HttpSession;

import javax.servlet.http.HttpSession;
import java.util.List;

@Controller
public class UsuariosController {

    @Autowired
    private ManagerUserSession managerUserSession;

    @Autowired
    UsuarioService usuarioService;
    @Autowired
    private EquipoService equipoService;

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

    @GetMapping("/registrados")
    public String listadoUsuarios(Model model, HttpSession session) {
        // Comprobar si el idUsuarioActual está presente en la sesión
        Long idUsuarioActual = (Long) session.getAttribute("idUsuarioLogeado");
        if (idUsuarioActual == null) {
            return "redirect:/login";  // Redirige al login si el usuario no está en sesión
        }

        // Comprobar si es admin
        if (!usuarioService.esAdmin(idUsuarioActual)) {
            return "accesoDenegado";
        }

        // Obtener la lista de usuarios directamente desde el servicio
        List<Usuario> usuarios = usuarioService.findAllUsers();
        model.addAttribute("usuarios", usuarios);
        return "registrados";
    }

    @GetMapping("/registrados/{id}")
    public String verDetallesUsuario(@PathVariable(value = "id") Long idUsuario, Model model, HttpSession session) {
        // Comprobar si el idUsuarioActual está presente en la sesión
        Long idUsuarioActual = (Long) session.getAttribute("idUsuarioLogeado");
        if (idUsuarioActual == null) {
            return "redirect:/login";  // Redirige al login si el usuario no está en sesión
        }

        // Comprobar si es admin
        if (!usuarioService.esAdmin(idUsuarioActual)) {
            return "accesoDenegado";
        }

        // Obtenemos los detalles del usuario
        UsuarioData usuario = usuarioService.findById(idUsuario);
        model.addAttribute("usuario", usuario);
        return "detallesUsuario";
    }

    // Metodo para bloquear un usuario
    @GetMapping("/usuarios/bloquear/{id}")
    public String bloquearUsuario(@PathVariable Long id) {
        usuarioService.bloquearUsuario(id); // Lógica para bloquear al usuario
        return "redirect:/registrados"; // Redirigir de vuelta a la lista de usuarios
    }

    // Metodo para desbloquear un usuario
    @GetMapping("/usuarios/desbloquear/{id}")
    public String desbloquearUsuario(@PathVariable Long id) {
        usuarioService.desbloquearUsuario(id); // Lógica para desbloquear al usuario
        return "redirect:/registrados"; // Redirigir de vuelta a la lista de usuarios
    }

    @GetMapping("/equipos/{id}/usuarios")
    public String listadoUsuariosEquipo(@PathVariable(value="id") Long idEquipo, Model model) {
        EquipoData equipo = equipoService.findById(idEquipo);
        if (equipo == null) {
            throw new EquipoServiceException();
        }
        List<UsuarioData> usuarios = usuarioService.allUsuariosEquipo(idEquipo);
        model.addAttribute("equipo", equipo);
        model.addAttribute("usuario", usuarios);
        return "listaUsuariosEquipo";
    }


}
