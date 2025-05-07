package conexionDB;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;

public class Conexion {
    private static final String CONTROLADOR = "com.mysql.cj.jdbc.Driver";
    private static final String URL = "jdbc:mysql://localhost:3307/mtis";
    private static final String USUARIO = "root";
    private static final String CLAVE = "root";

    private Connection conec = null;

    public Connection conectar() {
        try {
            Class.forName(CONTROLADOR);
            conec = DriverManager.getConnection(URL, USUARIO, CLAVE);
            System.out.println("Conexión OK");
        } catch (SQLException e) {
            e.printStackTrace();
        } catch (ClassNotFoundException e) {
            System.out.println(e.toString());
            e.printStackTrace();
        }
        return conec;
    }

    public void cerrarConexion() {
        try {
            if (conec != null) {
                conec.close();
                System.out.println("Conexion cerrada correctamente");
            }
        } catch (SQLException e) {
            e.printStackTrace();
        }
    }
}