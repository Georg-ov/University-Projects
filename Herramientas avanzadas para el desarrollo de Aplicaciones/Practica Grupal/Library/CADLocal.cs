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
    public class CADLocal
    {

        private String constring;
        public CADLocal() 
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ToString();
        }

        public bool CreateLocal(ENLocal en)
        {
            bool created = false;
            SqlConnection conection = new SqlConnection(constring);
            DataSet bdLocal = new DataSet();

            try
            {
                SqlDataAdapter d = new SqlDataAdapter("Select * from Locales", conection);
                d.Fill(bdLocal, "Locales");
                DataTable t = new DataTable();
                t = bdLocal.Tables["Locales"];
                DataRow fila = t.NewRow();
                int maxId = bdLocal.Tables["Locales"].AsEnumerable().Max(row => row.Field<int>("ID"));

                int newId = maxId + 1;
                fila[0] = newId;
                fila[1] = en.Nombre;
                fila[2] = en.Direccion;
                fila[3] = en.Ciudad;
                fila[4] = en.Supervisor;
                fila[5] = en.Telefono;
                fila[6] = en.Ubicacion;
                
                t.Rows.Add(fila);
                SqlCommandBuilder cbuilder = new SqlCommandBuilder(d);
                int hecho = d.Update(bdLocal, "Locales");
                if (hecho == 1)
                {
                    created = true;
                }
            }
            catch (SqlException)
            {
                created = false;
            }
            catch (Exception)
            {
                created = false;
            }
            finally
            {
                if (conection.State == ConnectionState.Open)
                {
                    conection.Close();
                }
            }

            return created;
        }

        public bool ReadLocal(ENLocal en) 
        {
            bool read = true;
            SqlConnection conection = null;
            conection = new SqlConnection(constring);

            try
            {
                conection.Open();

                string query = "Select * From [dbo].[Locales] Where id = '" + en.ID + "' ";
                SqlCommand consulta = new SqlCommand(query, conection);
                SqlDataReader search = consulta.ExecuteReader();
                search.Read();
                en.ID = int.Parse(search["id"].ToString());
                en.Nombre = search["nombre"].ToString();
                en.Direccion = search["direccion"].ToString();
                en.Ciudad = search["ciudad"].ToString();
                en.Telefono = search["telefono"].ToString();
                en.Ubicacion = search["ubicacion"].ToString();
                en.Supervisor = search["supervisor"].ToString();

                search.Close();
                conection.Close();
            }
            catch (SqlException)
            {
                read = false;
             
            }
            catch (Exception)
            {
                read = false;
                
            }
            finally
            {
                if (conection.State == ConnectionState.Open)
                {
                    conection.Close();
                }
            }

            return read;
        }

        public bool UpdateLocal(ENLocal en) 
        {
            bool update = true;
            SqlConnection conection = null;
            conection = new SqlConnection(constring);

            try
            {

                conection.Open();

                string query = "UPDATE [dbo].[Local] SET nombre= '" + en.Nombre + "' ,telefono=" + en.Telefono + "WHERE id = '" + en.ID + "'";
                SqlCommand consulta = new SqlCommand(query, conection);
                consulta.ExecuteNonQuery();
                conection.Close();
            }
            catch (SqlException)
            {
                update = false;

            }
            catch (Exception)
            {
                update = false;

            }
            finally
            {
                if (conection.State == ConnectionState.Open)
                {
                    conection.Close();
                }
            }

            return update;
        }

        public bool DeleteLocal(ENLocal en) 
        {
            bool deleted = false;
            SqlConnection conection = new SqlConnection(constring);

            try
            {

                conection.Open();

                string query = "DELETE FROM [dbo].[Local] WHERE id = '" + en.ID + "'";
                SqlCommand consulta = new SqlCommand(query, conection);
                consulta.ExecuteNonQuery();

                deleted = true;
                conection.Close();
            }
            catch (SqlException)
            {
                deleted = false;

            }
            catch (Exception)
            {
                deleted = false;

            }
            finally
            {
                if (conection.State == ConnectionState.Open)
                {
                    conection.Close();
                }
            }

            return deleted;
        }

        public List<ENLocal> GetLocales()
        {
            List<ENLocal> locales = new List<ENLocal>();
            using(SqlConnection connection = new SqlConnection(constring))
            {
                connection.Open();
                using(SqlCommand command = new SqlCommand("SELECT * FROM Locales", connection))
                {
                    SqlDataReader reader = command.ExecuteReader();
                    while (reader.Read())
                    {
                        ENLocal local = new ENLocal();
                        local.ID = int.Parse(reader["id"].ToString());
                        local.Nombre = reader["nombre"].ToString();
                        local.Direccion = reader["direccion"].ToString();
                        local.Ciudad = reader["ciudad"].ToString();
                        local.Telefono = reader["telefono"].ToString();
                        local.Ubicacion = reader["ubicacion"].ToString();
                        locales.Add(local);
                    }
                }
            }

            return locales;
        }

        

        public void EliminarLocal(int id)
        {
            string connectionString = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;

            using (SqlConnection con = new SqlConnection(connectionString))
            {
                using (SqlCommand cmd = new SqlCommand("DELETE FROM Locales WHERE id = @id;", con))
                {
                    cmd.Parameters.AddWithValue("@id", id);
                    con.Open();
                    cmd.ExecuteNonQuery();
                    con.Close();
                }
            }
        }

        public List<ENPedido> obtenerNombreLocal(List<ENPedido> lista)
        {
            List<ENPedido> list = new List<ENPedido>();
            SqlConnection conection = new SqlConnection(constring);
            try
            {
                foreach (ENPedido item in lista)
                {
                    string comand = $"select * from Locales where id='{item.Idlocal}'";
                    SqlCommand com = new SqlCommand(comand, conection);
                    conection.Open();
                    SqlDataReader reader = com.ExecuteReader();
                    reader.Read();
                    list.Add(item);
                    conection.Close();

                }
            }
            catch (Exception) { }
           
            return list;
        }

        public DataTable ObtenerLocales()
        {
            DataTable dtLocales = new DataTable();

            string query = "SELECT id, nombre FROM Locales";

            using (SqlConnection connection = new SqlConnection(constring))
            {
                SqlCommand command = new SqlCommand(query, connection);
                connection.Open();

                SqlDataReader reader = command.ExecuteReader();
                if (reader.HasRows)
                {
                    dtLocales.Load(reader);
                }

                reader.Close();
            }

            return dtLocales;
        }

        public DataRow ObtenerLocalPorId(int localId)
        {
            DataTable tabla_local = new DataTable();

            string query = "SELECT nombre, direccion, ubicacion FROM Locales WHERE id = @LocalId";

            using (SqlConnection connection = new SqlConnection(constring))
            {
                SqlCommand command = new SqlCommand(query, connection);
                command.Parameters.AddWithValue("@LocalId", localId);
                connection.Open();

                SqlDataReader reader = command.ExecuteReader();
                if (reader.HasRows)
                {
                    tabla_local.Load(reader);
                }

                reader.Close();
            }

            if (tabla_local.Rows.Count > 0)
            {
                return tabla_local.Rows[0];
            }

            return null;
        }

    }
}
