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
	public class CADInforme
	{
		private String constring;

		public CADInforme()
		{
			constring = ConfigurationManager.ConnectionStrings["BD_kebab"].ToString();
		}
		
		public DataTable CreateInforme()
		{

			// Consulta para obtener los datos del eje y
			string queryEjeY = "SELECT local_id, SUM(total) AS total FROM Pedido GROUP BY local_id";


			// Crear los datos para el gráfico
			DataTable dataTable = new DataTable();
			dataTable.Columns.Add("Local", typeof(string));
			dataTable.Columns.Add("Total", typeof(decimal));

			// Crear la conexión y los comandos
			using (SqlConnection connection = new SqlConnection(constring))
			{
				SqlCommand commandEjeY = new SqlCommand(queryEjeY, connection);

				// Abrir la conexión
				connection.Open();

				// Leer los datos del eje y
				SqlDataReader readerEjeY = commandEjeY.ExecuteReader();
				while (readerEjeY.Read())
				{
					decimal total = decimal.Parse(readerEjeY["total"].ToString());
                    ENLocal en = new ENLocal(int.Parse(readerEjeY["local_id"].ToString()));
					en.ReadLocal();

					dataTable.Rows.Add(en.Nombre, total);
				}
				readerEjeY.Close();

			}

			return dataTable;
		}

		public bool ReadInforme(ENInforme info)
		{
			return true;
		}

		public bool UpdateInforme(ENInforme info)
		{
			return true;
		}

		public bool DeleteInforme(ENInforme info)
		{
			return true;
		}

	}
}
