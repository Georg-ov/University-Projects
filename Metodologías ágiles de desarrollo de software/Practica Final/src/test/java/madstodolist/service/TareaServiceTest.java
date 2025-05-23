package madstodolist.service;

import madstodolist.dto.SubtareaData;
import madstodolist.dto.TareaData;
import madstodolist.dto.UsuarioData;
import org.junit.jupiter.api.Test;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.boot.test.context.SpringBootTest;
import org.springframework.test.context.jdbc.Sql;

import java.util.HashMap;
import java.util.List;
import java.util.Map;

import static org.assertj.core.api.Assertions.assertThat;

// Hemos eliminado todos los @Transactional de los tests
// y usado un script para limpiar la BD de test después de
// cada test
// https://dev.to/henrykeys/don-t-use-transactional-in-tests-40eb

@SpringBootTest
@Sql(scripts = "/clean-db.sql")
public class TareaServiceTest {

    @Autowired
    UsuarioService usuarioService;

    @Autowired
    TareaService tareaService;

    // Método para inicializar los datos de prueba en la BD
    // Devuelve un mapa con los identificadores del usuario y de la primera tarea añadida
    Map<String, Long> addUsuarioTareasBD() {
        UsuarioData usuario = new UsuarioData();
        usuario.setEmail("user@ua");
        usuario.setPassword("123");

        // Añadimos un usuario a la base de datos
        UsuarioData usuarioNuevo = usuarioService.registrar(usuario);

        // Y añadimos dos tareas asociadas a ese usuario
        TareaData tarea1 = tareaService.nuevaTareaUsuario(usuarioNuevo.getId(), "Lavar coche");
        tareaService.nuevaTareaUsuario(usuarioNuevo.getId(), "Renovar DNI");

        // Devolvemos los ids del usuario y de la primera tarea añadida
        Map<String, Long> ids = new HashMap<>();
        ids.put("usuarioId", usuarioNuevo.getId());
        ids.put("tareaId", tarea1.getId());
        return ids;
    }

    @Test
    public void testNuevaTareaUsuario() {
        // GIVEN
        // Un usuario en la BD

        Long usuarioId = addUsuarioTareasBD().get("usuarioId");

        // WHEN
        // creamos una nueva tarea asociada al usuario,
        TareaData nuevaTarea = tareaService.nuevaTareaUsuario(usuarioId, "Práctica 1 de MADS");

        // THEN
        // al recuperar la lista de tareas del usuario, la nueva tarea
        // está en la lista de tareas del usuario.

        List<TareaData> tareas = tareaService.allTareasUsuario(usuarioId);

        assertThat(tareas).hasSize(3);
        assertThat(tareas).contains(nuevaTarea);
    }

    @Test
    public void testBuscarTarea() {
        // GIVEN
        // Una tarea en la BD

        Long tareaId = addUsuarioTareasBD().get("tareaId");

        // WHEN
        // recuperamos una tarea de la base de datos a partir de su ID,

        TareaData lavarCoche = tareaService.findById(tareaId);

        // THEN
        // los datos de la tarea recuperada son correctos.

        assertThat(lavarCoche).isNotNull();
        assertThat(lavarCoche.getTitulo()).isEqualTo("Lavar coche");
    }

    @Test
    public void testModificarTarea() {
        // GIVEN
        // Un usuario y una tarea en la BD

        Map<String, Long> ids = addUsuarioTareasBD();
        Long usuarioId = ids.get("usuarioId");
        Long tareaId = ids.get("tareaId");

        // WHEN
        // modificamos la tarea correspondiente al identificador,

        tareaService.modificaTarea(tareaId, "Limpiar los cristales del coche");

        // THEN
        // al buscar por el identificador en la base de datos se devuelve la tarea modificada

        TareaData tareaBD = tareaService.findById(tareaId);
        assertThat(tareaBD.getTitulo()).isEqualTo("Limpiar los cristales del coche");

        // y el usuario tiene también esa tarea modificada.
        List<TareaData> tareas = tareaService.allTareasUsuario(usuarioId);
        assertThat(tareas).contains(tareaBD);
    }

    @Test
    public void testBorrarTarea() {
        // GIVEN
        // Un usuario y una tarea en la BD

        Map<String, Long> ids = addUsuarioTareasBD();
        Long usuarioId = ids.get("usuarioId");
        Long tareaId = ids.get("tareaId");

        // WHEN
        // borramos la tarea correspondiente al identificador,

        tareaService.borraTarea(tareaId);

        // THEN
        // la tarea ya no está en la base de datos ni en las tareas del usuario.

        assertThat(tareaService.findById(tareaId)).isNull();

        List<TareaData> tareas = tareaService.allTareasUsuario(usuarioId);
        assertThat(tareas).hasSize(1);
    }

    @Test
    public void asignarEtiquetaATarea(){

        Map<String, Long> ids = addUsuarioTareasBD();
        Long usuarioId = ids.get("usuarioId");
        Long tareaId = ids.get("tareaId");

        assertThat(tareaService.usuarioContieneTarea(usuarioId,tareaId)).isTrue();
    }

    @Test
    public void testDestacarTarea() {
        // GIVEN
        // Un usuario y una tarea en la BD
        Map<String, Long> ids = addUsuarioTareasBD();
        Long tareaId = ids.get("tareaId");

        // WHEN
        // destacamos la tarea
        tareaService.destacarTarea(tareaId, true);

        // THEN
        // la tarea está marcada como destacada
        TareaData tareaDestacada = tareaService.findById(tareaId);
        assertThat(tareaDestacada).isNotNull();
        assertThat(tareaDestacada.isDestacado()).isTrue();
    }

    @Test
    public void testQuitarDestacadoDeTarea() {
        // GIVEN
        // Un usuario y una tarea destacada en la BD
        Map<String, Long> ids = addUsuarioTareasBD();
        Long tareaId = ids.get("tareaId");
        tareaService.destacarTarea(tareaId, true);

        // WHEN
        // quitamos el estado de destacado de la tarea
        tareaService.destacarTarea(tareaId, false);

        // THEN
        // la tarea ya no está marcada como destacada
        TareaData tareaNoDestacada = tareaService.findById(tareaId);
        assertThat(tareaNoDestacada).isNotNull();
        assertThat(tareaNoDestacada.isDestacado()).isFalse();
    }

    @Test
    public void testCrearSubtarea() {
        // GIVEN
        Map<String, Long> ids = addUsuarioTareasBD();
        Long tareaId = ids.get("tareaId");

        // WHEN
        SubtareaData subtarea = tareaService.crearSubtarea(tareaId, "Lavar llantas");

        // THEN
        assertThat(subtarea).isNotNull();
        assertThat(subtarea.getNombre()).isEqualTo("Lavar llantas");

        List<SubtareaData> subtareas = tareaService.allSubtareasTarea(tareaId);
        assertThat(subtareas).hasSize(1);
        assertThat(subtareas).contains(subtarea);
    }

    @Test
    public void testEliminarSubtarea() {
        // GIVEN
        Map<String, Long> ids = addUsuarioTareasBD();
        Long tareaId = ids.get("tareaId");

        SubtareaData subtarea = tareaService.crearSubtarea(tareaId, "Lavar llantas");

        // WHEN
        tareaService.eliminarSubtarea(subtarea.getId());

        // THEN
        List<SubtareaData> subtareas = tareaService.allSubtareasTarea(tareaId);
        assertThat(subtareas).isEmpty();
    }

    @Test
    public void testListarSubtareasDeTarea() {
        // GIVEN
        Map<String, Long> ids = addUsuarioTareasBD();
        Long tareaId = ids.get("tareaId");

        tareaService.crearSubtarea(tareaId, "Lavar llantas");
        tareaService.crearSubtarea(tareaId, "Aspirar asientos");

        // WHEN
        List<SubtareaData> subtareas = tareaService.allSubtareasTarea(tareaId);

        // THEN
        assertThat(subtareas).hasSize(2);
        assertThat(subtareas).extracting("nombre")
                .containsExactlyInAnyOrder("Lavar llantas", "Aspirar asientos");
    }


}
