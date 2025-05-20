using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
    public class LoginManager
    {
        private static ENUsuario usuario;
        private static bool logged = false;

        public static bool IsLogged()
        {
            return logged;
        }

        public static void Login(ENUsuario en)
        {
            en.readUsuario();
            LoginManager.usuario = en;
            LoginManager.logged = true;
        }

        public static ENUsuario GetUser()
        {
            return LoginManager.usuario;
        }

        public static bool isAdmin()
        {
            CADAdmin a = new CADAdmin();
            ENAdmin e = new ENAdmin(usuario.Email);

            return e.ReadAdmin();
        }

        public static bool isEmp()
        {
            CADUsuario a = new CADUsuario();
            return a.isEmp(usuario);
        }

        public static void logOut()
        {
            if (logged)
            {
                usuario = null;
                logged = false;
            }
        }
    }
}
