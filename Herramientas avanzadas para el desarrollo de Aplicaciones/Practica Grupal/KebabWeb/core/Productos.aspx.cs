using System;
using System.Collections.Generic;
using System.Linq;
using System.Web;
using System.Web.UI;
using System.Web.UI.WebControls;
using System.Text;
using System.Threading.Tasks;
using System.Data.SqlTypes;
using System.Data.SqlClient;
using System.Data.Common;
using System.Data;
using System.Configuration;
using Library;


namespace KebabWeb.core
{
    public partial class Productos : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {

           
            
                if (!IsPostBack)
                {
                    LoadProducts();

                    LoadOfertas();

                    LoadMenus();

                    PopulateLocalesDropDownList();

                }
                Dictionary<string, Tuple<ENProducto, int>> cart;
                Dictionary<string, Tuple<ENMenu, int>> menuCart;

                if (Session["Cart"] != null && Session["Cart"] is Dictionary<string, Tuple<ENProducto, int>>)
                {
                    cart = (Dictionary<string, Tuple<ENProducto, int>>)Session["Cart"];
                    CartRepeater.DataSource = cart.Values.ToList();
                    CartRepeater.DataBind();
                }

                if (Session["menuCart"] != null && Session["menuCart"] is Dictionary<string, Tuple<ENMenu, int>>)
                {
                    menuCart = (Dictionary<string, Tuple<ENMenu, int>>)Session["menuCart"];
                    CartMenuRepeater.DataSource = menuCart.Values.ToList();
                    CartMenuRepeater.DataBind();
                }

                
            

            


        }

        protected void OrderButtonCart(object sender, EventArgs e)
        {
            if (LoginManager.IsLogged())
            {
                Dictionary<string, Tuple<ENProducto, int>> cart = Session["Cart"] as Dictionary<string, Tuple<ENProducto, int>>;
                Dictionary<string, Tuple<ENMenu, int>> menuCart = Session["menuCart"] as Dictionary<string, Tuple<ENMenu, int>>;

                // Crear listas a partir de los valores de los diccionarios
                List<Tuple<ENProducto, int>> cartList = null;
                List<Tuple<ENMenu, int>> menuCartList = null;

                if (cart != null)
                {
                    cartList = cart.Values.ToList();
                }

                if (menuCart != null)
                {
                    menuCartList = menuCart.Values.ToList();
                }


                List<ENPedido> pedidos = new List<ENPedido>();

                ENAlbaran alb = new ENAlbaran();




                if (cartList != null)
                {
                    foreach (var item in cartList)
                    {

                        // Crear un nuevo pedido
                        ENPedido pedido = new ENPedido(LoginManager.GetUser().Email, int.Parse(Session["localId"].ToString()), int.Parse(item.Item2.ToString()), item.Item1.precioProd * item.Item2);

                        float au = item.Item1.precioProd * item.Item2;
                        alb.Importe += au;
                        bool pedidoCreated = pedido.CreatePedido();
                        bool productoAsignedPedido = pedido.asignarProducto(item.Item1);
                        pedidos.Add(pedido);
                    }
                }


                if (menuCartList != null)
                {
                    foreach (var item in menuCartList)
                    {

                        ENPedido pedido = new ENPedido(LoginManager.GetUser().Email, int.Parse(Session["localId"].ToString()), int.Parse(item.Item2.ToString()), item.Item1.Precio * item.Item2);
                        float au = item.Item1.Precio * item.Item2;
                        alb.Importe += au;

                        bool pedidoCreated = pedido.CreatePedido();
                        bool product = pedido.asignarMenu(item.Item1);

                        pedidos.Add(pedido);
                    }
                }

                //inserto todos los pedidos en albaran
                alb.IdP = pedidos;

                bool albaranCreated = alb.CreateAlbaran();



                if (albaranCreated)
                {
                    //cestas Limpias (Solo si albaran esta creado)
                    Session["Cart"] = null;
                    Session["menuCart"] = null;
                }
                else
                {
                    //Error
                }

                Response.Redirect("~/Default.aspx");
            }
            else
            {
                Response.Redirect("~/core/Login.aspx");
            }
        }

        private void PopulateLocalesDropDownList()
        {
            // Obtener los locales de la base de datos
            List<ENLocal> locales = new CADLocal().GetLocales();

    
            if (locales != null)
            {
                LocalesDropDownList.DataSource = locales;
                LocalesDropDownList.DataTextField = "Nombre"; 
                LocalesDropDownList.DataValueField = "ID"; 
                LocalesDropDownList.DataBind();
            }
        }

        protected void LocalesDropDownList_SelectedIndexChanged(object sender, EventArgs e)
        {
            // Guardar el Local ID seleccionado en una variable de sesión
            Session["localId"] = LocalesDropDownList.SelectedValue;
        }

        private void LoadProducts()
        {
            List<ENProducto> productosEntrantes = new CADProducto().GetProductosPorTipo("Entrantes");
            rptEntrantes.DataSource = productosEntrantes;
            rptEntrantes.DataBind();

            List<ENProducto> productosKebab = new CADProducto().GetProductosPorTipo("Kebab");
            rptKebab.DataSource = productosKebab;
            rptKebab.DataBind();

            List<ENProducto> productosPostres = new CADProducto().GetProductosPorTipo("Postres");
            rptPostres.DataSource = productosPostres;
            rptPostres.DataBind();

            List<ENProducto> productosBebidas = new CADProducto().GetProductosPorTipo("Bebidas");
            rptBebidas.DataSource = productosBebidas;
            rptBebidas.DataBind();
        }

        private void LoadOfertas()
        {
            CADOferta cadoferta = new CADOferta();
            List<ENOferta> ofertas = cadoferta.GetOfertas();

            MostrarOfertas.DataSource = ofertas;
            MostrarOfertas.DataBind();
        }

        protected void AddToCart_Click(object sender, EventArgs e)
        {
            // Para obtener el valor:
            int productId = Convert.ToInt32(hiddenProductId.Value);

            CADProducto cadProducto = new CADProducto();
            ENProducto producto = cadProducto.GetProductoById(productId);

            Dictionary<string, Tuple<ENProducto, int>> cart;
            if (Session["Cart"] == null)
            {
                cart = new Dictionary<string, Tuple<ENProducto, int>>();
            }
            else
            {
                cart = (Dictionary<string, Tuple<ENProducto, int>>)Session["Cart"];
            }

            if (cart.ContainsKey(producto.nombreProd))
            {
                var item = cart[producto.nombreProd];
                cart[producto.nombreProd] = new Tuple<ENProducto, int>(item.Item1, item.Item2 + 1);
            }
            else
            {
                cart.Add(producto.nombreProd, new Tuple<ENProducto, int>(producto, 1));
            }
            Session["Cart"] = cart;

            CartRepeater.DataSource = cart.Values.ToList();
            CartRepeater.DataBind();

            UpdateTotal();

            if (LoginManager.IsLogged())
            {
                OrderButton.Visible = true;
            }
        }

        private void LoadMenus()
        {
            CADMenu cadmenu = new CADMenu();
            List<ENMenu> menus = cadmenu.GetMenus();

            rptmenus.DataSource = menus;
            rptmenus.DataBind();
        }

        protected void AddToCartMenu_Click(object sender, EventArgs e)
        {
            // Obtener el valor del menú
            int menuId = Convert.ToInt32(hiddenMenuId.Value);

            CADMenu cadMenu = new CADMenu();
            ENMenu menu = cadMenu.GetMenuById(menuId);

            Dictionary<string, Tuple<ENMenu, int>> menuCart;
            if (Session["menuCart"] == null)
            {
                menuCart = new Dictionary<string, Tuple<ENMenu, int>>();
            }
            else
            {
                menuCart = (Dictionary<string, Tuple<ENMenu, int>>)Session["menuCart"];
            }

            if (menuCart.ContainsKey(menu.Nombre))
            {
                var item = menuCart[menu.Nombre];
                menuCart[menu.Nombre] = new Tuple<ENMenu, int>(item.Item1, item.Item2 + 1);
            }
            else
            {
                menuCart.Add(menu.Nombre, new Tuple<ENMenu, int>(menu, 1));
            }
            Session["menuCart"] = menuCart;

            CartMenuRepeater.DataSource = menuCart.Values.ToList();
            CartMenuRepeater.DataBind();

            UpdateTotal();

            if (LoginManager.IsLogged())
            {
                OrderButton.Visible = true;
            }
        }

        private float CalculateMenuTotal()
        {
            float menuTotal = 0;
            if (Session["menuCart"] != null && Session["menuCart"] is Dictionary<string, Tuple<ENMenu, int>> menuCart)
            {
                foreach (var item in menuCart.Values)
                {
                    menuTotal += item.Item1.Precio * item.Item2;
                }
            }
            return menuTotal;
        }

        private float CalculateProductTotal()
        {
            float productTotal = 0;
            if (Session["Cart"] != null && Session["Cart"] is Dictionary<string, Tuple<ENProducto, int>> cart)
            {
                foreach (var item in cart.Values)
                {
                    productTotal += item.Item1.precioProd * item.Item2;
                }
            }
            return productTotal;
        }

        private void UpdateTotal()
        {
            var productTotal = CalculateProductTotal();
            var menuTotal = CalculateMenuTotal();
            var total = productTotal + menuTotal;
            TotalLabel.Text = "Total: " + total.ToString("C");
        }

        protected void btnBuscarProducto_Click(object sender, EventArgs e)
        {
            string nombreProducto = txtBusqueda.Text;

            CADProducto cadProducto = new CADProducto();
            ENProducto producto = cadProducto.GetProductoByNombre(nombreProducto);

            
            if (producto != null)
            {

                List<ENProducto> productosEncontrados = cadProducto.GetProductosPorNombre(nombreProducto);

                int tamañoLista = productosEncontrados.Count;

                txtBusqueda.Text = string.Empty;

                string encontradosMessage = "PRODUCTOS ENCONTRADOS";
                lblencontrados.Text = encontradosMessage;
                lblencontrados.Visible = true;
                lblMensaje.Visible = false;

                rptrproductosencontrados.Visible = true;
                rptrproductosencontrados.DataSource = productosEncontrados;
                rptrproductosencontrados.DataBind();
            }
            else
            {
                txtBusqueda.Text = string.Empty;
                string errorMessage = "No ofrecemos ese producto";
                lblMensaje.Text = errorMessage;
                lblMensaje.Visible = true;
                lblencontrados.Visible = false;
                rptrproductosencontrados.Visible = false;
            }

        }
        

    }
}

   

