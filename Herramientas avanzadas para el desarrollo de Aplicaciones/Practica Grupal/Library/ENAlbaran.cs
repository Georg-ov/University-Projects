using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
    public class ENAlbaran
    {
        private int idA;
        private static int nextId = 100;
        private DateTime fecha;
        private float importe;
        private List<ENPedido> pedidos;

        public int IdA { 
            get { return idA; }
            set { idA = value; }
        }
        
        public DateTime Fecha
        {
            get { return fecha; }
            set { fecha = value; }
        }
        public float Importe {
            get { return importe; }
            set { importe = value; }
        }

        public List<ENPedido> IdP {
            get { return pedidos; }
            set { pedidos = value; }
        }

        public ENAlbaran()
        {
            idA = nextId++;         
            fecha = DateTime.Now;
            importe = 0;
            pedidos = new List<ENPedido>();
        }

        public ENAlbaran(int idA, DateTime fecha, float importe, List<ENPedido> pedidos)
        {
            this.idA = nextId++;
            
            this.fecha = DateTime.Now;
            this.importe = importe;
            this.pedidos = new List<ENPedido>(pedidos);
        }

        public bool CreateAlbaran()
        {
            CADAlbaran albaran = new CADAlbaran();
            return albaran.CreateAlbaran(this);
        }

        public bool ReadAlbaran()
        {
            CADAlbaran albaran = new CADAlbaran();
            return albaran.ReadAlbaran(this);
        }

        public bool UpdateAlbaran()
        {
            CADAlbaran albaran = new CADAlbaran();
            return albaran.UpdateAlbaran(this);
        }

        public bool DeleteAlbaran()
        {
            CADAlbaran albaran = new CADAlbaran();
            return albaran.DeleteAlbaran(this);
        }
    }
}

