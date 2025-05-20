using System;
using System.Collections.Generic;
using System.Linq;
using System.Text.RegularExpressions;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Library;

namespace KebabWeb.core
{
    public partial class Login : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (LoginManager.IsLogged())
            {
                Response.Redirect("~/Default.aspx");
            }
        }



        protected bool IsEmailValid(string email)
        {
            // Patrón de expresión regular para validar un email
            string pattern = @"^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$";

            // Comprobación utilizando la expresión regular
            Regex regex = new Regex(pattern);
            return regex.IsMatch(email);
        }

        private bool comprobarEmailLogin(ENUsuario en)
        {
            if (!en.inicioSesion())
            {
                return false;
            }
            LoginManager.Login(en);
            return true;
        }

        protected void LoginButton_Click(object sender, EventArgs e)
            {

                string email = EmailTextBox.Text;
                string password = PasswordTextBox.Text;


            if (IsEmailValid(email))
            {
                ENUsuario en = new ENUsuario(email, password, "", "");
                if (comprobarEmailLogin((ENUsuario)en))
                    Response.Redirect("Admin.aspx");
                else
                    return;
            }
        }

        protected bool comprobarNombreCorrecto(string nombre)
        {
            string patron = @"^[a-zA-Z\s]+$";
            return System.Text.RegularExpressions.Regex.IsMatch(nombre, patron);
        }



        protected void RegisterButton_Click(object sender, EventArgs e)
        {
            string email = EmailTextBox.Text;
            string password = PasswordTextBox2.Text;
            string dni = dniTextBox.Text;
            string nombre = NameTextBox.Text;

            if (IsEmailValid(email))
            {
                if(comprobarNombreCorrecto(nombre))
                {
                    ENUsuario en = new ENUsuario(email, password, nombre, dni);
                    en.TipoUsuario = "Cliente";
                    if (en.createUsuario())
                    {
                        Response.Redirect("~/Default.aspx");
                    }
                    else
                    {
                        EmailTextBox.Text = string.Empty;
                        dniTextBox.Text = string.Empty;
                    }
                }
                else
                    NameTextBox.Text = string.Empty;
            }
            else
            { EmailTextBox.Text = string.Empty;}

        }
    }
}