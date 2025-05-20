using Library;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Runtime.Remoting.Messaging;
using System.Web;
using System.Web.UI;
using System.Web.UI.HtmlControls;
using System.Web.UI.WebControls;

namespace KebabWeb
{
    public partial class Kebab : System.Web.UI.MasterPage
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (!LoginManager.IsLogged()) {
                ContentPlaceHolder1.ResolveUrl("~/Default.aspx");
            }

            else
            {
                inicio_sesion.Text = $"Bienvenido, {LoginManager.GetUser().Nombre}";
                inicio_sesion.NavigateUrl = "~/core/MiCuenta.aspx";
                if (LoginManager.isAdmin())
                {
                    ContentPlaceHolder1.ResolveUrl("Admin.aspx");
                    boton1.Text = "Informes";
                    boton1.NavigateUrl = "/core/Informe.aspx";
                    boton2.Text = "Incidencias";
                    boton2.NavigateUrl = "/core/Incidencias.aspx";
                    boton3.Text = "Locales";
                    boton3.NavigateUrl = "~/core/Locales.aspx";
                    boton4.Text = "Panel admin";
                    boton4.NavigateUrl = "~/core/Admin.aspx";
                    boton5.Visible = false;
                }
                else if (LoginManager.isEmp())
                {
                    ContentPlaceHolder1.ResolveUrl("Empleado.aspx");
                    boton1.Visible = false;
                    boton2.Text = "Incidencias";
                    boton2.NavigateUrl = "~/core/Incidencias.aspx";
                    boton3.Visible = false;
                    boton4.Visible = false;
                    boton5.Visible = false;
                }
                else
                {
                    ContentPlaceHolder1.ResolveUrl("~/Default.aspx");
                }
            }

        }

        void Session_End(object sender, EventArgs e)
        {
            LoginManager.logOut();
        }

    }
}