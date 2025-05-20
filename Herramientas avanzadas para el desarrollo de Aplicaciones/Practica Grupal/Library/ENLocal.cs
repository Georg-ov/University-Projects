using System;
using System.Collections.Generic;
using System.Data;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
    public class ENLocal
    {
        private int id;
        private string nombre;
        private string direccion;
        private string ciudad;
        private string supervisor;
        private string telefono;
        private string ubicacion;

        public int ID
        {
            get { return id; }
            set { id = value; }
        }

        public string Nombre
        {
            get { return nombre; }
            set { nombre = value; }
        }

        public string Direccion
        {
            get { return direccion; }
            set { direccion = value; }
        }

        public string Ciudad
        {
            get { return ciudad; }
            set { ciudad = value; }
        }

        public string Telefono
        {
            get { return telefono; }
            set { telefono = value; }
        }

        public string Supervisor
        {
            get { return supervisor; }
            set { supervisor = value; }
        }

        public string Ubicacion
        {
            get { return ubicacion; }
            set { ubicacion = value; }
        }

        public ENLocal()
        {
            id = 0;
            nombre = "";
            direccion = "";
            ciudad = "";
            telefono = "";
            ubicacion = "";
        }

        public ENLocal(int id, string nombre, string direccion, string ciudad, string telefono, string ubicacion)
        {
            this.id = id;
            this.nombre = nombre;
            this.direccion = direccion;
            this.ciudad = ciudad;
            this.telefono = telefono;
            this.ubicacion = ubicacion;
        }

        public ENLocal(int id)
        {
            this.id = id;
        }

        public bool CreateLocal()
        {
            CADLocal local = new CADLocal();

            bool created = false;

            if (!local.ReadLocal(this))
            {
                created = local.CreateLocal(this);
            }

            return created;
        }

        public bool ReadLocal()
        {
            CADLocal local = new CADLocal();
            bool read = local.ReadLocal(this);
            return read;
        }

        public bool UpdateLocal()
        {
            CADLocal local = new CADLocal();
            bool updated = local.UpdateLocal(this);
            return updated;
        }

        public bool DeleteLocal()
        {
            CADLocal local = new CADLocal();

            bool delete = false;

            if (local.ReadLocal(this))
            {
                delete = local.DeleteLocal(this);
            }

            return delete;
        }

        public void EliminarLocal(int id)
        {
            CADLocal cadLocal = new CADLocal();
            cadLocal.EliminarLocal(id);

        }

        public List<ENLocal> GetLocales()
        {
            CADLocal cadLocal = new CADLocal();
            return cadLocal.GetLocales();
        }

        
    }
}
