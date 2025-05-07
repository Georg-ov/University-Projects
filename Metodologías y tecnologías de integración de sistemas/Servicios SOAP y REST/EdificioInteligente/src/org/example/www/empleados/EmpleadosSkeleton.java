
/**
 * EmpleadosSkeleton.java
 *
 * This file was auto-generated from WSDL
 * by the Apache Axis2 version: 1.6.3  Built on : Jun 27, 2015 (11:17:49 BST)
 */
package org.example.www.empleados;


import conexionDB.Conexion;
import java.sql.Connection;
import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;

import org.example.www.validaciones.ValidacionesSkeleton;

public class EmpleadosSkeleton {

    public org.example.www.empleados.NuevoResponse nuevo(org.example.www.empleados.Nuevo nuevo) {
        System.out.println("Registrando empleado: " + nuevo.getIn().getNifnie());
        org.example.www.empleados.NuevoResponse response = new org.example.www.empleados.NuevoResponse();
        boolean exito = registrarEmpleado(nuevo);
        response.setOut(exito ? "Empleado registrado correctamente" : "Error al registrar el empleado");
        return response;
    }

    public org.example.www.empleados.BorrarResponse borrar(org.example.www.empleados.Borrar borrar) {
    	System.out.println("Solicitud SOAP recibida para eliminar empleado con NIF: " + borrar.getIn());
        System.out.println("Eliminando empleado: " + borrar.getIn());
        org.example.www.empleados.BorrarResponse response = new org.example.www.empleados.BorrarResponse();
        boolean exito = eliminarEmpleado(borrar);
        response.setOut(exito ? "Empleado eliminado correctamente" : "Error al eliminar el empleado");
        return response;
    }

    public org.example.www.empleados.ConsultarResponse consultar(org.example.www.empleados.Consultar consultar) {
        System.out.println("Consultando empleado: " + consultar.getIn());
        org.example.www.empleados.ConsultarResponse response = new org.example.www.empleados.ConsultarResponse();
        String datos = obtenerEmpleado(consultar);
        response.setOut(datos != null ? datos : "Empleado no encontrado");
        return response;
    }

    public org.example.www.empleados.ModificarResponse modificar(org.example.www.empleados.Modificar modificar) {
        System.out.println("Modificando empleado: " + modificar.getIn().getNifnie());
        org.example.www.empleados.ModificarResponse response = new org.example.www.empleados.ModificarResponse();
        boolean exito = actualizarEmpleado(modificar);
        response.setOut(exito ? "Empleado modificado correctamente" : "Error al modificar el empleado");
        return response;
    }

    private boolean registrarEmpleado(org.example.www.empleados.Nuevo nuevo) {
        if (nuevo == null || nuevo.getIn() == null) {
            System.out.println("Error: Datos de empleado no válidos.");
            return false;
        }

        // Instancia de la clase ValidacionesSkeleton
        ValidacionesSkeleton validaciones = new ValidacionesSkeleton();

        // Validación de los campos
        String nifnie = nuevo.getIn().getNifnie();
        String naf = nuevo.getIn().getNaf();
        String iban = nuevo.getIn().getIban();

        // Validar el NIF/NIE
        org.example.www.validaciones.ValidarNIF validarNIF = new org.example.www.validaciones.ValidarNIF();
        validarNIF.setIn(nifnie);
        org.example.www.validaciones.ValidarNIFResponse responseNIF = validaciones.validarNIF(validarNIF);
        
        org.example.www.validaciones.ValidarNIE validarNIE = new org.example.www.validaciones.ValidarNIE();
        validarNIE.setIn(nifnie);
        org.example.www.validaciones.ValidarNIEResponse responseNIE = validaciones.validarNIE(validarNIE);
        
        // Verificar si el NIF o NIE son válidos
        if (!Boolean.parseBoolean(responseNIF.getOut()) && !Boolean.parseBoolean(responseNIE.getOut())) {
            System.out.println("Error: NIF/NIE inválido.");
            return false;
        }

        // Validar el NAF
        org.example.www.validaciones.ValidarNAF validarNAF = new org.example.www.validaciones.ValidarNAF();
        validarNAF.setIn(naf);
        org.example.www.validaciones.ValidarNAFResponse responseNAF = validaciones.validarNAF(validarNAF);
        if (!Boolean.parseBoolean(responseNAF.getOut())) {
            System.out.println("Error: NAF inválido.");
            return false;
        }

        // Validar el IBAN
        org.example.www.validaciones.ValidarIBAN validarIBAN = new org.example.www.validaciones.ValidarIBAN();
        validarIBAN.setIn(iban);
        org.example.www.validaciones.ValidarIBANResponse responseIBAN = validaciones.validarIBAN(validarIBAN);
        if (!Boolean.parseBoolean(responseIBAN.getOut())) {
            System.out.println("Error: IBAN inválido.");
            return false;
        }

        // Si todas las validaciones son correctas, proceder con la inserción en la base de datos
        Conexion conexionDB = new Conexion();
        Connection con = conexionDB.conectar();
        if (con == null) return false;

        String sql = "INSERT INTO empleados (nifnie, nombreApellidos, email, naf, iban, idNivel, usuario, password, valido) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        try (PreparedStatement pstmt = con.prepareStatement(sql)) {
            pstmt.setString(1, nuevo.getIn().getNifnie());
            pstmt.setString(2, nuevo.getIn().getNombreApellidos());
            pstmt.setString(3, nuevo.getIn().getEmail());
            pstmt.setString(4, nuevo.getIn().getNaf());
            pstmt.setString(5, nuevo.getIn().getIban());
            pstmt.setInt(6, nuevo.getIn().getIdNivel());
            pstmt.setString(7, nuevo.getIn().getUsuario());
            pstmt.setString(8, nuevo.getIn().getPassword());
            pstmt.setInt(9, nuevo.getIn().getValido());

            return pstmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        } finally {
            conexionDB.cerrarConexion();
        }
    }



    private boolean eliminarEmpleado(org.example.www.empleados.Borrar borrar) {
        Conexion conexionDB = new Conexion();
        Connection con = conexionDB.conectar();
        if (con == null) return false;

        String sql = "DELETE FROM empleados WHERE nifnie = ?";
        try (PreparedStatement pstmt = con.prepareStatement(sql)) {
        	System.out.println("Eliminando empleado con NIF: " + borrar.getIn());
        	pstmt.setString(1, borrar.getIn());
            return pstmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        } finally {
            conexionDB.cerrarConexion();
        }
    }

    private String obtenerEmpleado(org.example.www.empleados.Consultar consultar) {
        Conexion conexionDB = new Conexion();
        Connection con = conexionDB.conectar();
        if (con == null) return null;

        String sql = "SELECT * FROM empleados WHERE nifnie = ?";
        try (PreparedStatement pstmt = con.prepareStatement(sql)) {
            pstmt.setString(1, consultar.getIn());
            ResultSet rs = pstmt.executeQuery();
            if (rs.next()) {
                return "ID: " + rs.getInt("id") + ", Nombre: " + rs.getString("nombreApellidos") + ", Email: " + rs.getString("email");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            conexionDB.cerrarConexion();
        }
        return null;
    }

    private boolean actualizarEmpleado(org.example.www.empleados.Modificar modificar) {
        Conexion conexionDB = new Conexion();
        Connection con = conexionDB.conectar();
        if (con == null) return false;

        String sql = "UPDATE empleados SET nombreApellidos=?, email=?, naf=?, iban=?, idNivel=?, usuario=?, password=?, valido=? WHERE nifnie=?";
        try (PreparedStatement pstmt = con.prepareStatement(sql)) {
            pstmt.setString(1, modificar.getIn().getNombreApellidos());
            pstmt.setString(2, modificar.getIn().getEmail());
            pstmt.setString(3, modificar.getIn().getNaf());
            pstmt.setString(4, modificar.getIn().getIban());
            pstmt.setInt(5, modificar.getIn().getIdNivel());
            pstmt.setString(6, modificar.getIn().getUsuario());
            pstmt.setString(7, modificar.getIn().getPassword());
            pstmt.setInt(8, modificar.getIn().getValido());
            pstmt.setString(9, modificar.getIn().getNifnie());
            return pstmt.executeUpdate() > 0;
        } catch (SQLException e) {
            e.printStackTrace();
            return false;
        } finally {
            conexionDB.cerrarConexion();
        }
    }
}


    