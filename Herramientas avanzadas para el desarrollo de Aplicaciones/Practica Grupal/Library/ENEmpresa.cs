using System;
using System.Collections;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
    class ENEmpresa
    {
        private char[] cif { get; set; }
        private String nombre { get; set; }
        private String direccion { get; set; }
        private String ciudad { get; set; }
        private char[] telefono { get; set; }
        private String email { get; set; }
        private List<ENProducto> productos { get; set; }
        private List<ENLocal> locales { get; set; }
        private List<ENUsuario> usuarios { get; set; }

        public char[] Cif
        {
            get { return cif; }
            set { cif = value; }
        }

        public String Nombre
        {
            get { return nombre; }
            set { nombre = value; }
        }

        public string Direccion
        {
            get { return direccion; }
            set { direccion = value; }
        }

        public String Ciudad
        {
            get { return ciudad; }
            set { ciudad = value; }
        }

        public char[] Telefono
        {
            get { return telefono; }
            set { telefono = value; }
        }

        public string Email
        {
            get { return email; }
            set { email = value; }
        }
    
        public List<ENProducto> Productos
        {
            get { return productos; }
            set { productos = value; }
        }

        public List<ENLocal> Locales
        {
            get { return locales; }
            set { locales = value; }
        }

        public List<ENUsuario> Usuarios
        {
            get { return usuarios; }
            set { usuarios = value; }
        }
    
        public ENEmpresa()
        {
            cif = new char[9];
            nombre = null;
            direccion = null;
            ciudad = null;
            productos = new List<ENProducto>();
            locales = new List<ENLocal>();
            usuarios = new List<ENUsuario>();
        }

        public ENEmpresa(char[] cif, String nombre, String direccion, String ciudad, char[] tlf, String email, List<ENLocal> locales, List<ENUsuario> usuarios, List<ENProducto> productos)
        {
            this.cif = cif;
            this.nombre = nombre;
            this.direccion = direccion;
            this.ciudad = ciudad;
            this.telefono = tlf;
            this.email = email;
            this.locales = locales;
            this.usuarios = usuarios;
            this.productos = productos;
        }

        public bool createEmpresa()
        {
            CADEmpresa aux = new CADEmpresa();

            return aux.CreateEmpresa(this);
        }

        public bool readEmpresa()
        {
            CADEmpresa aux = new CADEmpresa();

            return aux.ReadEmpresa(this);
        }

        public bool updateEmpresa()
        {
            CADEmpresa aux = new CADEmpresa();

            return aux.UpdateEmpresa(this);
        }

        public bool deleteEmpresa()
        {
            CADEmpresa aux = new CADEmpresa();

            return aux.DeleteEmpresa(this);
        }

    }
}
