using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Data.SqlTypes;
using System.Data.SqlClient;
using System.Data.Common;
using System.Data;
using System.Configuration;


namespace Library
{
	public class ENInforme
	{

		public ENInforme()
		{

		}

		public ENInforme(ENInforme info)
        {

        }

		public DataTable CreateInforme()
        {
			CADInforme cadInforme = new CADInforme();
			return cadInforme.CreateInforme();
        }

		public bool ReadInforme()
        {
			CADInforme cadInforme = new CADInforme();
			return cadInforme.ReadInforme(this);
		}

		public bool UpdateInforme()
        {
			CADInforme cadInforme = new CADInforme();
			return cadInforme.UpdateInforme(this);

		}

		public bool DeleteInforme()
        {
			CADInforme cadInforme = new CADInforme();
			return cadInforme.DeleteInforme(this);
		}
	}
}

