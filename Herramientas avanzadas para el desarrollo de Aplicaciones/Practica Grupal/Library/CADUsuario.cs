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
using System.Net;

namespace Library
{
    class CADUsuario
    {
        private string constring;
        public CADUsuario()
        {
            constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ConnectionString;
        }

        public bool createUsuario(ENUsuario eu)
        {

            bool created = false;
            using (SqlConnection con = new SqlConnection(constring))
            {
                try
                {
                    con.Open();
                    SqlCommand command = new SqlCommand($"INSERT INTO Usuario (email, nombre, contraseña, dni, tipo_usuario) VALUES ({eu.Email}, {eu.Nombre}, {eu.Contraseña}, {eu.Dni}, {eu.TipoUsuario})", con);
                    int rowsAffected = command.ExecuteNonQuery();
                    int rowsAffected2 = 0;
                    if (eu.TipoUsuario.Equals("Empleado"))
                    {
                        SqlCommand command2 = new SqlCommand($"insert into Empleado(email, salario) values ({eu.Email}, 0)", con) ;
                        rowsAffected2 = command2.ExecuteNonQuery();
                        
                    }
                    else if(eu.TipoUsuario.Equals("Cliente")){
                        SqlCommand command2 = new SqlCommand($"insert into Cliente(email) values ({eu.Email})", con);
                        rowsAffected2 = command2.ExecuteNonQuery();
                    }
                    else
                    {
                        rowsAffected2 = 1;
                    }

                    if (rowsAffected > 0 && rowsAffected2 > 0)
                        created = true;
                }
                catch (SqlException)
                {
                    Console.WriteLine("Error al crear el usuario");
                }
                catch (Exception)
                {
                    Console.WriteLine("Error al crear el usuario");
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

        public bool updateUsuario(ENUsuario eu)
        {
            bool flag = false;
            SqlConnection con = new SqlConnection(constring);

            try
            {
                con.Open();
                SqlCommand consulta = new SqlCommand($"UPDATE [dbo].[Usuario] SET nombre = '{eu.Nombre}' and contraseña = '{eu.Contraseña}' WHERE email = '" + eu.Email + "'", con);
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

        //BORRAR USUARIO
        public bool deleteUsuario(ENUsuario eu)
        {
            SqlConnection con = new SqlConnection(constring);
            bool deleted = false;

            try
            {
                string consulta = $"delete from [dbo].[Usuario] where email = '{eu.Email}'";
                SqlCommand com = new SqlCommand(consulta, con);

                con.Open();
                com.ExecuteNonQuery();
                deleted = true;

                if (eu.TipoUsuario.Equals("Empleado"))
                {
                    SqlCommand consultaaa = new SqlCommand($"update table Locales set supervisor = 'null' where supervisor = {eu.Email}", con);
                    consultaaa.ExecuteNonQuery();
                }
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
                if(con != null)
                {
                    con.Close();
                }
            }

            return deleted;
        }

        public bool readUsuario(ENUsuario eu)
        {
            bool read = false;
            SqlConnection con = new SqlConnection(constring);
            DataSet create = new DataSet();
            DataTable dt = new DataTable();

            try
            {
                SqlDataAdapter d = new SqlDataAdapter("Select * from Usuario", con);
                d.Fill(create, "usuario");
                dt = create.Tables["usuario"];

                for (int i = 0; i < dt.Rows.Count && !read; i++)
                {
                    if (dt.Rows[i]["email"].ToString().Equals(eu.Email))
                    {
                        eu.Nombre = dt.Rows[i]["nombre"].ToString();
                        eu.Contraseña = dt.Rows[i]["contraseña"].ToString();
                        eu.Dni = dt.Rows[i]["dni"].ToString();
                        eu.TipoUsuario = dt.Rows[i]["tipo_usuario"].ToString();
                        read = true;
                    }
                    else if (dt.Rows[i]["dni"].ToString().Equals(eu.Dni))
                    {
                        eu.Nombre = dt.Rows[i]["nombre"].ToString();
                        eu.Contraseña = dt.Rows[i]["contraseña"].ToString();
                        eu.Email = dt.Rows[i]["email"].ToString();
                        eu.TipoUsuario = dt.Rows[i]["tipo_usuario"].ToString();
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
            finally
            {
                con.Close();
            }
            return read;
        }

        public bool inicioSesion(ENUsuario intro)
        {
            bool read = false;
            SqlConnection con = new SqlConnection(constring);
            DataSet create = new DataSet();
            DataTable dt = new DataTable();

            try
            {
                SqlDataAdapter d = new SqlDataAdapter("Select * from Usuario", con);
                d.Fill(create, "usuario");
                dt = create.Tables["usuario"];

                for (int i = 0; i < dt.Rows.Count && !read; i++)
                {
                    if (dt.Rows[i]["email"].ToString().Equals(intro.Email))
                    {
                        if (dt.Rows[i]["contraseña"].ToString().Equals(intro.Contraseña))
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
            finally { con.Close(); }
            return read;

        }

        public bool isEmp(ENUsuario intro)
        {
            bool read = false;
            SqlConnection con = new SqlConnection(constring);
            DataSet create = new DataSet();
            DataTable dt = new DataTable();

            try
            {
                SqlDataAdapter d = new SqlDataAdapter("Select * from Usuario", con);
                d.Fill(create, "usuario");
                dt = create.Tables["usuario"];

                for (int i = 0; i < dt.Rows.Count && !read; i++)
                {
                    if (dt.Rows[i]["email"].ToString().Equals(intro.Email))
                    {
                        if (dt.Rows[i]["tipo_usuario"].ToString().Equals("Empleado"))
                            read = true;
                        else
                            read = false;
                    }
                }

            }
            catch (SqlException)
            {

            }
            catch (Exception)
            {

            }
            finally { con.Close(); }
            return read;
        }

        public bool changePassword(ENUsuario eu, string oldPassword, string newPassword)
        {
            bool changed = false;
            SqlConnection con = new SqlConnection(constring);

            try
            {
                // First verify that the old password is correct
                if (inicioSesion(eu))
                {
                    con.Open();
                    SqlCommand consulta = new SqlCommand($"UPDATE [dbo].[Usuario] SET contraseña = @NewPassword WHERE email = @Email AND contraseña = @OldPassword", con);
                    consulta.Parameters.AddWithValue("@Email", eu.Email);
                    consulta.Parameters.AddWithValue("@OldPassword", oldPassword);
                    consulta.Parameters.AddWithValue("@NewPassword", newPassword);
                    int rowsAffected = consulta.ExecuteNonQuery();
                    if (rowsAffected > 0)
                        changed = true;
                }
            }
            catch (SqlException)
            {
                Console.WriteLine("Error al cambiar la contraseña");
            }
            catch (Exception)
            {
                Console.WriteLine("Error al cambiar la contraseña");
            }
            finally
            {
                if (con != null)
                {
                    con.Close();
                }
            }

            return changed;
        }

        public List<ENUsuario> getEmpleados()
        {
            List<ENUsuario> empleado = new List<ENUsuario>();
            SqlConnection connection = new SqlConnection(constring);

            connection.Open();
            SqlCommand command = new SqlCommand("select * from usuario where email in (SELECT email FROM Empleado where trabaja_en_local_id is null)", connection);

            SqlDataReader reader = command.ExecuteReader();
            while (reader.Read())
            {
                ENUsuario u = new ENUsuario();
                u.Nombre = reader["nombre"].ToString();
                u.Dni = reader["dni"].ToString();
                empleado.Add(u);
            }


            return empleado;
        }

        public bool changeEmail(string currentEmail, string password, string newEmail)
        {
            try
            {
                using (SqlConnection conn = new SqlConnection(constring))
                {
                    // Comprueba si la contraseña proporcionada coincide con la registrada para el correo electrónico actual
                    string queryCheck = "SELECT contraseña FROM Usuario WHERE Email = @currentEmail";

                    using (SqlCommand commandCheck = new SqlCommand(queryCheck, conn))
                    {
                        commandCheck.Parameters.AddWithValue("@currentEmail", currentEmail);

                        conn.Open();
                        string registeredPassword = (string)commandCheck.ExecuteScalar();

                        if (registeredPassword != password)
                        {
                            // La contraseña proporcionada no coincide con la registrada
                            return false;
                        }
                    }

                    // Si la contraseña coincide, procede a cambiar el correo electrónico
                    string queryUpdate = "UPDATE Usuario SET Email = @newEmail WHERE Email = @currentEmail";

                    using (SqlCommand commandUpdate = new SqlCommand(queryUpdate, conn))
                    {
                        commandUpdate.Parameters.AddWithValue("@newEmail", newEmail);
                        commandUpdate.Parameters.AddWithValue("@currentEmail", currentEmail);

                        int result = commandUpdate.ExecuteNonQuery();

                        // Comprueba si la consulta ha afectado a alguna fila
                        if (result > 0)
                            return true;
                        else
                            return false;
                    }
                }
            }
            catch (Exception e)
            {
                // Maneja la excepción como sea apropiado en tu caso
                return false;
            }
        }

        public bool setLocalSupervisor(ENUsuario en, int id)
        {
            SqlConnection connection = new SqlConnection(constring);
            SqlCommand command = new SqlCommand($"update Empleado set trabaja_en_local_id = {id} where email={en.Email}; update Locales set supervisor = {en.Email} where id = {id}", connection);
            try
            {
                connection.Open();
                command.ExecuteNonQuery();
            }catch (Exception e)
            {
                return false;
            }finally { connection.Close(); }
            return true;
        }

        public bool setSalario(ENUsuario en, float salario)
        {
            SqlConnection connection = new SqlConnection(constring);
            SqlCommand command = new SqlCommand($"update Empleado set salario = {salario} where email={en.Email}", connection);
            try
            {
                connection.Open();
                command.ExecuteNonQuery();
            }
            catch (Exception e)
            {
                return false;
            }
            finally { connection.Close(); }
            return true;
        }

    }
}
