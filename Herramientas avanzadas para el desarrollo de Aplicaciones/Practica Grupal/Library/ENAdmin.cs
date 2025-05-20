using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
    public class ENAdmin
    {
        private string email;

        public string Email
        {
            set { email = value; }
            get { return email; }
        }
        public ENAdmin(string email)
        {
            this.email = email;
        }
        public ENAdmin()
        {
            this.email = "";
        }

        public bool CreateAdmin()
        {
            bool creado = false;
            CADAdmin admin = new CADAdmin();

            if (!admin.readAdmin(this))
            {
                creado = admin.createAdmin(this);
            }
            return creado;
        }
        public bool ReadAdmin()
        {
            CADAdmin admin = new CADAdmin();

            return admin.readAdmin(this);
        }
        public bool UpdateAdmin(string nuevoEmail)
        {
            bool actualizado = false;

            CADAdmin admin = new CADAdmin();

            if (admin.updateAdmin(this, nuevoEmail))
            {
                actualizado = true;
            }

            return actualizado;
        }
        public bool DeleteAdmin()
        {
            bool eliminado = false;
            CADAdmin admin = new CADAdmin();

            if (admin.readAdmin(this))
            {
                eliminado = admin.deleteAdmin(this);
            }
            return eliminado;
        }
    }

}
