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

namespace Library
{
    public class ENMensaje
    {
        private int id { get; set; }
        private string mensaje { get; set; }
        private string tipoMensaje { get; set; }
        private DateTime fecha { get; set; }

        public int Id
        {
            get { return id; }
            set { id = value; }
        }

        public string Mensaje
        {
            get { return mensaje; }
            set { mensaje = value; }
        }

        public string TipoMensaje
        {
            get { return tipoMensaje; }
            set { tipoMensaje = value; }
        }

        public DateTime Fecha
        {
            get { return fecha; }
            set { fecha = value; }
        }

        public ENMensaje()
        {
            id = 0;
            mensaje = "";
            tipoMensaje = "";
            fecha = DateTime.Now;
        }

        public ENMensaje(int id, string mensaje, string tipoMensaje, DateTime fecha)
        {
            this.id = id;
            this.mensaje = mensaje;
            this.tipoMensaje = tipoMensaje;
            this.fecha = fecha;
        }

        public bool CreateMensaje()
        {
            CADMensaje cadMensaje = new CADMensaje();
            return cadMensaje.CreateMensaje(this);
        }

        public bool ReadMensaje()
        {
            CADMensaje cadMensaje = new CADMensaje();
            return cadMensaje.ReadMensaje(this);
        }

        public bool UpdateMensaje()
        {
            CADMensaje cadMensaje = new CADMensaje();
            return cadMensaje.UpdateMensaje(this);
        }

        public bool DeleteMensaje()
        {
            CADMensaje cadMensaje = new CADMensaje();
            return cadMensaje.DeleteMensaje(this);
        }

        public int ObtenerSiguienteIdMensaje()
        {
            CADMensaje cadMensaje = new CADMensaje();
            return cadMensaje.ObtenerSiguienteIdMensaje();
        }
    }

    public class ENComentario : ENMensaje
    {
        public int Valoracion { get; set; }

        public ENComentario() : base()
        {
            Valoracion = 0;
        }

        public ENComentario(int id, string mensaje, string tipoMensaje, DateTime fecha, int valoracion)
            : base(id, mensaje, tipoMensaje, fecha)
        {
            Valoracion = valoracion;
        }

        public bool CreateComentario()
        {
            CADMensaje cadMensaje = new CADMensaje();
            return cadMensaje.CreateMensaje(this);
        }
        
        public bool UpdateComentario()
        {
            CADMensaje cadMensaje = new CADMensaje();
            return cadMensaje.UpdateComentario(this);
        }
    }

    public class ENIncidencia : ENMensaje
    {
        public ENIncidencia() : base()
        {
            // Constructor por defecto
        }

        public ENIncidencia(int id, string mensaje, string tipoMensaje, DateTime fecha)
            : base(id, mensaje, tipoMensaje, fecha)
        {
            // Constructor con parámetros
        }

        public bool CreateIncidencia()
        {
            CADMensaje cadMensaje = new CADMensaje();
            return cadMensaje.CreateMensaje(this);
        }

        public DataTable GetIncidencias()
        {
            CADMensaje cadMensaje = new CADMensaje();
            return cadMensaje.GetIncidencias();
        }

        public void EliminarIncidencia(int mensaje_id)
        {
            CADMensaje cadMensaje = new CADMensaje();
            cadMensaje.EliminarIncidencia(mensaje_id);
        }
         
    }
}
