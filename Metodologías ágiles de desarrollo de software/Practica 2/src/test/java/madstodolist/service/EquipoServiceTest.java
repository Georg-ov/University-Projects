package madstodolist.service;

import madstodolist.controller.exception.EquipoServiceException;
import madstodolist.dto.TareaData;
import madstodolist.dto.UsuarioData;
import madstodolist.model.Equipo;
import madstodolist.model.Usuario;
import madstodolist.repository.EquipoRepository;
import madstodolist.repository.UsuarioRepository;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.test.annotation.Rollback;
import org.springframework.test.context.jdbc.Sql;
import madstodolist.dto.EquipoData;

import static org.assertj.core.api.Assertions.assertThat;
import static org.assertj.core.api.AssertionsForClassTypes.assertThatThrownBy;
import static org.hamcrest.Matchers.any;
import static org.junit.jupiter.api.Assertions.assertEquals;
import static org.junit.jupiter.api.Assertions.assertNotNull;
import static org.mockito.Mockito.when;

import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;

import javax.transaction.Transactional;
import java.util.List;
import java.util.Map;
import java.util.Optional;

@SpringBootTest
@Sql(scripts = "/clean-db.sql")
public class EquipoServiceTest {

    @Autowired
    EquipoService equipoService;

    @Autowired
    UsuarioService usuarioService;

    @Autowired
    private EquipoRepository equipoRepository;

    @Autowired
    private UsuarioRepository usuarioRepository;


    @Test
    public void crearRecuperarEquipo() {
        EquipoData equipo = equipoService.crearEquipo("Proyecto 1");
        assertThat(equipo.getId()).isNotNull();

        EquipoData equipoBd = equipoService.recuperarEquipo(equipo.getId());
        assertThat(equipoBd).isNotNull();
        assertThat(equipoBd.getNombre()).isEqualTo("Proyecto 1");
    }

    @Test
    public void añadirUsuarioAEquipo() {
        // GIVEN
        // Un usuario y un equipo en la base de datos
        UsuarioData usuario = new UsuarioData();
        usuario.setEmail("user@ua");
        usuario.setPassword("123");
        usuario = usuarioService.registrar(usuario);
        EquipoData equipo = equipoService.crearEquipo("Proyecto 1");

        // WHEN
        // Añadimos el usuario al equipo
        equipoService.añadirUsuarioAEquipo(equipo.getId(), usuario.getId());

        // THEN
        // El usuario pertenece al equipo
        List<UsuarioData> usuarios = equipoService.usuariosEquipo(equipo.getId());
        assertThat(usuarios).hasSize(1);
        assertThat(usuarios.get(0).getEmail()).isEqualTo("user@ua");
    }

    @Test
    public void recuperarEquiposDeUsuario() {
        // GIVEN
        // Un usuario y dos equipos en la base de datos
        UsuarioData usuario = new UsuarioData();
        usuario.setEmail("user@ua");
        usuario.setPassword("123");
        usuario = usuarioService.registrar(usuario);
        EquipoData equipo1 = equipoService.crearEquipo("Proyecto 1");
        EquipoData equipo2 = equipoService.crearEquipo("Proyecto 2");
        equipoService.añadirUsuarioAEquipo(equipo1.getId(), usuario.getId());
        equipoService.añadirUsuarioAEquipo(equipo2.getId(), usuario.getId());

        // WHEN
        // Recuperamos los equipos del usuario
        List<EquipoData> equipos = equipoService.equiposUsuario(usuario.getId());

        // THEN
        // El usuario pertenece a los dos equipos
        assertThat(equipos).hasSize(2);
        assertThat(equipos.get(0).getNombre()).isEqualTo("Proyecto 1");
        assertThat(equipos.get(1).getNombre()).isEqualTo("Proyecto 2");
    }

    @Test
    public void comprobarExcepciones() {
        // Comprobamos las excepciones lanzadas por los métodos
        // recuperarEquipo, añadirUsuarioAEquipo, usuariosEquipo y equiposUsuario
        assertThatThrownBy(() -> equipoService.recuperarEquipo(1L))
                .isInstanceOf(EquipoServiceException.class);
        assertThatThrownBy(() -> equipoService.añadirUsuarioAEquipo(1L, 1L))
                .isInstanceOf(EquipoServiceException.class);
        assertThatThrownBy(() -> equipoService.usuariosEquipo(1L))
                .isInstanceOf(EquipoServiceException.class);
        assertThatThrownBy(() -> equipoService.equiposUsuario(1L))
                .isInstanceOf(EquipoServiceException.class);

        // Creamos un equipo pero no un usuario y comprobamos que también se lanza una excepción
        EquipoData equipo = equipoService.crearEquipo("Proyecto 1");
        assertThatThrownBy(() -> equipoService.añadirUsuarioAEquipo(equipo.getId(), 1L))
                .isInstanceOf(EquipoServiceException.class);
    }

    @Test
    public void añadirUsuarioAEquipo_Success() {
        // GIVEN: Crear un usuario y un equipo
        UsuarioData usuario = new UsuarioData();
        usuario.setEmail("user@ua");
        usuario.setPassword("123");
        usuario = usuarioService.registrar(usuario);
        EquipoData equipo = equipoService.crearEquipo("Proyecto 1");

        // WHEN: Añadir usuario al equipo
        equipoService.anyadirUsuario(equipo.getId(), usuario.getId());

        // THEN: Verificar que el usuario está en el equipo
        List<UsuarioData> usuarios = equipoService.usuariosEquipo(equipo.getId());
        assertThat(usuarios).hasSize(1);
        assertThat(usuarios.get(0).getEmail()).isEqualTo("user@ua");
    }

    @Test
    public void eliminarUsuarioDeEquipo_Success() {
        // GIVEN: Crear un usuario y un equipo, luego añadir el usuario al equipo
        UsuarioData usuario = new UsuarioData();
        usuario.setEmail("user@ua");
        usuario.setPassword("123");
        usuario = usuarioService.registrar(usuario);
        EquipoData equipo = equipoService.crearEquipo("Proyecto 1");
        equipoService.añadirUsuarioAEquipo(equipo.getId(), usuario.getId());

        // WHEN: Eliminar el usuario del equipo
        equipoService.eliminarUsuario(equipo.getId(), usuario.getId());

        // THEN: Verificar que el usuario ha sido eliminado
        List<UsuarioData> usuarios = equipoService.usuariosEquipo(equipo.getId());
        assertThat(usuarios).isEmpty();
    }

    @Test
    public void editarEquipo() throws Exception {
        // Preparar los datos de prueba
        String nuevoNombre = "Nuevo Nombre";

        // Crear y guardar un nuevo equipo (sin asignar un ID fijo)
        Equipo equipo = new Equipo("Nombre Original");
        equipoRepository.save(equipo);  // Aquí el ID será generado automáticamente
        Long equipoId = equipo.getId();  // Obtener el ID generado

        // Verificar que el equipo se guardó correctamente
        Equipo equipoGuardado = equipoRepository.findById(equipoId).orElse(null);
        assertThat(equipoGuardado).isNotNull();

        // Ejecutar el servicio para editar el equipo
        equipoService.editarEquipo(equipoId, nuevoNombre);

        // Verificar que el nombre ha sido actualizado
        Equipo equipoActualizado = equipoRepository.findById(equipoId).orElse(null);
        assertThat(equipoActualizado).isNotNull();
        assertThat(equipoActualizado.getNombre()).isEqualTo(nuevoNombre);
    }

    @Test
    public void testEliminarEquipo() throws Exception {

        // Crear y guardar un nuevo equipo (sin asignar un ID fijo)
        Equipo equipo = new Equipo("Nombre Original");
        equipoRepository.save(equipo);  // Aquí el ID será generado automáticamente
        Long equipoId = equipo.getId();  // Obtener el ID generado

        equipoService.eliminarEquipo(equipoId);

        assertThat(equipoService.findById(equipoId)).isNull();

        List<EquipoData> equipos = equipoService.findAllOrdenadoPorNombre();
        assertThat(equipos).hasSize(0);
    }


}