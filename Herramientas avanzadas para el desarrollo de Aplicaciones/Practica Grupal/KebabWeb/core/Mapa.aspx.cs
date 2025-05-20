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
using Library;

namespace KebabWeb.core
{
    public partial class Mapa : System.Web.UI.Page
    {
        private String constring;
        protected void Page_Load(object sender, EventArgs e)
        {
            if (!IsPostBack)
            {
                LoadLocales();
                CargarPrimerLocal();
            }
        }

        private void LoadLocales()
        {
            CADLocal local = new CADLocal();
            Locales.DataSource = local.ObtenerLocales();
            Locales.DataTextField = "nombre";
            Locales.DataValueField = "id";
            Locales.DataBind();
        }
        private void CargarPrimerLocal()
        {
            int primerLocalId = Convert.ToInt32(Locales.SelectedValue);

            CADLocal cadLocal = new CADLocal();
            var local = cadLocal.ObtenerLocalPorId(primerLocalId);

            if (local != null)
            {
                Nombre_Local.Text = local["nombre"].ToString();
                Direccion_Local.Text = local["direccion"].ToString();
                Ubicación_Local.Attributes["src"] = local["ubicacion"].ToString();
            }
        }


        protected void Cargar_Local(object sender, EventArgs e)
        {
            int localId = Convert.ToInt32(Locales.SelectedValue);

            CADLocal cadLocal = new CADLocal();
            var local = cadLocal.ObtenerLocalPorId(localId);

            if (local != null)
            {
                Nombre_Local.Text = local["nombre"].ToString();
                Direccion_Local.Text = local["direccion"].ToString();
                Ubicación_Local.Attributes["src"] = local["ubicacion"].ToString();
            }
        }
    }
}