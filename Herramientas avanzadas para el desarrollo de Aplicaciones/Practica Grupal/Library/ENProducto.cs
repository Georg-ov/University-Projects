using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
    public class ENProducto
    {
        private int id { get; set; }
        private string nombre { get; set; }
        private string descripcion { get; set; }
        private float precio { get; set; }
        private string imagen { get; set; }
        private string tipoProducto { get; set; }

        public int idProd
        {
            get { return id; }
            set { id = value; }
        }

        public string nombreProd
        {
            get { return nombre; }
            set { nombre = value; }
        }

        public string descripcionProd
        {
            get { return descripcion; }
            set { descripcion = value; }
        }

        public float precioProd
        {
            get { return precio; }
            set { precio = value; }
        }

        public string imagenProd
        {
            get { return imagen; }
            set { imagen = value; }
        }


        public string tipoProd
        {
            get { return tipoProducto; }
            set { tipoProducto = value; }
        }
        public ENProducto()
        {
            this.id = 0;
            this.nombre = "";
            this.descripcion = "";
            this.precio = 0;
            this.imagen = "";
            this.tipoProducto = "";
        }

        public ENProducto(int id, string nombre, string descripcion, float precio, string imagen, float oferta, string tipoProducto)
        {
            this.id = id;
            this.nombre = nombre;
            this.descripcion = descripcion;
            this.precio = precio;
            this.imagen = imagen;
            this.tipoProducto = tipoProducto;
        }
        public bool createProducto()
        {
            CADProducto cadProd = new CADProducto();
            return cadProd.createProducto(this);
        }
        public bool readProducto()
        {
            CADProducto cadProd = new CADProducto();
            return cadProd.readProducto(this);
        }
        public bool updateProducto()
        {
            CADProducto cadProd = new CADProducto();
            return cadProd.updateProducto(this);
        }
        public bool deleteProducto()
        {
            CADProducto cadProd = new CADProducto();
            return cadProd.deleteProducto(this);
        }

        public List<ENProducto> GetProductosPorTipo(string tipoProducto)
        {
            CADProducto cadProd = new CADProducto();
            return cadProd.GetProductosPorTipo(tipoProducto);
        }

        public int ObtenerNextIDProducto()
        {
            CADProducto cadProd = new CADProducto();
            return cadProd.ObtenerNextIDProducto();
        }

        public ENProducto GetProductoById(int id)
        {
            CADProducto cadProd = new CADProducto();
            return cadProd.GetProductoById(id);
        }

        public ENProducto GetProductoByNombre(string nombre)
        {
            CADProducto cadProd = new CADProducto();
            return cadProd.GetProductoByNombre(nombre);
        }

        public List<ENProducto> GetProductosPorNombre(string nombreProducto)
        {
            CADProducto cadProd = new CADProducto();
            return cadProd.GetProductosPorNombre(nombreProducto);
        }

    }   

}
