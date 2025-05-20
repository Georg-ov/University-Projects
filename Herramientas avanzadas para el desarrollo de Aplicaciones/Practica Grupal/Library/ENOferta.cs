using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
    public class ENOferta
    {
        private int id { get; set; }
        private float precio { get; set; }
        private string codigo { get; set; }

        public int Id
        {
            get { return id; }
            set { id = value; }
        }

        public float Precio
        {
            get { return precio; }
            set { precio = value; }
        }

        public string Codigo
        {
            get { return codigo; }
            set { codigo = value; }
        }

        public ENOferta()
        {
            id = 0;
            precio = 0;
            codigo = null;
        }

        public ENOferta(int id, float precio, string codigo)
        {
            Id = id;
            Precio = precio;
            Codigo = codigo;
        }

        public bool CreateOferta()
        {
            CADOferta oferta = new CADOferta();

            bool created = false;

            if (!oferta.ReadOferta(this))
            {
                created = oferta.CreateOferta(this);
            }
                
            return created;
        }

        public bool ReadOferta()
        {
            CADOferta oferta = new CADOferta();
            bool read = oferta.ReadOferta(this);
            return read;
        }

        public bool UpdateOferta()
        {
            CADOferta oferta = new CADOferta();
            bool updated = oferta.UpdateOferta(this);
            return updated;
        }

        public bool DeleteOferta()
        {
            CADOferta oferta = new CADOferta();

            bool delete = false;
            delete = oferta.DeleteOferta(this);
            return delete;
        }

        public List<ENOferta> GetOfertas()
        {
            CADOferta oferta = new CADOferta();
            return oferta.GetOfertas();
        }
    }
}
