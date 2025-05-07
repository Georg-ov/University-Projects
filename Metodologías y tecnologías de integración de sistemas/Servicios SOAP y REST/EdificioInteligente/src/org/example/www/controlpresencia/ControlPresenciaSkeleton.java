package org.example.www.controlpresencia;

import conexionDB.Conexion;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

public class ControlPresenciaSkeleton {

    public org.example.www.controlpresencia.RegistrarResponse registrar(org.example.www.controlpresencia.Registrar registrar) {
        System.out.println("Registrando presencia para NIF: " + registrar.getNif());
        org.example.www.controlpresencia.RegistrarResponse response = new org.example.www.controlpresencia.RegistrarResponse();
        boolean exito = registrarPresencia(registrar);
        response.setOut(exito ? "Presencia registrada correctamente" : "Error al registrar la presencia");
        return response;
    }

    public org.example.www.controlpresencia.EliminarResponse eliminar(org.example.www.controlpresencia.Eliminar eliminar) {
        System.out.println("Eliminando presencia para NIF: " + eliminar.getNif());
        org.example.www.controlpresencia.EliminarResponse response = new org.example.www.controlpresencia.EliminarResponse();
        boolean exito = eliminarPresencia(eliminar);
        response.setOut(exito ? "Presencia eliminada correctamente" : "Error al eliminar la presencia");
        return response;
    }

    public org.example.www.controlpresencia.ControlEmpleadosSalaResponse controlEmpleadosSala(org.example.www.controlpresencia.ControlEmpleadosSala controlEmpleadosSala) {
        System.out.println("Consultando empleados en la sala: " + controlEmpleadosSala.getIn());
        org.example.www.controlpresencia.ControlEmpleadosSalaResponse response = new org.example.www.controlpresencia.ControlEmpleadosSalaResponse();
        String resultado = consultarEmpleadosEnSala(controlEmpleadosSala.getIn());
        response.setOut(resultado != null ? resultado : "No se encontraron empleados en la sala");
        return response;
    }

    private boolean registrarPresencia(org.example.www.controlpresencia.Registrar registrar) {
        Conexion conexionDB = new Conexion();
        Connection con = conexionDB.conectar();
        if (con == null) return false;

        int idSala = obtenerIdSala(registrar.getCodigosala(), con);
        if (idSala == -1) {
            System.out.println("Error: La sala con código " + registrar.getCodigosala() + " no existe.");
            return false;
        }

        String sql = "INSERT INTO controlpresencia (idEmpleado, idSala) VALUES ((SELECT id FROM empleados WHERE nifnie = ?), ?)";
        try (PreparedStatement pstmt = con.prepareStatement(sql)) {
            pstmt.setString(1, registrar.getNif());
            pstmt.setInt(2, idSala);
            return pstmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        } finally {
            conexionDB.cerrarConexion();
        }
    }

    private boolean eliminarPresencia(org.example.www.controlpresencia.Eliminar eliminar) {
        Conexion conexionDB = new Conexion();
        Connection con = conexionDB.conectar();
        if (con == null) return false;

        int idSala = obtenerIdSala(eliminar.getCodigosala(), con);
        if (idSala == -1) {
            System.out.println("Error: La sala con código " + eliminar.getCodigosala() + " no existe.");
            return false;
        }

        String sql = "DELETE FROM controlpresencia WHERE idEmpleado = (SELECT id FROM empleados WHERE nifnie = ?) AND idSala = ?";
        try (PreparedStatement pstmt = con.prepareStatement(sql)) {
            pstmt.setString(1, eliminar.getNif());
            pstmt.setInt(2, idSala);
            return pstmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        } finally {
            conexionDB.cerrarConexion();
        }
    }

    private String consultarEmpleadosEnSala(int codigosala) {
        Conexion conexionDB = new Conexion();
        Connection con = conexionDB.conectar();
        if (con == null) return null;

        int idSala = obtenerIdSala(codigosala, con);
        if (idSala == -1) {
            return "No se encontraron empleados en la sala porque la sala no existe.";
        }

        String sql = "SELECT e.nifnie FROM controlpresencia cp JOIN empleados e ON cp.idEmpleado = e.id WHERE cp.idSala = ?";
        try (PreparedStatement pstmt = con.prepareStatement(sql)) {
            pstmt.setInt(1, idSala);
            ResultSet rs = pstmt.executeQuery();
            StringBuilder resultado = new StringBuilder();
            while (rs.next()) {
                resultado.append("Empleado NIF: ").append(rs.getString("nifnie")).append("\n");
            }
            return resultado.toString().isEmpty() ? null : resultado.toString();
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            conexionDB.cerrarConexion();
        }
        return null;
    }

    private int obtenerIdSala(int codigosala, Connection con) {
        String sql = "SELECT id FROM salas WHERE codigoSala = ?";
        try (PreparedStatement pstmt = con.prepareStatement(sql)) {
            pstmt.setInt(1, codigosala);
            ResultSet rs = pstmt.executeQuery();
            if (rs.next()) {
                return rs.getInt("id");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
        return -1;
    }
}

