using Library;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;

namespace KebabWeb.core
{
    public partial class MiCuenta : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (LoginManager.IsLogged())
            {

                if (LoginManager.isAdmin())
                {
                    if (IsPostBack)
                    {
                        System.Diagnostics.Debug.WriteLine("PostBack occurred");
                    }
                }
                if (!LoginManager.isEmp() && !LoginManager.isAdmin())
                {
                    List<ENPedido> locales = new List<ENPedido>();
                    locales = new ENPedido().getPedidos(LoginManager.GetUser());
                    rptPedidos.DataSource = locales;
                    rptPedidos.DataBind();
                }

                if (!IsPostBack)
                {
                    LoadPedidos();
                }
            }
            else
                Response.Redirect("~/Default.aspx");

        }


        protected void LoadPedidos()
        {
            List<ENPedido> locales = new ENPedido().getPedidos(LoginManager.GetUser());
            rptPedidos.DataSource = locales;
            rptPedidos.DataBind();
        }

        protected void btnCerrarSesion_Click(object sender, EventArgs e)
        {
            LoginManager.logOut();
        }

        protected void btnChangePassword_Click(object sender, EventArgs e)
        {
            string oldPassword = typePasswordOld.Text;
            string newPassword = typePasswordNew.Text;
            string repeatNewPassword = typePasswordNewR.Text;


            if (!newPassword.Equals(repeatNewPassword))
            {
                lblErrorMessage.Text = "Las nuevas contraseñas no coinciden.";
                return;
            }

            
            Library.ENUsuario usuario = new Library.ENUsuario();

       
            usuario.Email = LoginManager.GetUser().Email;
            usuario.Contraseña = oldPassword;          

            // Intenta cambiar la contraseña
            bool success = usuario.changePassword(oldPassword, newPassword);

            if (success)
            {

            }
            else
            {

            }
        }

        protected void btnCerrarSesion(object sender, EventArgs e)
        {
            LoginManager.logOut();
            Response.Redirect("/Default.aspx");
        }

        protected void btnChangeEmail_Click(object sender, EventArgs e)
        {
            string newPassword = typePasswordEmailChange.Text;
            string newEmail = typeEmail.Text;

            
            ENUsuario usuario = new ENUsuario();

            usuario.Email = LoginManager.GetUser().Email;
            usuario.Contraseña = newPassword;

            // Intenta cambiar el correo electrónico
            bool success = usuario.changeEmail(usuario.Contraseña, newEmail);

            if (success)
            {

            }
            else
            {

            }
        }

    }
}