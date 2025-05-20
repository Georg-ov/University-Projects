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
    public class CADProducto
    {

        private string constring;

        public CADProducto()
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ToString();

        }

        public bool createProducto(ENProducto prod)
        {
            bool created = false;
            using (SqlConnection connection = new SqlConnection(constring))
            {

                try
                {
                    connection.Open();

                    int nextId = ObtenerNextIDProducto();

                    SqlCommand query = new SqlCommand("INSERT INTO [dbo].[Producto] (id, nombre, descripcion, precio, imagen, tipo_producto) VALUES (@id, @nombre, @descripcion, @precio, @imagen, @tipo_producto)", connection);

                    query.Parameters.Add("@id", SqlDbType.Int).Value = nextId;
                    query.Parameters.AddWithValue("@nombre", prod.nombreProd);
                    query.Parameters.AddWithValue("@descripcion", prod.descripcionProd);
                    query.Parameters.AddWithValue("@precio", prod.precioProd);
                    query.Parameters.AddWithValue("@imagen", prod.imagenProd);
                    query.Parameters.AddWithValue("@tipo_producto", prod.tipoProd);

                    query.ExecuteNonQuery();
                    created = true;

                    prod.idProd = nextId;
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

        public bool readProducto(ENProducto prod)
        {
            SqlConnection connection = null;
            bool read;

            try
            {
                connection = new SqlConnection(constring);
                connection.Open();

                string query = "SELECT * FROM Producto WHERE id = @id";
                SqlCommand command = new SqlCommand(query, connection);
                command.Parameters.AddWithValue("@id", prod.idProd);
                SqlDataReader reader = command.ExecuteReader();
                read = reader.Read();

                prod.precioProd = float.Parse(reader["precio"].ToString());
                prod.descripcionProd = reader["descripcion"].ToString();
                prod.nombreProd = reader["nombre"].ToString().Trim();
                prod.tipoProd = reader["tipo_producto"].ToString() ;
                prod.imagenProd = reader["imagen"].ToString();
             
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

        public bool updateProducto(ENProducto prod)
        {
            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();

                    string query = "UPDATE Producto SET nombre = @nombre, descripcion = @descripcion, precio = @precio, imagen = @imagen, " +
                    "tipo_producto = @tipo_producto WHERE id = @id";

                    SqlCommand command = new SqlCommand(query, connection);
                    command.Parameters.AddWithValue("@id", prod.idProd);
                    command.Parameters.AddWithValue("@nombre", prod.nombreProd);
                    command.Parameters.AddWithValue("@descripcion", prod.descripcionProd);
                    command.Parameters.AddWithValue("@precio", prod.precioProd);
                    command.Parameters.AddWithValue("@imagen", prod.imagenProd);
                    command.Parameters.AddWithValue("@tipo_producto", prod.tipoProd);

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

        public bool deleteProducto(ENProducto prod)
        {
            using (SqlConnection connection = new SqlConnection(constring))

                try
                {
                    connection.Open();

                    string query = "DELETE FROM Producto WHERE id = @id";
                    SqlCommand command = new SqlCommand(query, connection);
                    command.Parameters.AddWithValue("@id", prod.idProd);

                    int rowsDeleted = command.ExecuteNonQuery();

                    return rowsDeleted > 0;
                }
                catch (SqlException)
                {
                    Console.WriteLine("Error. No se pudo eliminar el Producto.");
                    return false;
                }
                catch (Exception)
                {
                    Console.WriteLine("Error. No se pudo eliminar el Producto.");
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

        
        public List<ENProducto> GetProductosPorTipo(string tipoProducto)
        {
            List<ENProducto> productos = new List<ENProducto>();
            using (SqlConnection connection = new SqlConnection(constring))
            {
                connection.Open();
                string query = "SELECT * FROM Producto WHERE tipo_producto = @tipoProducto";
                using (SqlCommand command = new SqlCommand(query, connection))
                {
                    command.Parameters.AddWithValue("@tipoProducto", tipoProducto);
                    SqlDataReader reader = command.ExecuteReader();
                    while (reader.Read())
                    {
                        ENProducto producto = new ENProducto();
                        producto.idProd = int.Parse(reader["id"].ToString());
                        producto.nombreProd = reader["nombre"].ToString();
                        producto.descripcionProd = reader["descripcion"].ToString();
                        producto.precioProd = float.Parse(reader["precio"].ToString());
                        producto.imagenProd = reader["imagen"].ToString();
                        producto.tipoProd = reader["tipo_producto"].ToString();
                        productos.Add(producto);
                    }
                }
            }
            return productos;
        }

        public int ObtenerNextIDProducto()
        {
            string constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;
            int nextId = 0;

            using (SqlConnection connection = new SqlConnection(constring))
            {
                try
                {
                    connection.Open();

                    // Consultar el máximo ID de la tabla Producto
                    string maxIdQuery = "SELECT MAX(id) FROM Producto";
                    SqlCommand maxIdCommand = new SqlCommand(maxIdQuery, connection);
                    var result = maxIdCommand.ExecuteScalar();

                    if (result != DBNull.Value)
                    {
                        int maxId = Convert.ToInt32(result);
                        nextId = maxId + 1;
                    }
                    else
                    {
                        // No hay registros en la tabla Producto, establecer el siguiente ID como 1
                        nextId = 1;
                    }
                }
                catch (Exception)
                {
                    // Error
                }
            }

            return nextId;
        }

        public ENProducto GetProductoById(int id)
        {
            ENProducto producto = null;
            using (SqlConnection connection = new SqlConnection(constring))
            {
                connection.Open();
                using (SqlCommand command = new SqlCommand("SELECT * FROM Producto WHERE id = @id", connection))
                {
                    command.Parameters.AddWithValue("@id", id);
                    SqlDataReader reader = command.ExecuteReader();
                    while (reader.Read())
                    {
                        producto = new ENProducto();
                        producto.idProd = int.Parse(reader["id"].ToString());
                        producto.nombreProd = reader["nombre"].ToString();
                        producto.descripcionProd = reader["descripcion"].ToString();
                        producto.precioProd = float.Parse(reader["precio"].ToString());
                        producto.imagenProd = reader["imagen"].ToString();
                        producto.tipoProd = reader["tipo_producto"].ToString();
                    }
                }
            }
            return producto;
        }


        public ENProducto GetProductoByNombre(string nombre)
        {
            ENProducto producto = null;
            using (SqlConnection connection = new SqlConnection(constring))
            {
                connection.Open();
                using (SqlCommand command = new SqlCommand("SELECT * FROM Producto WHERE nombre LIKE @nombre", connection))
                {
                    command.Parameters.AddWithValue("@nombre", nombre + "%");
                    SqlDataReader reader = command.ExecuteReader();
                    while (reader.Read())
                    {
                        producto = new ENProducto();
                        producto.idProd = int.Parse(reader["id"].ToString());
                        producto.nombreProd = reader["nombre"].ToString();
                        producto.descripcionProd = reader["descripcion"].ToString();
                        producto.precioProd = float.Parse(reader["precio"].ToString());
                        producto.imagenProd = reader["imagen"].ToString();
                        producto.tipoProd = reader["tipo_producto"].ToString();
                        
                        
                    }
                }
            }
            return producto;
        }

        public List<ENProducto> GetProductosPorNombre(string nombreProducto)
        {
            List<ENProducto> productos = new List<ENProducto>();
            using (SqlConnection connection = new SqlConnection(constring))
            {
                connection.Open();
                string query = "SELECT * FROM Producto WHERE nombre LIKE @nombre";
                using (SqlCommand command = new SqlCommand(query, connection))
                {
                    command.Parameters.AddWithValue("@nombre", nombreProducto + "%");
                    SqlDataReader reader = command.ExecuteReader();
                    while (reader.Read())
                    {
                        ENProducto producto = new ENProducto();
                        producto.idProd = int.Parse(reader["id"].ToString());
                        producto.nombreProd = reader["nombre"].ToString();
                        producto.descripcionProd = reader["descripcion"].ToString();
                        producto.precioProd = float.Parse(reader["precio"].ToString());
                        producto.imagenProd = reader["imagen"].ToString();
                        producto.tipoProd = reader["tipo_producto"].ToString();
                        productos.Add(producto);
                    }
                }
            }
            return productos;
        }

    }
}
