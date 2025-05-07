package org.example.www.controlaccesos;

import conexionDB.Conexion;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.sql.Timestamp;
import java.util.Calendar;

public class ControlAccesosSkeleton {

    private Timestamp convertToTimestamp(Calendar calendar) {
        return (calendar != null) ? new Timestamp(calendar.getTimeInMillis()) : null;
    }

    public org.example.www.controlaccesos.RegistrarResponse registrar(org.example.www.controlaccesos.Registrar registrar) {
        System.out.println("Registrando acceso para NIF: " + registrar.getNif());
        org.example.www.controlaccesos.RegistrarResponse response = new org.example.www.controlaccesos.RegistrarResponse();
        boolean exito = registrarAcceso(registrar);
        response.setOut(exito ? "Acceso registrado correctamente" : "Error al registrar el acceso");
        return response;
    }

    public org.example.www.controlaccesos.ConsultarResponse consultar(org.example.www.controlaccesos.Consultar consultar) {
        System.out.println("Consultando accesos para NIF: " + consultar.getNif());
        org.example.www.controlaccesos.ConsultarResponse response = new org.example.www.controlaccesos.ConsultarResponse();
        String resultado = consultarAccesos(consultar);
        response.setOut(resultado != null ? resultado : "No se encontraron registros");
        return response;
    }

    private boolean registrarAcceso(org.example.www.controlaccesos.Registrar registrar) {
        Conexion conexionDB = new Conexion();
        Connection con = conexionDB.conectar();
        if (con == null) return false;

        try {
            int idSala = obtenerIdSala(con, registrar.getCodigosala());
            int idDispositivo = obtenerIdDispositivo(con, registrar.getCodigodispositivo());
            if (idSala == -1 || idDispositivo == -1) return false;

            String sql = "INSERT INTO registroaccesos (idEmpleado, idSala, idDispositivo, fechaHora) " +
                         "VALUES ((SELECT id FROM empleados WHERE nifnie = ?), ?, ?, NOW())";
            try (PreparedStatement pstmt = con.prepareStatement(sql)) {
                pstmt.setString(1, registrar.getNif());
                pstmt.setInt(2, idSala);
                pstmt.setInt(3, idDispositivo);
                return pstmt.executeUpdate() > 0;
            }
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        } finally {
            conexionDB.cerrarConexion();
        }
    }

    private String consultarAccesos(org.example.www.controlaccesos.Consultar consultar) {
        Conexion conexionDB = new Conexion();
        Connection con = conexionDB.conectar();
        if (con == null) return null;

        try {
            int idSala = obtenerIdSala(con, consultar.getCodigosala());
            int idDispositivo = obtenerIdDispositivo(con, consultar.getCodigodispositivo());
            if (idSala == -1 || idDispositivo == -1) return null;

            String sql = "SELECT idEmpleado, idSala, idDispositivo, fechaHora " +
                         "FROM registroaccesos " +
                         "WHERE idEmpleado = (SELECT id FROM empleados WHERE nifnie = ?) " +
                         "AND idSala = ? AND idDispositivo = ? " +
                         "AND fechaHora BETWEEN ? AND ?";
            try (PreparedStatement pstmt = con.prepareStatement(sql)) {
                pstmt.setString(1, consultar.getNif());
                pstmt.setInt(2, idSala);
                pstmt.setInt(3, idDispositivo);
                pstmt.setTimestamp(4, convertToTimestamp(consultar.getFechaA()));
                pstmt.setTimestamp(5, convertToTimestamp(consultar.getFechaB()));

                ResultSet rs = pstmt.executeQuery();
                StringBuilder resultado = new StringBuilder();
                while (rs.next()) {
                    resultado.append("ID: ").append(rs.getInt("idEmpleado"))
                             .append(", Sala: ").append(rs.getInt("idSala"))
                             .append(", Dispositivo: ").append(rs.getInt("idDispositivo"))
                             .append(", FechaHora: ").append(rs.getTimestamp("fechaHora"))
                             .append("\n");
                }
                return resultado.toString().isEmpty() ? null : resultado.toString();
            }
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            conexionDB.cerrarConexion();
        }
        return null;
    }

    private int obtenerIdSala(Connection con, int codigoSala) throws SQLException {
        String query = "SELECT id FROM salas WHERE codigoSala = ?";
        try (PreparedStatement stmt = con.prepareStatement(query)) {
            stmt.setInt(1, codigoSala);
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                return rs.getInt("id");
            }
        }
        return -1;
    }

    private int obtenerIdDispositivo(Connection con, int codigoDispositivo) throws SQLException {
        String query = "SELECT id FROM dispositivo WHERE codigo = ?";
        try (PreparedStatement stmt = con.prepareStatement(query)) {
            stmt.setInt(1, codigoDispositivo);
            ResultSet rs = stmt.executeQuery();
            if (rs.next()) {
                return rs.getInt("id");
            }
        }
        return -1;
    }
}
