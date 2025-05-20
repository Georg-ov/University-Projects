using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Library;

namespace KebabWeb.core
{
    public partial class Incidencias : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (LoginManager.isAdmin() || LoginManager.isEmp())
            {
                if (!IsPostBack)
                {
                    ENIncidencia enIncidencias = new ENIncidencia();
                    rptIncidencias.DataSource = enIncidencias.GetIncidencias();
                    rptIncidencias.DataBind();
                }
            }
            else
                Response.Redirect("~/Default.aspx");
        }

        protected void btnEliminar_Click(object sender, EventArgs e)
        {
            ENIncidencia enIncidencias = new ENIncidencia();
            Button btn = (Button)sender;
            int mensaje_id = Convert.ToInt32(btn.CommandArgument);
            enIncidencias.EliminarIncidencia(mensaje_id);
            // Recargar el Repeater después de eliminar la incidencia
            rptIncidencias.DataSource = enIncidencias.GetIncidencias();
            rptIncidencias.DataBind();
        }
    }
}