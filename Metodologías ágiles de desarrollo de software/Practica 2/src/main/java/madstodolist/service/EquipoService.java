package madstodolist.service;

import madstodolist.controller.exception.EquipoServiceException;
import madstodolist.dto.EquipoData;
import madstodolist.dto.TareaData;
import madstodolist.dto.UsuarioData;
import madstodolist.model.Equipo;
import madstodolist.model.Tarea;
import madstodolist.model.Usuario;
import madstodolist.repository.EquipoRepository;
import madstodolist.repository.UsuarioRepository;
import org.modelmapper.ModelMapper;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;
import org.springframework.transaction.annotation.Transactional;

import java.util.Collections;
import java.util.List;
import java.util.stream.Collectors;

@Service
public class EquipoService {

    @Autowired
    private EquipoRepository equipoRepository;
    @Autowired
    private ModelMapper modelMapper;
    @Autowired
    private UsuarioRepository usuarioRepository;

    @Transactional(readOnly = true)
    public EquipoData findById(Long equipoId) {
        Equipo equipo = equipoRepository.findById(equipoId).orElse(null);
        if (equipo == null) return null;
        else {
            return modelMapper.map(equipo, EquipoData.class);
        }
    }

    @Transactional
    public EquipoData crearEquipo(String nombre) {

        Equipo equipo = new Equipo(nombre);
        equipoRepository.save(equipo);

        return modelMapper.map(equipo, EquipoData.class);
    }

    @Transactional(readOnly = true)
    public EquipoData recuperarEquipo(Long id) {
        Equipo equipo = equipoRepository.findById(id)
                .orElseThrow(EquipoServiceException::new);
        return modelMapper.map(equipo, EquipoData.class);
    }


    @Transactional(readOnly = true)
    public List<EquipoData> findAllOrdenadoPorNombre() {
        return equipoRepository.findAllByOrderByNombreAsc().stream()
                .map(equipo -> modelMapper.map(equipo, EquipoData.class))
                .collect(Collectors.toList());
    }

    @Transactional
    public void añadirUsuarioAEquipo(Long equipoId, Long usuarioId) {
        Equipo equipo = equipoRepository.findById(equipoId)
                .orElseThrow(EquipoServiceException::new);
        Usuario usuario = usuarioRepository.findById(usuarioId)
                .orElseThrow(EquipoServiceException::new);
        equipo.addUsuario(usuario);
    }


    @Transactional(readOnly = true)
    public List<UsuarioData> usuariosEquipo(Long equipoId) {
        Equipo equipo = equipoRepository.findById(equipoId)
                .orElseThrow(EquipoServiceException::new);

        return equipo.getUsuarios().stream()
                .map(usuario -> modelMapper.map(usuario, UsuarioData.class))
                .collect(Collectors.toList());
    }

    @Transactional(readOnly = true)
    public List<EquipoData> equiposUsuario(Long usuarioId) {
        Usuario usuario = usuarioRepository.findById(usuarioId)
                .orElseThrow(EquipoServiceException::new);

        return usuario.getEquipos().stream()
                .map(equipo -> modelMapper.map(equipo, EquipoData.class))
                .collect(Collectors.toList());
    }

    @Transactional
    public void anyadirUsuario(Long idEquipo, Long idUsuario) {
        Usuario usuario = usuarioRepository.findById(idUsuario).orElseThrow(() -> new RuntimeException("Usuario no encontrado"));
        Equipo equipo = equipoRepository.findById(idEquipo).orElseThrow(() -> new RuntimeException("Equipo no encontrado"));

        // Verifica si el usuario ya pertenece al equipo
        if (equipo.getUsuarios().contains(usuario)) {
            throw new IllegalArgumentException("El usuario ya pertenece al equipo.");
        }

        // Relación bidireccional
        equipo.addUsuario(usuario);

        // Persistencia
        equipoRepository.save(equipo);
    }

    @Transactional
    public void eliminarUsuario(Long idEquipo, Long idUsuario) {
        Usuario usuario = usuarioRepository.findById(idUsuario).orElseThrow(() -> new RuntimeException("Usuario no encontrado"));
        Equipo equipo = equipoRepository.findById(idEquipo).orElseThrow(() -> new RuntimeException("Equipo no encontrado"));

        // Verifica si el usuario pertenece al equipo
        if (!equipo.getUsuarios().contains(usuario)) {
            throw new IllegalArgumentException("El usuario no pertenece al equipo.");
        }

        // Relación bidireccional
        equipo.deleteUsuario(usuario);

        // Persistencia
        equipoRepository.save(equipo);
    }

    @Transactional
    public EquipoData editarEquipo(Long idEquipo, String nuevoNombre) {
        Equipo equipo = equipoRepository.findById(idEquipo).orElse(null);
        if (equipo == null) {
            throw new EquipoServiceException();
        }
        equipo.setNombre(nuevoNombre);
        equipo = equipoRepository.save(equipo);
        return modelMapper.map(equipo, EquipoData.class);
    }

    @Transactional
    public void eliminarEquipo(Long idEquipo) {

        Equipo equipo = equipoRepository.findById(idEquipo).orElse(null);
        if (equipo == null) {
            throw new EquipoServiceException();
        }

        equipoRepository.delete(equipo);
    }

}
