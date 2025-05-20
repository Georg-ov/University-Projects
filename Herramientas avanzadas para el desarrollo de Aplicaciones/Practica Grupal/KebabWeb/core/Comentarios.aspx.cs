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
using System.Web.UI.WebControls;
using Library;

namespace KebabWeb.core
{
    public partial class Comentarios : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {
            if (LoginManager.IsLogged())
            {
                if (!IsPostBack)
                {
                    LoadMensajes();
                    LoadLocales();
                }
            }
            else
            {
                Response.Redirect("~/Default.aspx");
            }
        }
        private void LoadLocales()
        {
            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
            List<ENLocal> locales = cadLocalMensajeUsuario.GetLocales();

            ddlLocales.DataSource = locales;
            ddlLocales.DataBind();
        }
        private void LoadMensajes()
        {
            var cad = new CADLocalMensajeUsuario();
            var mensajesConUsuarios = cad.ObtenerMensajesConUsuarios();

            rptMensajes.DataSource = mensajesConUsuarios;
            rptMensajes.DataBind();
        }

        public string GetMensajePorId(object mensajeId)
        {
            if (mensajeId != null && mensajeId != DBNull.Value)
            {
                int id = Convert.ToInt32(mensajeId);

                CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
                string mensaje = cadLocalMensajeUsuario.GetMensajePorId(id);

                return mensaje;
            }

            return string.Empty;
        }

        protected bool MostrarBotonEliminar(object usuarioEmail)
        {
            string usuarioActual = LoginManager.GetUser().Email; 

            if (LoginManager.isAdmin() || (usuarioActual != null && usuarioEmail != null && usuarioEmail.ToString() == usuarioActual))
            {
                return true; 
            }

            return false; 
        }


        protected void AgregarComentario_Click(object sender, EventArgs e)
        {            
            LabelError.Visible = false;
            ENMensaje men = new ENMensaje();
            men.Mensaje = txtMensaje.Text;
            men.TipoMensaje = "Comentario";
            string usuarioEmail = LoginManager.GetUser().Email;
            int localId = Convert.ToInt32(ddlLocales.SelectedValue);

            if (string.IsNullOrEmpty(men.Mensaje) || string.IsNullOrWhiteSpace(men.Mensaje) )
            {
                LabelError.Text = "El campo 'Mensaje' es requerido";
                LabelError.Visible = true;
                return;
            }


            if (!men.CreateMensaje())
            {               
                return;
            }

            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();

            int valoracion;
            if (int.TryParse(ValoracionHidden.Value, out valoracion))
            {
                
                ENComentario comentarioExistente = new ENComentario();
                comentarioExistente.Id = men.Id;
                comentarioExistente.Valoracion = valoracion;

                if (comentarioExistente.UpdateComentario())
                {
                    
                    txtMensaje.Text = string.Empty;                    
                }
                
            }
            else
            {
                LabelError.Text = "Elige una valoración(estrellas)";
                LabelError.Visible = true;
                return;
            }
                                                                         
            cadLocalMensajeUsuario.AgregarLocalMensajeUsuario(localId, men.Id, usuarioEmail);

            LabelError.Text = string.Empty;
            LabelError.Visible = true;
            LoadMensajes();
            
        }
                
        protected void btnEliminarComentario_Click(object sender, EventArgs e)
        {
            Button btnEliminarComentario = (Button)sender;
            string comentarioId = btnEliminarComentario.CommandArgument;

            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
            cadLocalMensajeUsuario.EliminarMensajeYRegistrosRelacionados(comentarioId);

            LoadMensajes();
        }
    }
}
