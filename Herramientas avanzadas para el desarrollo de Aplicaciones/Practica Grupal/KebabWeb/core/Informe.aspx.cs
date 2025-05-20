using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Library;
using System.Data;

namespace KebabWeb.core
{
    public partial class Informe : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (LoginManager.isAdmin())
            {
                DataTable informe = new ENInforme().CreateInforme();

                Grafico.DataSource = informe;
                Grafico.Series["Series1"].XValueMember = "Local";
                Grafico.Series["Series1"].YValueMembers = "Total";
                Grafico.DataBind();
            }
            else
                Response.Redirect("~/Default.aspx");
        }
    }
}