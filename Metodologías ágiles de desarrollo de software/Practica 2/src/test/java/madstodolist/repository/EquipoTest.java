package madstodolist.repository;

import madstodolist.dto.EquipoData;
import madstodolist.dto.UsuarioData;
import madstodolist.model.Equipo;
import madstodolist.model.Usuario;
import madstodolist.service.EquipoService;
import madstodolist.service.UsuarioService;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.test.context.jdbc.Sql;
import org.springframework.transaction.annotation.Transactional;

import java.util.List;

import static org.assertj.core.api.Assertions.assertThat;

@SpringBootTest
@Sql(scripts = "/clean-db.sql")
public class EquipoTest {

    @Autowired
    private EquipoRepository equipoRepository;

    @Autowired
    private EquipoService equipoService;

    @Autowired
    private UsuarioService usuarioService;

    @Autowired
    private UsuarioRepository usuarioRepository;

    @Test
    @Transactional
    public void crearEquipo() {
        // GIVEN
        Equipo equipo = new Equipo("Proyecto P1");

        // THEN
        assertThat(equipo.getNombre()).isEqualTo("Proyecto P1");
    }

    @Test
    @Transactional
    public void grabarYBuscarEquipo() {
        // GIVEN
        Equipo equipo = new Equipo("Proyecto P1");

        // WHEN
        equipoRepository.save(equipo);

        // THEN
        Long equipoId = equipo.getId();
        assertThat(equipoId).isNotNull();
        Equipo equipoDB = equipoRepository.findById(equipoId).orElse(null);
        assertThat(equipoDB).isNotNull();
        assertThat(equipoDB.getNombre()).isEqualTo("Proyecto P1");
    }

    @Test
    @Transactional
    public void comprobarIgualdadEquipos() {
        // GIVEN
        Equipo equipo1 = new Equipo("Proyecto P1");
        Equipo equipo2 = new Equipo("Proyecto P2");
        Equipo equipo3 = new Equipo("Proyecto P2");

        // THEN
        assertThat(equipo1).isNotEqualTo(equipo2);
        assertThat(equipo2).isEqualTo(equipo3);
        assertThat(equipo2.hashCode()).isEqualTo(equipo3.hashCode());

        equipo1.setId(1L);
        equipo2.setId(1L);
        equipo3.setId(2L);

        assertThat(equipo1).isEqualTo(equipo2);
        assertThat(equipo2).isNotEqualTo(equipo3);
    }

    @Test
    @Transactional
    public void comprobarRelacionBaseDatos() {
        // GIVEN
        Equipo equipo = new Equipo("Proyecto 1");
        equipoRepository.save(equipo);

        Usuario usuario = new Usuario("user@ua");
        usuarioRepository.save(usuario);

        // WHEN
        equipo.addUsuario(usuario);

        // THEN
        Equipo equipoBD = equipoRepository.findById(equipo.getId()).orElse(null);
        Usuario usuarioBD = usuarioRepository.findById(usuario.getId()).orElse(null);

        assertThat(equipo.getUsuarios()).hasSize(1);
        assertThat(equipo.getUsuarios()).contains(usuario);
        assertThat(usuario.getEquipos()).hasSize(1);
        assertThat(usuario.getEquipos()).contains(equipo);
    }

    @Test
    @Transactional
    public void comprobarFindAll() {
        // GIVEN
        equipoRepository.save(new Equipo("Proyecto 2"));
        equipoRepository.save(new Equipo("Proyecto 3"));

        // WHEN
        List<Equipo> equipos = equipoRepository.findAll();

        // THEN
        assertThat(equipos).hasSize(2);
    }

    @Test
    @Transactional
    public void listadoEquiposOrdenAlfabetico() {
        // GIVEN
        equipoService.crearEquipo("Proyecto BBB");
        equipoService.crearEquipo("Proyecto AAA");

        // WHEN
        List<EquipoData> equipos = equipoService.findAllOrdenadoPorNombre();

        // THEN
        assertThat(equipos).hasSize(2);
        assertThat(equipos.get(0).getNombre()).isEqualTo("Proyecto AAA");
        assertThat(equipos.get(1).getNombre()).isEqualTo("Proyecto BBB");
    }

    @Test
    @Transactional
    public void unEquipoTieneUnaListaDeUsuarios() {
        // GIVEN
        Equipo equipo = new Equipo("Proyecto 1");
        equipoRepository.save(equipo);

        Usuario usuario1 = new Usuario("usuario1@ua");
        Usuario usuario2 = new Usuario("usuario2@ua");
        usuarioRepository.save(usuario1);
        usuarioRepository.save(usuario2);

        // Asociamos los usuarios al equipo
        equipo.getUsuarios().add(usuario1);
        equipo.getUsuarios().add(usuario2);

        // Guardamos nuevamente el equipo
        equipoRepository.save(equipo);

        Long idEquipo = equipo.getId();

        // WHEN
        List<UsuarioData> usuarios = usuarioService.allUsuariosEquipo(idEquipo);

        // THEN
        assertThat(usuarios).hasSize(2);
        assertThat(usuarios.get(0).getEmail()).isEqualTo("usuario1@ua");
        assertThat(usuarios.get(1).getEmail()).isEqualTo("usuario2@ua");
    }
}
