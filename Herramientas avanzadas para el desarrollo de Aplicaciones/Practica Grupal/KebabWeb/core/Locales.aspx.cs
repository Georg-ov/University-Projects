using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Library;



namespace KebabWeb.core
{
    public partial class Locales : System.Web.UI.Page
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

                    if (!IsPostBack)
                    {
                        LoadLocales();
                    }

                    List<ENLocal> locales = new CADLocal().GetLocales();
                    rptLocales.DataSource = locales;
                    rptLocales.DataBind();
                }
                if (!LoginManager.isEmp() && !LoginManager.isAdmin())
                    Response.Redirect("~/Default.aspx");


            }
            else
                Response.Redirect("/Default.aspx");

        }


        private void LoadLocales()
        {
            CADLocal cadLocal = new CADLocal();
            List<ENLocal> locales = cadLocal.GetLocales();

        }

        protected void btnEliminar_Click(object sender, EventArgs e)
        {
            ENLocal enl = new ENLocal();
            Button btn = (Button)sender;
            int id = Convert.ToInt32(btn.CommandArgument);
            enl.EliminarLocal(id);

            // Recarga los locales para reflejar la eliminación
            rptLocales.DataSource = enl.GetLocales();
            rptLocales.DataBind();
            
        }




    }
}