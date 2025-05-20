using System;
using System.Collections.Generic;
using System.Data.SqlClient;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Configuration;

namespace Library
{
    public class CADLocalMensajeUsuario
    {
        private string constring;  

        public CADLocalMensajeUsuario()
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;
        }

        public bool CreateLocalMensajeUsuario(ENLocalMensajeUsuario localMensajeUsuario)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "INSERT INTO Local_Mensaje_Usuario (local_id, mensaje_id, usuario_email) VALUES (@localId, @mensajeId, @usuarioEmail)";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@localId", localMensajeUsuario.LocalId);
                    cmd.Parameters.AddWithValue("@mensajeId", localMensajeUsuario.MensajeId);
                    cmd.Parameters.AddWithValue("@usuarioEmail", localMensajeUsuario.UsuarioEmail);

                    conn.Open();
                    int rowsAffected = cmd.ExecuteNonQuery();

                    return rowsAffected > 0;
                }
            }
        }

        public bool ReadLocalMensajeUsuario(ENLocalMensajeUsuario localMensajeUsuario)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "SELECT * FROM Local_Mensaje_Usuario WHERE local_id = @localId AND mensaje_id = @mensajeId AND usuario_email = @usuarioEmail";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@localId", localMensajeUsuario.LocalId);
                    cmd.Parameters.AddWithValue("@mensajeId", localMensajeUsuario.MensajeId);
                    cmd.Parameters.AddWithValue("@usuarioEmail", localMensajeUsuario.UsuarioEmail);

                    conn.Open();
                    using (SqlDataReader reader = cmd.ExecuteReader())
                    {
                        return reader.Read(); // Returns true if a record is found, false otherwise
                    }
                }
            }
        }

        public bool UpdateLocalMensajeUsuario(ENLocalMensajeUsuario localMensajeUsuario)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "UPDATE Local_Mensaje_Usuario SET local_id = @localId, mensaje_id = @mensajeId, usuario_email = @usuarioEmail WHERE local_id = @localId AND mensaje_id = @mensajeId AND usuario_email = @usuarioEmail";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@localId", localMensajeUsuario.LocalId);
                    cmd.Parameters.AddWithValue("@mensajeId", localMensajeUsuario.MensajeId);
                    cmd.Parameters.AddWithValue("@usuarioEmail", localMensajeUsuario.UsuarioEmail);

                    conn.Open();
                    int rowsAffected = cmd.ExecuteNonQuery();

                    return rowsAffected > 0;
                }
            }
        }

        public bool DeleteLocalMensajeUsuario(ENLocalMensajeUsuario localMensajeUsuario)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "DELETE FROM Local_Mensaje_Usuario WHERE local_id = @localId AND mensaje_id = @mensajeId AND usuario_email = @usuarioEmail";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@localId", localMensajeUsuario.LocalId);
                    cmd.Parameters.AddWithValue("@mensajeId", localMensajeUsuario.MensajeId);
                    cmd.Parameters.AddWithValue("@usuarioEmail", localMensajeUsuario.UsuarioEmail);

                    conn.Open();
                    int rowsAffected = cmd.ExecuteNonQuery();

                    return rowsAffected > 0;
                }
            }
        }

        public List<ENLocalMensajeUsuario> GetAllComentarios()
        {
            List<ENLocalMensajeUsuario> comentarios = new List<ENLocalMensajeUsuario>();

            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();
                    string query = "SELECT lmu.* FROM Local_Mensaje_Usuario lmu JOIN Mensaje m ON lmu.mensaje_id = m.id WHERE m.tipo_mensaje = @tipo_mensaje";
                    SqlCommand command = new SqlCommand(query, connection);
                    command.Parameters.AddWithValue("@tipo_mensaje", "Comentario");

                    SqlDataReader reader = command.ExecuteReader();
                    while (reader.Read())
                    {
                        ENLocalMensajeUsuario enLocalMensajeUsuario = new ENLocalMensajeUsuario();
                        enLocalMensajeUsuario.LocalId = Convert.ToInt32(reader["local_id"]);
                        enLocalMensajeUsuario.MensajeId = Convert.ToInt32(reader["mensaje_id"]);
                        enLocalMensajeUsuario.UsuarioEmail = Convert.ToString(reader["usuario_email"]);
                        comentarios.Add(enLocalMensajeUsuario);
                    }
                }
                catch (Exception ex)
                {
                    throw ex;
                }
            }

            return comentarios;
        }

        public string GetMensajePorId(int mensajeId)
        {
            string mensaje = string.Empty;

            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();
                    string query = "SELECT mensaje FROM Mensaje WHERE id = @mensajeId";
                    SqlCommand command = new SqlCommand(query, connection);
                    command.Parameters.AddWithValue("@mensajeId", mensajeId);

                    object result = command.ExecuteScalar();
                    if (result != null)
                    {
                        mensaje = result.ToString();
                    }
                }
                catch (Exception ex)
                {
                    throw ex;
                }
                finally { connection.Close(); }
            }

            return mensaje;
        }

        public List<ENLocal> GetLocales()
        {
            List<ENLocal> locales = new List<ENLocal>();

            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();
                    string query = "SELECT id, nombre, direccion, ciudad, telefono FROM Locales";
                    SqlCommand command = new SqlCommand(query, connection);

                    SqlDataReader reader = command.ExecuteReader();
                    while (reader.Read())
                    {
                        ENLocal local = new ENLocal();
                        local.ID = Convert.ToInt32(reader["id"]);
                        local.Nombre = Convert.ToString(reader["nombre"]);
                        local.Direccion = Convert.ToString(reader["direccion"]);
                        local.Ciudad = Convert.ToString(reader["ciudad"]);
                        local.Telefono = Convert.ToString(reader["telefono"]);
                        locales.Add(local);
                    }
                }
                catch (Exception ex)
                {
                    throw ex;
                }
                finally { connection.Close(); }
            }

            return locales;
        }


        public void AgregarLocalMensajeUsuario(int localId, int mensajeId, string usuarioEmail)
        {
            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();
                    string query = "INSERT INTO Local_Mensaje_Usuario (local_id, mensaje_id, usuario_email) VALUES (@localId, @mensajeId, @usuarioEmail)";
                    SqlCommand command = new SqlCommand(query, connection);
                    command.Parameters.AddWithValue("@localId", localId);
                    command.Parameters.AddWithValue("@mensajeId", mensajeId);
                    command.Parameters.AddWithValue("@usuarioEmail", usuarioEmail);

                    command.ExecuteNonQuery();
                }
                catch (Exception ex)
                {
                    throw ex;
                }
                finally { connection.Close(); }
            }

        }
        

        public List<dynamic> ObtenerMensajesConUsuarios()
        {
            List<dynamic> mensajesConUsuarios = new List<dynamic>();

            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = @"SELECT lmu.usuario_email AS UsuarioEmail, m.mensaje AS Mensaje, m.fecha AS Fecha, l.nombre AS NombreLocal, c.mensaje_id AS ComentarioId
                       FROM Mensaje m
                       JOIN Local_Mensaje_Usuario lmu ON m.id = lmu.mensaje_id
                       JOIN Locales l ON lmu.local_id = l.id
                       LEFT JOIN Comentario c ON m.id = c.mensaje_id
                       WHERE m.tipo_mensaje = 'Comentario'";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    conn.Open();
                    using (SqlDataReader reader = cmd.ExecuteReader())
                    {
                        while (reader.Read())
                        {
                            var mensajeUsuario = new
                            {
                                UsuarioEmail = reader["UsuarioEmail"].ToString(),
                                Mensaje = reader["Mensaje"].ToString(),
                                Fecha = Convert.ToDateTime(reader["Fecha"]).ToString("d"),
                                NombreLocal = reader["NombreLocal"].ToString(),
                                ComentarioId = reader["ComentarioId"].ToString()
                            };

                            mensajesConUsuarios.Add(mensajeUsuario);
                        }
                    }
                }
            }

            return mensajesConUsuarios;
        }

        public void EliminarMensajeYRegistrosRelacionados(string mensajeId)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                conn.Open();
                using (SqlTransaction transaction = conn.BeginTransaction())
                {
                    try
                    {
                        // Elimina el mensaje y los registros relacionados en una transacción
                        using (SqlCommand cmd = conn.CreateCommand())
                        {
                            cmd.Transaction = transaction;

                            // Elimina el mensaje
                            cmd.CommandText = "DELETE FROM Mensaje WHERE id = @mensajeId";
                            cmd.Parameters.AddWithValue("@mensajeId", mensajeId);
                            cmd.ExecuteNonQuery();

                            // Elimina los registros relacionados en Local_Mensaje_Usuario
                            cmd.CommandText = "DELETE FROM Local_Mensaje_Usuario WHERE mensaje_id = @mensajeId";
                            cmd.ExecuteNonQuery();

                            // Commit la transacción si todas las operaciones fueron exitosas
                            transaction.Commit();
                        }

                        // Realiza cualquier otra acción necesaria después de eliminar el mensaje y registros relacionados
                    }
                    catch (Exception ex)
                    {
                        // Ocurrió un error, realiza el rollback de la transacción
                        transaction.Rollback();

                        // Maneja el error según tu implementación (por ejemplo, mostrar un mensaje de error, registrar en el log, etc.)
                    }
                }
            }
        }

    }
}
