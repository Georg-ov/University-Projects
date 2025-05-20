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
    internal class CADPedido {
        private readonly String constring;

        public CADPedido()
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ToString();
        }

        public bool CreatePedido(ENPedido pedido) 
        {
            bool created = false;
            using (SqlConnection con = new SqlConnection(constring))
            {
                try
                {
                    con.Open();
                    SqlCommand command = new SqlCommand("INSERT INTO Pedido (id, fecha_pedido, cliente_email, local_id, estado, cantidad, total) VALUES (@id, @fecha_pedido, @cliente_email, @local_id, @estado, @cantidad, @total)", con);
                    command.Parameters.AddWithValue("@id", pedido.CodP);
                    command.Parameters.AddWithValue("@fecha_pedido", pedido.Fecha);
                    command.Parameters.AddWithValue("@cliente_email", pedido.Emailcliente);
                    command.Parameters.AddWithValue("@local_id", pedido.Idlocal);
                    command.Parameters.AddWithValue("@total", pedido.Precio);

                    if(pedido.State == Estado.encurso)
                        command.Parameters.AddWithValue("@estado", "En curso");
                    else if(pedido.State == Estado.preparado)
                        command.Parameters.AddWithValue("@estado", "Preparado");
                    else
                        command.Parameters.AddWithValue("@estado", "En reparto");

                    command.Parameters.AddWithValue("@cantidad", pedido.Cantidad);
                    int rowsAffected = command.ExecuteNonQuery();

                    if (rowsAffected > 0)
                        created = true;
                }
                catch (SqlException)
                {
                    Console.WriteLine("Error al crear el pedido");
                }
                catch (Exception)
                {
                    Console.WriteLine("Error al crear el pedido");
                }
                finally
                {
                    if (con != null)
                    {
                        con.Close();
                    }
                }
            }

            return created;
        }

        public bool UpdatePedido(ENPedido pedido, Estado estado) {
            using (SqlConnection con = new SqlConnection(constring))
            {
                try
                {
                    con.Open();
                    SqlCommand command = new SqlCommand("UPDATE Pedido SET estado=@NewState WHERE codP=@codpedido", con);
                    if (pedido.State == Estado.encurso)
                        command.Parameters.AddWithValue("@NewState", "En curso");
                    else if (pedido.State == Estado.preparado)
                        command.Parameters.AddWithValue("@NewState", "Preparado");
                    else
                        command.Parameters.AddWithValue("@NewState", "En reparto");
                    command.Parameters.AddWithValue("@NewState", estado);
                    command.Parameters.AddWithValue("@codpedido", pedido.CodP);
                    int rowsAffected = command.ExecuteNonQuery();

                }
                catch (SqlException)
                {
                    Console.WriteLine("Error al cambiar el estado del pedido");
                }
                catch (Exception)
                {
                    Console.WriteLine("Error al cambiar el estado del pedido");
                }
                finally
                {
                    if (con != null)
                    {
                        con.Close();
                    }
                }
            }
            return true;
    }

        public bool ReadPedido(ENPedido pedido) 
        {
            bool read = false;
            SqlConnection con = new SqlConnection(constring);
            DataSet create = new DataSet();
            DataTable t = new DataTable();

            try
            {
                SqlDataAdapter d = new SqlDataAdapter("Select * from Pedido", con);
                d.Fill(create, "pedido");
                t = create.Tables["pedido"];

                for (int i = 0; i < t.Rows.Count && !read; i++)
                {
                    if (t.Rows[i]["codP"].ToString().Equals(pedido.CodP))
                    {
                        pedido.CodP = int.Parse(t.Rows[i]["id"].ToString());
                        pedido.Emailcliente = t.Rows[i]["cliente_email"].ToString();
                        pedido.Cantidad = int.Parse(t.Rows[i]["cantidad"].ToString());
                        pedido.Idlocal = int.Parse(t.Rows[i]["local_id"].ToString()) ;
                        pedido.Fecha = DateTime.Parse(t.Rows[i]["fecha_pedido"].ToString());
                        if (t.Rows[i]["estado"].ToString().Equals("En curso"))
                            pedido.State = Estado.encurso;
                        else if (t.Rows[i]["estado"].ToString().Equals("En reparto"))
                            pedido.State = Estado.enreparto;
                        else
                            pedido.State = Estado.preparado;
                        read = true;
                    }
                }

            }
            catch (SqlException)
            {
                Console.WriteLine("Error al leer el pedido");
            }
            catch (Exception)
            {
                Console.WriteLine("Error al leer el pedido");
            }
            finally
            {
                if(con != null) { con.Close(); }
            }
            return read;
        }


        public bool DeletePedido(ENPedido pedido) 
        {
            SqlConnection con = new SqlConnection(constring);
            bool eliminado = false;

            try
            {
                string consulta = $"delete from [dbo].[Pedido] where codP = '{pedido.CodP}'";
                SqlCommand comm = new SqlCommand(consulta, con);

                con.Open();
                comm.ExecuteNonQuery();
                eliminado = true;
            }
            catch (SqlException)
            {
                Console.WriteLine("Error. No se pudo eliminar el pedido.");
            }
            catch (Exception)
            {
                Console.WriteLine("Error. No se pudo eliminar el pedido.");
            }
            finally
            {
                if (con != null)
                {
                    con.Close();
                }
            }
            return eliminado;
        }

        public List<ENPedido> getPedidos(ENUsuario usu)
        {
            List<ENPedido> list = new List<ENPedido>();
            SqlConnection con = new SqlConnection(constring);

            try
            {
                string consulta = $"select * from [dbo].[Pedido] where cliente_email = '{usu.Email}'";
                SqlCommand comm = new SqlCommand(consulta, con);
                con.Open();
                SqlDataReader reader = comm.ExecuteReader();

                while (reader.Read())
                {
                    ENPedido en = new ENPedido();
                    en.Precio = float.Parse(reader["total"].ToString());
                    en.CodP = int.Parse(reader["id"].ToString());
                    en.Idlocal = int.Parse(reader["local_id"].ToString());
                    en.Fecha = DateTime.Parse(reader["fecha_pedido"].ToString());
                    if (reader["estado"].ToString().Equals("En curso"))
                        en.State = Estado.encurso;
                    else if (reader["estado"].ToString().Equals("En reparto"))
                        en.State = Estado.enreparto;
                    else
                        en.State = Estado.preparado;
                    list.Add(en);
                }
            }
            catch(SqlException)
            {

            }
            catch(Exception)
            {

            }
            finally
            {
                con.Close();
            }
            return list;
        }


        public bool asignarProductos(ENPedido en, ENProducto prod)
        {
            bool created = false;
            using (SqlConnection con = new SqlConnection(constring))
            {
                try
                {
                    con.Open();
                    SqlCommand command = new SqlCommand($"INSERT INTO Pedido_prod (pedido_id, producto_id, cantidad) VALUES ({en.CodP}, {prod.idProd}, 1)", con);

                    int rowsAffected = command.ExecuteNonQuery();

                    if (rowsAffected > 0)
                        created = true;
                }
                catch (SqlException)
                {
                    Console.WriteLine("Error al crear el pedido");
                }
                catch (Exception)
                {
                    Console.WriteLine("Error al crear el pedido");
                }
                finally
                {
                    if (con != null)
                    {
                        con.Close();
                    }
                }
            }

            return created;

        }

        public bool asignarMenu(ENPedido en, ENMenu prod)
        {
            bool created = false;
            using (SqlConnection con = new SqlConnection(constring))
            {
                try
                {
                    con.Open();
                    SqlCommand command = new SqlCommand($"INSERT INTO Pedido_menu (pedido_id, menu_id, cantidad) VALUES ({en.CodP}, {prod.Id}, 1)", con);

                    int rowsAffected = command.ExecuteNonQuery();

                    if (rowsAffected > 0)
                        created = true;
                }
                catch (SqlException)
                {
                    Console.WriteLine("Error al crear el pedido");
                }
                catch (Exception)
                {
                    Console.WriteLine("Error al crear el pedido");
                }
                finally
                {
                    if (con != null)
                    {
                        con.Close();
                    }
                }
            }

            return created;

        }
    
    
    }
}
