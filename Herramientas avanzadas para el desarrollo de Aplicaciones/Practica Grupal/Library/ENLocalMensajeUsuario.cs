using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
    public class ENLocalMensajeUsuario
    {
        private int localId { get; set; }
        private int mensajeId { get; set; }
        private string usuarioEmail { get; set; }

        private ENMensaje mensaje { get; set; }

        public int LocalId
        {
            get { return localId; }
            set { localId = value; }
        }

        public int MensajeId
        {
            get { return mensajeId; }
            set { mensajeId = value; }
        }

        public string UsuarioEmail
        {
            get { return usuarioEmail; }
            set { usuarioEmail = value; }
        }

        public ENMensaje Mensaje
        {
            get { return mensaje; }
            set { mensaje = value; }
        }

        public ENLocalMensajeUsuario()
        {
            localId = 0;
            mensajeId = 0;
            usuarioEmail = "";
        }

        public ENLocalMensajeUsuario(int localId, int mensajeId, string usuarioEmail)
        {
            this.localId = localId;
            this.mensajeId = mensajeId;
            this.usuarioEmail = usuarioEmail;
        }

        public bool CreateLocalMensajeUsuario()
        {
            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
            return cadLocalMensajeUsuario.CreateLocalMensajeUsuario(this);
        }

        public bool ReadLocalMensajeUsuario()
        {
            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
            return cadLocalMensajeUsuario.ReadLocalMensajeUsuario(this);
        }

        public bool UpdateLocalMensajeUsuario()
        {
            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
            return cadLocalMensajeUsuario.UpdateLocalMensajeUsuario(this);
        }

        public bool DeleteLocalMensajeUsuario()
        {
            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
            return cadLocalMensajeUsuario.DeleteLocalMensajeUsuario(this);
        }

        public List<ENLocalMensajeUsuario> GetAllComentarios()
        {
            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
            return cadLocalMensajeUsuario.GetAllComentarios();
        }

        public string GetMensajePorId(int mensajeId)
        {
            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
            return cadLocalMensajeUsuario.GetMensajePorId(mensajeId);
        }

        public List<ENLocal> GetLocales()
        {
            CADLocalMensajeUsuario cADLocalMensajeUsuario = new CADLocalMensajeUsuario();
            return cADLocalMensajeUsuario.GetLocales();

        }

        public void AgregarLocalMensajeUsuario(int localid, int mensajeId, string usuarioEmail)
        {
            CADLocalMensajeUsuario cadLocal = new CADLocalMensajeUsuario();
            cadLocal.AgregarLocalMensajeUsuario(localid, mensajeId, usuarioEmail);
        }

        public List<dynamic> ObtenerMensajesConUsuarios()
        {
            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
            return cadLocalMensajeUsuario.ObtenerMensajesConUsuarios();
        }

        public void EliminarMensajeYRegistrosRelacionados(string mensajeId)
        {
            CADLocalMensajeUsuario cADLocalMensajeUsuario = new CADLocalMensajeUsuario();
            cADLocalMensajeUsuario.EliminarMensajeYRegistrosRelacionados(mensajeId);
        }
    }
}
