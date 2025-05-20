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
using Library;



namespace Library
{
    public class CADMenu
    {
        private string constring;

        public CADMenu()
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ToString();
        }


        public bool CreateMenu(ENMenu men)
        {
            bool created = false;
            using (SqlConnection connection = new SqlConnection(constring))
            {

                try
                {
                    connection.Open();

                    int nextId = ObtenerNextIDMenu();

                    SqlCommand query = new SqlCommand("INSERT INTO [dbo].[Menu] (id, nombre, precio, id_prod1, id_prod2, id_prod3) VALUES (@id, @nombre, @precio, @id_prod1, @id_prod2, @id_prod3)", connection);
                    query.Parameters.AddWithValue("@id", nextId);
                    query.Parameters.AddWithValue("@nombre", men.Nombre);
                    query.Parameters.AddWithValue("@precio", men.Precio);
                    query.Parameters.AddWithValue("@id_prod1", men.Id_prod1);
                    query.Parameters.AddWithValue("@id_prod2", men.Id_prod2);
                    query.Parameters.AddWithValue("@id_prod3", men.Id_prod3);

                    query.ExecuteNonQuery();
                    created = true;

                    men.Id = nextId;
                }
                catch (SqlException exc)
                {
                    created = false;
                    Console.WriteLine("Mensaje operation has failed. Error: {0}", exc.Message);
                }
                catch (Exception exc)
                {
                    created = false;
                    Console.WriteLine("Mensaje operation has failed. Error: {0}", exc.Message);
                }
                finally
                {
                    if (connection != null)
                    {
                        connection.Close();
                    }
                }
                return created;
            }
        }

        public bool ReadMenu(ENMenu men)
        {
             SqlConnection connection = null;
             bool read;

             try              
             {
                connection = new SqlConnection(constring);
                connection.Open();
 
                string query = "SELECT * FROM Menu WHERE id = @id";
                SqlCommand command = new SqlCommand(query, connection);
                command.Parameters.AddWithValue("@id", men.Id);

                SqlDataReader reader = command.ExecuteReader();
                reader.Read();

                men.Id_prod1 = int.Parse(reader["id_prod1"].ToString());
                men.Id_prod2 = int.Parse(reader["id_prod2"].ToString());
                men.Id_prod3 = int.Parse(reader["id_prod3"].ToString());


                read = true;
             }
             
             catch (Exception exc)
             {
                Console.WriteLine("Error al leer el mensaje: " + exc.Message);
                read = false;
             }

             finally
             {
                  if (connection != null)
                  {
                    connection.Close();
                  }
             }
             
             return read;
        }


        public bool UpdateMenu(ENMenu men)
        {
            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();

                    string query = "UPDATE Menu SET nombre = @nombre, precio = @precio, id_prod1 = @id_prod1, id_prod2 = @id_prod2, id_prod3 = @id_prod3 WHERE id = @id";
                    SqlCommand command = new SqlCommand(query, connection);
                    command.Parameters.AddWithValue("@nombre", men.Nombre);
                    command.Parameters.AddWithValue("@precio", men.Precio);
                    command.Parameters.AddWithValue("@id", men.Id);
                    command.Parameters.AddWithValue("@id_prod1", men.Id_prod1);
                    command.Parameters.AddWithValue("@id_prod2", men.Id_prod2);
                    command.Parameters.AddWithValue("@id_prod3", men.Id_prod3);

                    int rowsUpdated = command.ExecuteNonQuery();

                    return rowsUpdated > 0;
                }
                catch (SqlException)
                {
                    Console.WriteLine("Error al actualizar los datos");
                    return false;
                }
                catch (Exception)
                {
                    Console.WriteLine("Error al actualizar los datos");
                    return false;
                }
                finally
                {
                    if (connection != null)
                    {
                        connection.Close();
                    }
                }

            }
        }

        public bool DeleteMenu(ENMenu men)
        {
            using (SqlConnection connection = new SqlConnection(constring))

            try
            {
                connection.Open();

                string query = "DELETE FROM Menu WHERE id = @id";
                SqlCommand command = new SqlCommand(query, connection);
                command.Parameters.AddWithValue("@id", men.Id);

                int rowsDeleted = command.ExecuteNonQuery();

                return rowsDeleted > 0;
            }
            catch (SqlException)
            {
                Console.WriteLine("Error. No se pudo eliminar el Menu.");
                return false;
            }
            catch (Exception)
            {
                Console.WriteLine("Error. No se pudo eliminar el Menu.");
                return false;
            }
            finally
            {
                if (connection != null)
                {
                    connection.Close();
                }
            }
             
        }

        public List<ENMenu> GetMenus()
        {
            List<ENMenu> menus = new List<ENMenu>();
            using (SqlConnection connection = new SqlConnection(constring))
            {
                connection.Open();
                using (SqlCommand command = new SqlCommand("SELECT * FROM Menu", connection))
                {
                    SqlDataReader reader = command.ExecuteReader();
                    while (reader.Read())
                    {
                        ENMenu menu = new ENMenu();
                        menu.Id = int.Parse(reader["id"].ToString());
                        menu.Nombre = reader["nombre"].ToString();
                        menu.Precio = float.Parse(reader["precio"].ToString());
                        menu.Id_prod1 = int.Parse(reader["id_prod1"].ToString());
                        menu.Id_prod2 = int.Parse(reader["id_prod2"].ToString());
                        menu.Id_prod3 = int.Parse(reader["id_prod3"].ToString());

                        menus.Add(menu);
                    }
                }

            }
            return menus;
        }

        public int ObtenerNextIDMenu()
        {
            string constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;
            int nextId = 0;

            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();

                    // Consultar el máximo ID de la tabla Menu
                    string maxIdQuery = "SELECT MAX(id) FROM Menu";
                    SqlCommand maxIdCommand = new SqlCommand(maxIdQuery, connection);
                    var result = maxIdCommand.ExecuteScalar();

                    if (result != DBNull.Value)
                    {
                        int maxId = Convert.ToInt32(result);
                        nextId = maxId + 1;
                    }
                    else
                    {
                        // No hay registros en la tabla Menu, establecer el siguiente ID como 1
                        nextId = 1;
                    }
                }
                catch (Exception)
                {
                    // Manejar el error según sea necesario
                }
            }

            return nextId;
        }

        public ENMenu GetMenuById(int id)
        {
            ENMenu menu = null;
            using (SqlConnection connection = new SqlConnection(constring))
            {
                connection.Open();
                using (SqlCommand command = new SqlCommand("SELECT * FROM Menu WHERE id = @id", connection))
                {
                    command.Parameters.AddWithValue("@id", id);
                    SqlDataReader reader = command.ExecuteReader();
                    while (reader.Read())
                    {
                        menu = new ENMenu();
                        menu.Id = int.Parse(reader["id"].ToString());
                        menu.Nombre = reader["nombre"].ToString();
                        menu.Precio = float.Parse(reader["precio"].ToString());
                        menu.Id_prod1 = int.Parse(reader["id_prod1"].ToString());
                        menu.Id_prod2 = int.Parse(reader["id_prod2"].ToString());
                        menu.Id_prod3 = int.Parse(reader["id_prod3"].ToString());
                    }
                }
            }
            return menu;
        }
    }
}