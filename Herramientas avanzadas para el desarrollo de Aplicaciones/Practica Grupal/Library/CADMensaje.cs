using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Data.SqlTypes;
using System.Data.SqlClient;
using System.Data.Common;
using System.Data;
using System.Configuration;


namespace Library
{
    public class CADMensaje
    {
        private string constring;

        public CADMensaje()
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;
        }

        public bool CreateMensaje(ENMensaje en)
        {
            bool creado = false;
            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();

                    // Obtener el siguiente ID de mensaje disponible
                    int siguienteId = ObtenerSiguienteIdMensaje();

                    SqlCommand query = new SqlCommand("INSERT INTO [dbo].[Mensaje] (id, mensaje, tipo_mensaje, fecha) VALUES (@id, @mensaje, @tipo_mensaje, @fecha)", connection);
                    query.Parameters.AddWithValue("@id", siguienteId);
                    query.Parameters.AddWithValue("@mensaje", en.Mensaje);
                    query.Parameters.AddWithValue("@tipo_mensaje", en.TipoMensaje);
                    query.Parameters.AddWithValue("@fecha", en.Fecha);

                    query.ExecuteNonQuery();
                    creado = true;

                    // Guardar el ID del mensaje creado en el objeto ENMensaje
                    en.Id = siguienteId;

                    // Si el mensaje es de tipo "Comentario", insertar en la tabla "Comentario" y establecer una valoración predeterminada
                    if (en.TipoMensaje == "Comentario")
                    {
                        int valoracionPredeterminada = 1; // Valoración predeterminada
                        SqlCommand comentarioQuery = new SqlCommand("INSERT INTO [dbo].[Comentario] (mensaje_id, valoracion) VALUES (@mensaje_id, @valoracion)", connection);
                        comentarioQuery.Parameters.AddWithValue("@mensaje_id", siguienteId);
                        comentarioQuery.Parameters.AddWithValue("@valoracion", valoracionPredeterminada);
                        comentarioQuery.ExecuteNonQuery();
                    }
                    else if (en.TipoMensaje == "Incidencia")
                    {
                        SqlCommand incidenciaQuery = new SqlCommand("INSERT INTO [dbo].[Incidencia] (mensaje_id) VALUES (@mensaje_id)", connection);
                        incidenciaQuery.Parameters.AddWithValue("@mensaje_id", siguienteId);
                        incidenciaQuery.ExecuteNonQuery();
                    }

                    connection.Close();
                }
                catch (SqlException ex)
                {
                    creado = false;
                    Console.WriteLine("Mensaje operation has failed. Error: {0}", ex.Message);
                }
                catch (Exception ex)
                {
                    creado = false;
                    Console.WriteLine("Mensaje operation has failed. Error: {0}", ex.Message);
                }
                finally
                {
                    connection.Close();
                }
            }
            return creado;
        }

        public bool ReadMensaje(ENMensaje enMensaje)
        {
            bool read = false;

            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();

                    string query = "SELECT * FROM Mensaje WHERE id = @id";
                    SqlCommand command = new SqlCommand(query, connection);
                    command.Parameters.AddWithValue("@id", enMensaje.Id);

                    using (SqlDataReader reader = command.ExecuteReader())
                    {
                        if (reader.Read())
                        {
                            enMensaje.Mensaje = reader["mensaje"].ToString();
                            enMensaje.TipoMensaje = reader["tipo_mensaje"].ToString();
                            enMensaje.Fecha = Convert.ToDateTime(reader["fecha"]);

                            read = true;
                        }
                    }
                }
                catch (SqlException ex)
                {
                    Console.WriteLine("Error al leer el mensaje: " + ex.Message);
                }
                catch (Exception ex)
                {
                    Console.WriteLine("Error al leer el mensaje: " + ex.Message);
                }
            }

            return read;
        }

        public bool UpdateMensaje(ENMensaje men)
        {
            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();

                    string query = "UPDATE Mensaje SET mensaje = @mensaje, tipo_mensaje = @tipo_mensaje WHERE id = @id";
                    SqlCommand command = new SqlCommand(query, connection);
                    command.Parameters.AddWithValue("@mensaje", men.Mensaje);
                    command.Parameters.AddWithValue("@tipo_mensaje", men.TipoMensaje);
                    command.Parameters.AddWithValue("@id", men.Id);

                    int rowsUpdated = command.ExecuteNonQuery();

                    return rowsUpdated > 0;
                }
                catch (Exception)
                {
                    return false;
                }
            }
        }

        public bool DeleteMensaje(ENMensaje men)
        {
            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();

                    string query = "DELETE FROM Mensaje WHERE id = @id";
                    SqlCommand command = new SqlCommand(query, connection);
                    command.Parameters.AddWithValue("@id", men.Id);

                    int rowsDeleted = command.ExecuteNonQuery();

                    return rowsDeleted > 0;
                }
                catch (Exception)
                {
                    return false;
                }
                finally { 
                    connection.Close();
                }
            }
        }

        public int ObtenerSiguienteIdMensaje()
        {
            string constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;
            int siguienteId = 0;

            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();

                    // Consultar el máximo ID de la tabla Mensaje
                    string maxIdQuery = "SELECT MAX(id) FROM Mensaje";
                    SqlCommand maxIdCommand = new SqlCommand(maxIdQuery, connection);
                    var result = maxIdCommand.ExecuteScalar();

                    if (result != DBNull.Value)
                    {
                        int maxId = Convert.ToInt32(result);
                        siguienteId = maxId + 1;
                    }
                    else
                    {
                        // No hay registros en la tabla Mensaje, establecer el siguiente ID como 1
                        siguienteId = 1;
                    }
                }
                catch (Exception)
                {
                    // Manejar el error según sea necesario
                }
                finally
                {
                    connection.Close();
                }
            }

            return siguienteId;
        }
        
        public bool UpdateComentario(ENComentario comentario)
        {
            bool actualizado = false;
            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();

                    SqlCommand query = new SqlCommand("UPDATE [dbo].[Comentario] SET valoracion = @valoracion WHERE mensaje_id = @mensaje_id", connection);
                    query.Parameters.AddWithValue("@valoracion", comentario.Valoracion);
                    query.Parameters.AddWithValue("@mensaje_id", comentario.Id);

                    int rowsAffected = query.ExecuteNonQuery();
                    if (rowsAffected > 0)
                        actualizado = true;

                    connection.Close();
                }
                catch (SqlException ex)
                {
                    actualizado = false;
                    Console.WriteLine("Comentario update has failed. Error: {0}", ex.Message);
                }
                catch (Exception ex)
                {
                    actualizado = false;
                    Console.WriteLine("Comentario update has failed. Error: {0}", ex.Message);
                }
                finally { connection.Close(); }
            }
            return actualizado;
        }

        public DataTable GetIncidencias()
        {
            string connectionString = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;

            using (SqlConnection con = new SqlConnection(connectionString))
            {
                using (SqlCommand cmd = new SqlCommand("SELECT Incidencia.mensaje_id, Mensaje.mensaje FROM Incidencia JOIN Mensaje ON Incidencia.mensaje_id = Mensaje.id", con))
                {
                    using (SqlDataAdapter sda = new SqlDataAdapter(cmd))
                    {
                        DataTable dt = new DataTable();
                        sda.Fill(dt);
                        return dt;
                    }
                }
            }
        }

        public void EliminarIncidencia(int mensaje_id)
        {
            string connectionString = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;

            using (SqlConnection con = new SqlConnection(connectionString))
            {
                using (SqlCommand cmd = new SqlCommand("DELETE FROM Incidencia WHERE mensaje_id = @mensaje_id; DELETE FROM Mensaje WHERE id = @mensaje_id;", con))
                {
                    cmd.Parameters.AddWithValue("@mensaje_id", mensaje_id);
                    con.Open();
                    cmd.ExecuteNonQuery();
                    con.Close();
                }
            }
        }
    }

}