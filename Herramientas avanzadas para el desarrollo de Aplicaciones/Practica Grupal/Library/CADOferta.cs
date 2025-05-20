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
    public class CADOferta
    {

        private String constring;
        public CADOferta()
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ToString();
        }

        public bool CreateOferta(ENOferta en)
        {
            bool created = false;
            SqlConnection conection = new SqlConnection(constring);
            DataSet bdLocal = new DataSet();

            try
            {
                SqlDataAdapter adapter = new SqlDataAdapter("Select * from Oferta", conection);
                adapter.Fill(bdLocal, "Oferta");
                DataTable table = new DataTable();
                table = bdLocal.Tables["Oferta"];
                DataRow fila = table.NewRow();
                fila[0] = en.Id;
                fila[1] = en.Precio;
                fila[2] = en.Codigo;
                table.Rows.Add(fila);
                SqlCommandBuilder cbuilder = new SqlCommandBuilder(adapter);
                int filaInsertada = adapter.Update(bdLocal, "Oferta");
                if (filaInsertada == 1)
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

        public bool ReadOferta(ENOferta en)
        {
            bool read = true;
            SqlConnection conection = null;
            conection = new SqlConnection(constring);

            try
            {
                conection.Open();

                string query = "Select * From [dbo].[Oferta] Where id = '" + en.Id + "' ";
                SqlCommand consulta = new SqlCommand(query, conection);
                SqlDataReader search = consulta.ExecuteReader();
                search.Read();

                if (search["id"].Equals(en.Id))
                {
                    en.Precio = float.Parse(search["precio"].ToString());
                    en.Codigo = search["codigo"].ToString();
                }
                else read = false;

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

        public bool UpdateOferta(ENOferta en)
        {
            bool updated = false;
            SqlConnection conection = null;
            conection = new SqlConnection(constring);
            DataSet dataSet = new DataSet();

            try
            {
                SqlDataAdapter adapter = new SqlDataAdapter("SELECT * FROM Oferta", conection);
                SqlCommandBuilder builder = new SqlCommandBuilder(adapter);
                adapter.Fill(dataSet, "Oferta");
                DataTable table = dataSet.Tables["Oferta"];
                DataRow[] rows = table.Select($"Id = {en.Id}");

                if (rows.Length > 0)
                {
                    DataRow row = rows[0];
                    row["Precio"] = en.Precio;
                    row["Codigo"] = en.Codigo;

                    int filaModificada = adapter.Update(dataSet, "Oferta");

                    if (filaModificada == 1)
                    {
                        updated = true;
                    }
                }
            }
            catch (SqlException)
            {
                updated = false;
            }
            catch (Exception)
            {
                updated = false;
            }
            finally
            {
                if (conection.State == ConnectionState.Open)
                {
                    conection.Close();
                }
            }

            return updated;
        }


        public bool DeleteOferta(ENOferta en)
        {
            bool deleted = false;
            SqlConnection conection = null;
            conection = new SqlConnection(constring);
            DataSet dataSet = new DataSet();

            try
            {
                SqlDataAdapter adapter = new SqlDataAdapter("SELECT * FROM Oferta", conection);
                SqlCommandBuilder builder = new SqlCommandBuilder(adapter);
                adapter.Fill(dataSet, "Oferta");
                DataTable table = dataSet.Tables["Oferta"];
                DataRow[] rows = table.Select($"Id = {en.Id}");

                if (rows.Length > 0)
                {
                    rows[0].Delete();
                    int filaEliminada = adapter.Update(dataSet, "Oferta");

                    if (filaEliminada == 1)
                    {
                        deleted = true;
                    }
                }
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


        public List<ENOferta> GetOfertas()
        {
            List<ENOferta> ofertas = new List<ENOferta>();

            SqlConnection conection = new SqlConnection(constring);
            {
                try
                {
                    conection.Open();
                    string query = "Select * From [dbo].[Oferta]";
                    SqlCommand command = new SqlCommand(query, conection);

                    SqlDataReader search = command.ExecuteReader();

                    while (search.Read())
                    {
                        ENOferta oferta = new ENOferta();
                        oferta.Id = int.Parse(search["id"].ToString());
                        oferta.Precio = float.Parse(search["precio"].ToString());
                        oferta.Codigo = search["codigo"].ToString();
                        ofertas.Add(oferta);
                    }
                }
                catch (Exception ex)
                {
                    throw ex;
                }
                finally
                {
                    if (conection.State == ConnectionState.Open)
                    {
                        conection.Close();
                    }
                }
            }

            return ofertas;
        }
    }
}
