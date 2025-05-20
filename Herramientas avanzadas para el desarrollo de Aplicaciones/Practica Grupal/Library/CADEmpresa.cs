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
    class CADEmpresa
    {

        private string constring;

        public CADEmpresa()
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;
        }

        public bool CreateEmpresa(ENEmpresa intro)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "INSERT INTO Empresa (CIF, nombre, direccion, ciudad, telefono, email) VALUES (@CIF, @nombre, @direccion, @ciudad, @telefono, @email)";
                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@CIF", new string(intro.Cif));
                    cmd.Parameters.AddWithValue("@nombre", intro.Nombre);
                    cmd.Parameters.AddWithValue("@direccion", intro.Direccion);
                    cmd.Parameters.AddWithValue("@ciudad", intro.Ciudad);
                    cmd.Parameters.AddWithValue("@telefono", new string(intro.Telefono));
                    cmd.Parameters.AddWithValue("@email", intro.Email);

                    conn.Open();
                    int rowsAffected = cmd.ExecuteNonQuery();

                    return rowsAffected > 0;
                }
            }
        }

        public bool ReadEmpresa(ENEmpresa intro)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "SELECT * FROM Empresa WHERE CIF = @CIF";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@CIF", new string(intro.Cif));

                    conn.Open();
                    using (SqlDataReader reader = cmd.ExecuteReader())
                    {
                        if (reader.Read())
                        {
                            intro.Nombre = reader["nombre"].ToString();
                            intro.Direccion = reader["direccion"].ToString();
                            intro.Ciudad = reader["ciudad"].ToString();
                            intro.Telefono = reader["telefono"].ToString().ToCharArray();
                            intro.Email = reader["email"].ToString();
                            return true;
                        }
                    }
                }
            }
            return false;
        }

        public bool UpdateEmpresa(ENEmpresa intro)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "UPDATE Empresa SET nombre = @nombre, direccion = @direccion, ciudad = @ciudad, telefono = @telefono, email = @email WHERE CIF = @CIF";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@CIF", new string(intro.Cif));
                    cmd.Parameters.AddWithValue("@nombre", intro.Nombre);
                    cmd.Parameters.AddWithValue("@direccion", intro.Direccion);
                    cmd.Parameters.AddWithValue("@ciudad", intro.Ciudad);
                    cmd.Parameters.AddWithValue("@telefono", new string(intro.Telefono));
                    cmd.Parameters.AddWithValue("@email", intro.Email);

                    conn.Open();
                    int rowsAffected = cmd.ExecuteNonQuery();

                    return rowsAffected > 0;
                }
            }
        }

        public bool DeleteEmpresa(ENEmpresa intro)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "DELETE FROM Empresa WHERE CIF = @CIF";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@CIF", new string(intro.Cif));

                    conn.Open();
                    int rowsAffected = cmd.ExecuteNonQuery();

                    return rowsAffected > 0;
                }
            }
        }
    }
}
