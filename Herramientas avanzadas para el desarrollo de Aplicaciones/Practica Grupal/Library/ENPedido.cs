using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
    public enum Estado { 
        preparado,
        encurso,
        enreparto
    };

    public class ENPedido
    {
        private static int nextid = 100;
        private string emailcliente;
        private int idlocal;
        private int codP;
        private int cantidad;
        private DateTime? fecha;
        private Estado state;
        private float precio;
        
        public string Emailcliente
        {
            get { return emailcliente; }
            set { emailcliente = value; }
        }
        public int Idlocal
        {
            get { return idlocal; }
            set { idlocal = value; }
        }
        public int CodP
        {
            get { return codP; }
            set { codP = value; }
        }
        public DateTime? Fecha
        {
            get { return fecha; }
            set { fecha = value; }
        }
        public Estado State
        {
            get { return state; }
            set { state = value; }
        }
        public float Precio
        {
            get { return precio; }
            set { precio = value; }
        }

        public int Cantidad
        {
            get { return cantidad; }
            set { cantidad = value; }
        }

        public ENPedido()
        {
            emailcliente = "";
            idlocal = 0;
            codP = nextid++;   
            fecha = null;
            state = Estado.encurso;
            precio = 0;           
            cantidad = 1;
        }

        public ENPedido(string emailcliente, int id, int cantid, float precio)
        {
            this.codP = nextid++;
            this.emailcliente = emailcliente;
            this.cantidad = cantid;
            this.idlocal = id;
            this.fecha = DateTime.Now;
            this.precio = precio;
            this.state = Estado.encurso;
            
        }

 
        public ENPedido(string emailcliente, int idlocal, int codP, DateTime? fecha)
        {
            this.emailcliente = emailcliente;
            this.idlocal = idlocal;
            this.codP = codP;
            this.fecha = fecha;
            cantidad = 1;
            this.state = Estado.preparado;
        }

        public bool CreatePedido()
        {
            CADPedido cadPedido = new CADPedido();
            return cadPedido.CreatePedido(this);
        }

        public bool ReadPedido()
        {
            CADPedido cadPedido = new CADPedido();
            return cadPedido.ReadPedido(this);
        }

        public bool UpdatePedido()
        {
            CADPedido cadPedido = new CADPedido();
            return cadPedido.UpdatePedido(this, state);
        }

        public bool DeletePedido()
        {
            CADPedido cadPedido = new CADPedido();
            return cadPedido.DeletePedido(this);
        }

        public List<ENPedido>getPedidos(ENUsuario usu)
        {
            CADPedido cad = new CADPedido();
            CADLocal local = new CADLocal();
            List<ENPedido> list = new List<ENPedido>();
            list = cad.getPedidos(usu);
            list = local.obtenerNombreLocal(list);
            return list;
        }

        public bool asignarProducto(ENProducto en)
        {
            CADPedido pe = new CADPedido();

            return pe.asignarProductos(this, en);
        }

        public bool asignarMenu(ENMenu en)
        {
            CADPedido pe = new CADPedido();

            return pe.asignarMenu(this, en);
        }
    }
}
