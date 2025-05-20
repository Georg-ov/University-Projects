package madstodolist.model;

import javax.persistence.*;
import java.io.Serializable;
import java.util.Objects;
import java.util.Set;
import java.util.HashSet;

@Entity
@Table(name = "equipos")
public class Equipo implements Serializable{

    private String nombre;

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @ManyToMany(fetch = FetchType.LAZY)
    @JoinTable(name = "equipo_usuario",
            joinColumns = { @JoinColumn(name = "fk_equipo") },
            inverseJoinColumns = {@JoinColumn(name = "fk_usuario")})
    Set<Usuario> usuarios = new HashSet<>();

    public Set<Usuario> getUsuarios() {
        return usuarios;
    }

    public void addUsuario(Usuario usuario) {
        // Hay que actualiar ambas colecciones, porque
        // JPA/Hibernate no lo hace automáticamente
        this.getUsuarios().add(usuario);
        usuario.getEquipos().add(this);
    }

    public void deleteUsuario(Usuario usuario) {
        this.getUsuarios().remove(usuario);
        usuario.getEquipos().remove(this);
    }

    public Equipo(String nombre) {
        this.nombre = nombre;
    }

    public Equipo() {this.nombre = "default";}

    public String getNombre() {return nombre;}

    public void setNombre(String nombre) {this.nombre = nombre;}

    public Long getId() {return id;}

    public void setId(Long id) {this.id = id;}

    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (!(o instanceof Equipo)) return false;
        Equipo equipo = (Equipo) o;

        // Si ambos tienen ID, compara por ID
        if (id != null && equipo.id != null) {
            return Objects.equals(id, equipo.id);
        }

        // Si no hay ID, compara por nombre
        return Objects.equals(nombre, equipo.nombre);
    }

    @Override
    public int hashCode() {
        // Si hay ID, usa el ID en el hashCode; si no, usa el nombre
        return id != null ? Objects.hash(id) : Objects.hash(nombre);
    }
}
