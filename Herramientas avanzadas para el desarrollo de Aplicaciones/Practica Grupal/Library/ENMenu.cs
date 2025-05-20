using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Library
{
	public class ENMenu
	{
		private int id { get; set; }
		private string nombre { get; set; }
		private float precio { get; set; }
		private int id_prod1 { get; set; }
		private int id_prod2 { get; set; }
		private int id_prod3 { get; set; }



		public int Id_prod1
        {
            get { return id_prod1; }
			set { id_prod1 = value; }
        }

		public int Id_prod2
		{
			get { return id_prod2; }
			set { id_prod2 = value; }
		}

		public int Id_prod3
		{
			get { return id_prod3; }
			set { id_prod3 = value; }
		}

		public int Id
		{
			get { return id; }
			set { id = value; }
		}

		public string Nombre
		{
			get { return nombre; }
			set { nombre = value; }
		}

		public float Precio
		{
			get { return precio; }
			set { precio = value; }
		}

		public ENMenu()
		{
			id= 0;
			nombre = "";
			precio = 0;
			id_prod1 = 0;
			id_prod2 = 0;
			id_prod3 = 0;
		}

		public ENMenu(int id, float precio, string nombre, int id_prod1, int id_prod2, int id_prod3)
		{
			this.id = id;
			this.precio = precio;
			this.nombre = nombre;
			this.id_prod1 = id_prod1;
			this.id_prod2 = id_prod2;
			this.id_prod3 = id_prod3;
		}

		public bool CreateMenu()
		{
			CADMenu cadMenu = new CADMenu();
			return cadMenu.CreateMenu(this);
		}

		public bool ReadMenu()
		{
			CADMenu cadMenu = new CADMenu();
			return cadMenu.ReadMenu(this);
		}

		public bool UpdateMenu()
		{
			CADMenu cadMenu = new CADMenu();
			return cadMenu.UpdateMenu(this);
		}

		public bool DeleteMenu()
		{
			CADMenu cadMenu = new CADMenu();
			return cadMenu.DeleteMenu(this);
		}

		public List<ENMenu> GetMenus()
        {
			CADMenu cadMenu = new CADMenu();
			return cadMenu.GetMenus();
        }

		public int ObtenerNextIDMenu()
        {
			CADMenu cadMenu = new CADMenu();
			return cadMenu.ObtenerNextIDMenu();
		}

		public ENMenu GetMenuById(int id)
        {
			CADMenu cadMenu = new CADMenu();
			return cadMenu.GetMenuById(id);
		}

	}
}




