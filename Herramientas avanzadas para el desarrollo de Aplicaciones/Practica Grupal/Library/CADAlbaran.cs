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
    class CADAlbaran
    {
        private string constring;

        public CADAlbaran()
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;
        }

        public bool CreateAlbaran(ENAlbaran albaran)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                conn.Open();

                foreach (ENPedido ped in albaran.IdP)
                {
                    string sql = "INSERT INTO Albaran (idA, fecha, importe, idP) VALUES (@idA, @fecha, @importe, @idP)";

                    using (SqlCommand cmd = new SqlCommand(sql, conn))
                    {
                        cmd.Parameters.AddWithValue("@idA", albaran.IdA);
                        cmd.Parameters.AddWithValue("@idP", ped.CodP);
                        cmd.Parameters.AddWithValue("@fecha", albaran.Fecha);
                        cmd.Parameters.AddWithValue("@importe", albaran.Importe);

                        int rowsAffected = cmd.ExecuteNonQuery();

                        if (rowsAffected == 0)
                        {
                            conn.Close();
                            return false;
                        }
                    }
                }

                conn.Close();
            }
            return true;
        }

        public bool ReadAlbaran(ENAlbaran albaran)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "SELECT * FROM Albaran WHERE idA = @idA AND idP = @idP";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@idA", albaran.IdA);
                    cmd.Parameters.AddWithValue("@idP", albaran.IdP);

                    conn.Open();
                    using (SqlDataReader reader = cmd.ExecuteReader())
                    {
                        if (reader.Read())
                        {
                            albaran.Fecha = Convert.ToDateTime(reader["fecha"]);
                            albaran.Importe = float.Parse((reader["importe"]).ToString());
                            return true;
                        }
                    }
                }
            }

            return false;
        }

        public bool UpdateAlbaran(ENAlbaran albaran)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "UPDATE Albaran SET fecha = @fecha, importe = @importe WHERE idA = @idA AND idP = @idP";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@fecha", albaran.Fecha);
                    cmd.Parameters.AddWithValue("@importe", albaran.Importe);
                    cmd.Parameters.AddWithValue("@idA", albaran.IdA);
                    cmd.Parameters.AddWithValue("@idP", albaran.IdP);

                    conn.Open();
                    int rowsAffected = cmd.ExecuteNonQuery();

                    return rowsAffected > 0;
                }
            }
        }

        public bool DeleteAlbaran(ENAlbaran albaran)
        {
            using (SqlConnection conn = new SqlConnection(constring))
            {
                string sql = "DELETE FROM Albaran WHERE idA = @idA AND idP = @idP";

                using (SqlCommand cmd = new SqlCommand(sql, conn))
                {
                    cmd.Parameters.AddWithValue("@idA", albaran.IdA);
                    cmd.Parameters.AddWithValue("@idP", albaran.IdP);

                    conn.Open();
                    int rowsAffected = cmd.ExecuteNonQuery();

                    return rowsAffected > 0;
                }
            }
        }
    }
}
