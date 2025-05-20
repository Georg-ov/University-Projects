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
    public class CADAdmin
    {
        private string constring;

        public CADAdmin()
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;
        }

        public bool createAdmin(ENAdmin admin)
        {
            bool created = false;
            using (SqlConnection con = new SqlConnection(constring))
            {
                try
                {
                    con.Open();
                    SqlCommand command = new SqlCommand("INSERT INTO Administrador (email) VALUES (@Email)", con);
                    command.Parameters.AddWithValue("@Email", admin.Email);
                    int rowsAffected = command.ExecuteNonQuery();

                    if (rowsAffected > 0)
                        created = true;
                }
                catch (SqlException)
                {
                    Console.WriteLine("Error al añadir administrador");
                }
                catch (Exception)
                {
                    Console.WriteLine("Error al añadir administrador");
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

        public bool readAdmin(ENAdmin admin)
        {
            bool read = false;
            SqlConnection con = new SqlConnection(constring);
            DataSet create = new DataSet();
            DataTable dt = new DataTable();

            try
            {
                SqlDataAdapter d = new SqlDataAdapter("Select * from Administrador", con);
                d.Fill(create, "Administrador");
                dt = create.Tables["Administrador"];

                for (int i = 0; i < dt.Rows.Count && !read; i++)
                {
                    if (dt.Rows[i]["email"].ToString().Equals(admin.Email))
                    {
                        admin.Email = dt.Rows[i]["email"].ToString();
                        read = true;
                    }
                }

            }
            catch (SqlException)
            {

            }
            catch (Exception)
            {

            }
            finally {
                if (con != null) { con.Close(); }
            }
            return read;
        }

        public bool updateAdmin(ENAdmin admin, string nuevo)
        {
        bool flag = false;
        SqlConnection con = new SqlConnection(constring);

        try
        {
            con.Open();
            SqlCommand consulta = new SqlCommand($"UPDATE [dbo].[Administrador] SET nombre = '{nuevo}' WHERE email = '" + admin.Email + "'", con);
            consulta.ExecuteNonQuery();
            flag = true;
        }
        catch (SqlException)
        {
            Console.WriteLine("Error al actualizar los datos");
        }
        catch (Exception)
        {
            Console.WriteLine("Error al actualizar los datos");
        }
        finally
        {
            if (con != null)
            {
                con.Close();
            }
        }

        return flag;
    }

        public bool deleteAdmin(ENAdmin admin)
        {
            SqlConnection con = new SqlConnection(constring);
            bool deleted = false;

            try
            {
                string consulta = $"delete from [dbo].[Administrador] where email = '{admin.Email}'";
                SqlCommand com = new SqlCommand(consulta, con);

                con.Open();
                com.ExecuteNonQuery();
                deleted = true;
            }
            catch (SqlException)
            {
                Console.WriteLine("Error. No se pudo eliminar el admin.");
            }
            catch (Exception)
            {
                Console.WriteLine("Error. No se puedo eliminar el admin.");
            }
            finally
            {
                if (con != null)
                {
                    con.Close();
                }
            }

            return deleted;
        }
    }
}
