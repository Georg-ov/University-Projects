using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
    public class ENUsuario
    {
        private String email { get; set; }
        private String contraseña { get; set; }
        private String nombre { get; set; }
        private String dni { get; set; }

        private String tipoUsuario { get; set; }

        public String Dni
        {
            get { return dni; }
            set { dni = value; }
        }

        public String Email
        {
            get { return email; }
            set { email = value; }
        }

        public String Contraseña
        {
            get { return contraseña; }
            set { contraseña = value; }
        }

        public String Nombre
        {
            get { return nombre; }
            set { nombre = value; }
        }

        public string TipoUsuario
        {
            get { return tipoUsuario; }
            set { tipoUsuario = value; }
        }

        public ENUsuario()
        {
            email = null;
            contraseña = null;
            nombre = null;
            dni = null;
        }

        public ENUsuario(string dni)
        {
            this.dni = dni;
        }

        public ENUsuario(string email, string contraseña, string nombre, string dni)
        {
            this.email = email;
            this.contraseña = contraseña;
            this.nombre = nombre;
            this.dni = dni;
        }

        public bool createUsuario()
        {
            CADUsuario usuario = new CADUsuario();
            return usuario.createUsuario(this);
        }

        public bool updateUsuario()
        {
            CADUsuario usuario = new CADUsuario();
            return usuario.updateUsuario(this);
        }

        public bool deleteUsuario()
        {
            CADUsuario usuario = new CADUsuario();
            return usuario.deleteUsuario(this);
        }

        public bool readUsuario()
        {
            CADUsuario usuario = new CADUsuario();
            return usuario.readUsuario(this);
        }

        public bool inicioSesion()
        {
            CADUsuario usuario = new CADUsuario();
            return usuario.inicioSesion(this);
        }

        public bool changePassword(string oldPassword, string newPassword)
        {
            CADUsuario usuario = new CADUsuario();
            return usuario.changePassword(this, oldPassword, newPassword);
        }

        public List<ENUsuario> getEmpleados()
        {
            CADUsuario usu = new CADUsuario();

            return usu.getEmpleados();
        }

        public bool changeEmail(string password, string newEmail)
        {
            CADUsuario usuario = new CADUsuario();
            return usuario.changeEmail(this.Email, password, newEmail);
        }

        public bool setLocalSupervisor(int id)
        {
            CADUsuario usu = new CADUsuario();

            return usu.setLocalSupervisor(this, id);
        }

        public bool setSalario(float salario)
        {
            return new CADUsuario().setSalario(this, salario);
        }

    }
}
