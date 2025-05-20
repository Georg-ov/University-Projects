using System;
using System.Collections.Generic;
using System.Linq;
using System.Text.RegularExpressions;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using Library;

namespace KebabWeb
{
    public partial class Default : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (LoginManager.IsLogged())
            {
                ComentariosButton.Visible = true;
            }
        }

        protected void ComentariosButton_Click(object sender, EventArgs e)
        {
            Response.Redirect("/core/Comentarios.aspx");
        }


    }
}