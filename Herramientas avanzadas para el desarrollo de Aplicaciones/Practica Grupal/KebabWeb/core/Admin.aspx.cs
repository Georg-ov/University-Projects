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
using System.Web.UI.WebControls;
using System.Web.UI;
using Library;
using static System.Net.Mime.MediaTypeNames;
using System.Text.RegularExpressions;
using System.Net;

namespace KebabWeb.core
{
    public partial class Admin : System.Web.UI.Page
    {
        protected void Page_Load(object sender, EventArgs e)
        {


            if (LoginManager.IsLogged())
            {
                if (LoginManager.isAdmin())
                {
                    LoadProducts();
                    LoadMenus();

                    MensajeOferta.Text = "";
                    if (IsPostBack)
                    {
                        System.Diagnostics.Debug.WriteLine("PostBack occurred");
                    }

                    if (!IsPostBack)
                    {
                        LoadLocales();
                    }
                }
                else if (LoginManager.isEmp())
                {
                    btnOpenCrearLocal.Visible = false;
                    botonCrearOferta.Visible = false;
                    botonCrearEmpleado.Visible = false;
                    botonmenus.Visible = false;
                    botonproductos.Visible = false;


                }
                else if (!LoginManager.isEmp() && !LoginManager.isAdmin())
                    Response.Redirect("~/Default.aspx");
            }
            else
                Response.Redirect("~/Default.aspx");
        }


        protected void btnOpenCrearMensajeForm_Click(object sender, EventArgs e)
        {
            // Mostrar el formulario de creación de mensajes
            pnlCrearMensajeForm.Visible = true;
            pnlCrearIncidencia.Visible = false;
            panelcrearmenu.Visible = false;
            panelCrearOferta.Visible = false;
            panelcrearproducto.Visible = false;
            pnlCrearLocal.Visible = false;
            Panelcrearempleado.Visible = false;
            menusmostradosadmin.Visible = false;
        }


        protected void btnOpenCrearIncidenciaForm_Click(object sender, EventArgs e)
        {
            // Mostrar el formulario de creación de incidencias
            pnlCrearIncidencia.Visible = true;
            pnlCrearMensajeForm.Visible = false;
            panelcrearmenu.Visible = false;
            panelCrearOferta.Visible = false;
            panelcrearproducto.Visible = false;
            pnlCrearLocal.Visible = false;
            Panelcrearempleado.Visible = false;
            menusmostradosadmin.Visible = false;
        }

        protected void btnOpenCrearLocalForm_Click(object sender, EventArgs e)
        {
            List<ENUsuario> options = new ENUsuario().getEmpleados();
            foreach (ENUsuario en in options)
            {

                ddlOptions.Items.Add(ListItem.FromString(en.Dni + " - " + en.Nombre));
            }

            ddlOptions.Items.Add(ListItem.FromString("+ Nuevo Empleado"));

            // Mostrar el formulario de creación de locales
            pnlCrearLocal.Visible = true;
            panelCrearOferta.Visible = false;
            panelcrearmenu.Visible = false;
            pnlCrearIncidencia.Visible = false;
            pnlCrearMensajeForm.Visible = false;
            panelcrearproducto.Visible = false;
            Panelcrearempleado.Visible = false;
            menusmostradosadmin.Visible = false;
        }


        protected void btnOpenCrearEmpleadoForm_Click(object sender, EventArgs e)
        {
            Panelcrearempleado.Visible = true;
            pnlCrearIncidencia.Visible = false;
            pnlCrearMensajeForm.Visible = false;
            panelcrearmenu.Visible = false;
            panelCrearOferta.Visible = false;
            panelcrearproducto.Visible = false;
            pnlCrearLocal.Visible = false;
            menusmostradosadmin.Visible = false;
        }

        protected void btnCrearComentario_Click(object sender, EventArgs e)
        {
            lblError.Visible = false;
            lblError.Text = string.Empty;
            ENMensaje men = new ENMensaje();
            men.Mensaje = txtMensaje.Text;
            men.TipoMensaje = "Comentario";
            string usuarioEmail = LoginManager.GetUser().Email;
            int localId = Convert.ToInt32(ddlLocales.SelectedValue);

            if (string.IsNullOrEmpty(men.Mensaje) || string.IsNullOrWhiteSpace(men.Mensaje))
            {
                lblError.Text = "El campo 'Mensaje' es requerido";
                lblError.Visible = true;
                return;
            }

            if (!men.CreateMensaje())
            {
                return;
            }

            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();

            int valoracion;
            if (int.TryParse(txtValoracion.Text, out valoracion))
            {
              
                ENComentario comentarioExistente = new ENComentario();
                comentarioExistente.Id = men.Id;
                comentarioExistente.Valoracion = valoracion;

                if (comentarioExistente.UpdateComentario())
                {
                 
                    txtMensaje.Text = string.Empty;
                    txtValoracion.Text = string.Empty;
                    lblError.Visible = false;
                    lblError.Text = string.Empty;
                }
                else
                {
                    lblError.Text = "El campo 'Valoración' debe ser un número válido(1-5)";
                    lblError.Visible = true;
                    return;
                }
            }
            else
            {
                lblError.Text = "El campo 'Valoración' debe ser un número válido(1-5)";
                lblError.Visible = true;
                return;
            }

            cadLocalMensajeUsuario.AgregarLocalMensajeUsuario(localId, men.Id, usuarioEmail);
            lblError.Visible = false;
            lblError.Text = string.Empty;


        }

        private void LoadLocales()
        {
            CADLocalMensajeUsuario cadLocalMensajeUsuario = new CADLocalMensajeUsuario();
            List<ENLocal> locales = cadLocalMensajeUsuario.GetLocales();

            ddlLocales.DataSource = locales;
            ddlLocales.DataBind();
        }

        protected void ddlOptions_SelectedIndexChanged(object sender, EventArgs e)
        {
            if(ddlOptions.SelectedValue.Equals("+ Nuevo Empleado"))
            {
                pnlCrearLocal.Visible = false;
            }

        }


        protected void btnCrearIncidencia_Click(object sender, EventArgs e)
        {
            ENMensaje nuevoMensaje = new ENMensaje();
            nuevoMensaje.Mensaje = txtMensajeIncidencia.Text;
            nuevoMensaje.TipoMensaje = "Incidencia";

            if (!nuevoMensaje.CreateMensaje())
            {
                return;
            }

            pnlCrearIncidencia.Visible = false;
            txtMensajeIncidencia.Text = string.Empty;

        }

        protected void btnCrearLocal_Click(object sender, EventArgs e)
        {
            // Crear una instancia de ENLocal
            ENLocal nuevoLocal = new ENLocal();
            nuevoLocal.Nombre = txtNombreLocal.Text;
            nuevoLocal.Direccion = txtDireccionLocal.Text;
            nuevoLocal.Ciudad = txtCiudadLocal.Text;
            nuevoLocal.Telefono = txtTelefonoLocal.Text;
            nuevoLocal.Ubicacion = txtUbicacionLocal.Text;
            ENUsuario em = new ENUsuario(ddlOptions.SelectedValue);
            em.readUsuario();
            if (nuevoLocal.CreateLocal())
            {
                // Local creado correctamente
                pnlCrearLocal.Visible = false;
                txtNombreLocal.Text = string.Empty;
                txtDireccionLocal.Text = string.Empty;
                txtCiudadLocal.Text = string.Empty;
                txtTelefonoLocal.Text = string.Empty;
                nuevoLocal.ReadLocal();
                if (em.setLocalSupervisor(nuevoLocal.ID))
                {

                }
                else
                {
                    nuevoLocal.DeleteLocal();
                }


                LoadLocales();
            }
            else
            {

            }
        }


        protected void botonOpenMenus_Click(object sender, EventArgs e)
        {
            botonCrearMenu.Visible = true;
            botonEliminarMenu.Visible = true;
            botonModificarMenu.Visible = true;
            pnlCrearIncidencia.Visible = false;
            pnlCrearMensajeForm.Visible = false;
            panelCrearOferta.Visible = false;
            panelcrearproducto.Visible = false;
            pnlCrearLocal.Visible = false;
            panelcrearproducto.Visible = false;
            btnOpenCrearComentarioForm.Visible = false;
            btnOpenCrearIncidenciaForm.Visible = false;
            btnOpenCrearLocal.Visible = false;
            botonCrearOferta.Visible = false;
            botonCrearProducto.Visible = false;
            botonCrearEmpleado.Visible = false;
            botonmenus.Visible = false;
            menusmostradosadmin.Visible = true;
            botonproductos.Visible = false;
        }

        protected void botonOpenCrearMenu_Click(object sender, EventArgs e)
        {
            // Mostrar el formulario de creación de menu
            panelcrearmenu.Visible = true;
            paneleliminarmenu.Visible = false;
            panelmodmenu.Visible = false;
        }

        protected void botonOpenEliminarMenu_Click(object sender, EventArgs e)
        {
            // Mostrar el formulario de eliminacion de menu
            panelmodmenu.Visible = false;
            panelcrearmenu.Visible = false;
            paneleliminarmenu.Visible = true;

        }

        protected void botonOpenModificarMenu_Click(object sender, EventArgs e)
        {
            // Mostrar el formulario de modificacion de menu
            panelmodmenu.Visible = true;
            panelcrearmenu.Visible = false;
            paneleliminarmenu.Visible = false;
        }

        protected void btnConfirmarMenu_Click(object sender, EventArgs e)
        {
            float precio;
            int id1;
            int id2;
            int id3;

            if (float.TryParse(txtpreciomenu.Text, out precio))
            {
                ENMenu menuNuevo = new ENMenu();

                menuNuevo.Nombre = txtnombremenu.Text;
                menuNuevo.Precio = precio;

                if (int.TryParse(txtproducto1.Text, out id1))
                {
                    menuNuevo.Id_prod1 = id1;

                }

                if (int.TryParse(txtproducto2.Text, out id2))
                {
                    menuNuevo.Id_prod2 = id2;

                }

                if (int.TryParse(txtproducto3.Text, out id3))
                {
                    menuNuevo.Id_prod3 = id3;

                }

                if (menuNuevo.CreateMenu())
                {
                    panelcrearmenu.Visible = false;
                    txtnombremenu.Text = string.Empty;
                    txtpreciomenu.Text = string.Empty;
                    txtproducto1.Text = string.Empty;
                    txtproducto2.Text = string.Empty;
                    txtproducto3.Text = string.Empty;
                }
                else
                {
                    
                }
            }
            else
            {

            }
            LoadMenus();
        }

        protected void btnEliminarMenu_Click(object sender, EventArgs e)
        {
            
              if (txtidelminarmenu.Text != "")
              {
                ENMenu menu = new ENMenu();
                menu.Id = int.Parse(txtidelminarmenu.Text);

                if (menu.DeleteMenu())
                    lblmensajeeliminarmenu.Text = "Menu  con id:" + menu.Id + " borrado con éxito";
                        

                else lblmensajeeliminarmenu.Text = "No es posible borrar el menu con id: " + menu.Id;

              }

            else lblmensajeeliminarmenu.Text = "El campo Id esta vacio.";
            txtidelminarmenu.Text = string.Empty;
            panelmodmenu.Visible = false;
            paneleliminarmenu.Visible = false;
            LoadMenus();
        }

        protected void btnModificarMenu_Click(object sender, EventArgs e)
        {          
            int id;

            if (int.TryParse(txtidmodmenu.Text, out id))
            {
                ENMenu menuMod = new ENMenu();

                menuMod.Id = id;
                menuMod.Nombre = txtnombremodmenu.Text;
                menuMod.Precio = float.Parse(txtpreciomodmenu.Text);
                menuMod.Id_prod1 = int.Parse(txtprod1modmenu.Text);
                menuMod.Id_prod2 = int.Parse(txtprod2modmenu.Text);
                menuMod.Id_prod3 = int.Parse(txtprod3modmenu.Text);

                CADMenu cadMenu = new CADMenu();
                if (cadMenu.UpdateMenu(menuMod))
                {
                    panelmodmenu.Visible = false;
                    txtidmodmenu.Text = string.Empty;
                    txtnombremodmenu.Text = string.Empty;
                    txtpreciomodmenu.Text = string.Empty;
                    txtprod1modmenu.Text = string.Empty;
                    txtprod2modmenu.Text = string.Empty;
                    txtprod3modmenu.Text = string.Empty;
                }
                else
                {
                  
                }
            }
            else
            {

            }
            LoadMenus();
        }

        protected void botonOpenProductos_Click(object sender, EventArgs e)
        {                   
            pnlCrearIncidencia.Visible = false;
            pnlCrearMensajeForm.Visible = false;
            panelCrearOferta.Visible = false;
            panelcrearproducto.Visible = false;
            pnlCrearLocal.Visible = false;
            panelcrearproducto.Visible = false;
            btnOpenCrearComentarioForm.Visible = false;
            btnOpenCrearIncidenciaForm.Visible = false;
            btnOpenCrearLocal.Visible = false;
            botonCrearOferta.Visible = false;
            botonCrearEmpleado.Visible = false;
            botonmenus.Visible = false;
            botonproductos.Visible = false;

            botonModProducto.Visible = true;
            botonCrearProducto.Visible = true;
            botonEliminarProducto.Visible = true;
            productosmostraradmin.Visible = true;
        }

        protected void botonOpenCrearProducto_Click(object sender, EventArgs e)
        {
            // Mostrar el formulario de creación de productos
            panelcrearmenu.Visible = false;
            pnlCrearIncidencia.Visible = false;
            pnlCrearMensajeForm.Visible = false;
            panelCrearOferta.Visible = false;
            pnlCrearLocal.Visible = false;
            Panelcrearempleado.Visible = false;
            menusmostradosadmin.Visible = false;
            
            paneleliminarproductos.Visible = false;
            panelcrearproducto.Visible = true;
            panelmodproducto.Visible = false;
        }

        protected void botonOpenEliminarProducto_Click(object sender, EventArgs e)
        {
            // Mostrar el formulario de elimiacion de productos
            panelcrearmenu.Visible = false;
            pnlCrearIncidencia.Visible = false;
            pnlCrearMensajeForm.Visible = false;
            panelCrearOferta.Visible = false;
            pnlCrearLocal.Visible = false;

            panelcrearproducto.Visible = false;
            paneleliminarproductos.Visible = true;
            panelmodproducto.Visible = false;
            
            Panelcrearempleado.Visible = false;
            menusmostradosadmin.Visible = false;
        }

        protected void botonOpenModificarProducto_Click(object sender, EventArgs e)
        {
            // Mostrar el formulario de modificacion de productos
            panelcrearmenu.Visible = false;
            pnlCrearIncidencia.Visible = false;
            pnlCrearMensajeForm.Visible = false;
            panelCrearOferta.Visible = false;
            pnlCrearLocal.Visible = false;
            Panelcrearempleado.Visible = false;
            menusmostradosadmin.Visible = false;
            
            paneleliminarproductos.Visible = false;
            panelmodproducto.Visible = true;
            panelcrearproducto.Visible = false;
        }

        protected void btnConfirmarProducto_Click(object sender, EventArgs e)
        {
            float precion;

            if (float.TryParse(txtprecioproducto.Text, out precion))
            {
                ENProducto productoNuevo = new ENProducto();

                //productoNuevo.idProd = idped;
                productoNuevo.nombreProd = txtnombreproducto.Text;
                productoNuevo.descripcionProd = txtdescripcionproducto.Text;
                productoNuevo.precioProd = precion;
                productoNuevo.imagenProd = txtimagenproducto.Text;
                productoNuevo.tipoProd = txttipoproducto.Text;
               
                if (productoNuevo.createProducto())
                {
                    panelcrearproducto.Visible = false;
                    txtnombreproducto.Text = string.Empty;
                    txtdescripcionproducto.Text = string.Empty;
                    txtprecioproducto.Text = string.Empty;
                    txtimagenproducto.Text = string.Empty;
                    txttipoproducto.Text = string.Empty;

                }
                else
                {

                }
            }
            else
            {

            }
            LoadProducts();
        }

        protected void btnEliminarProducto_Click(object sender, EventArgs e)
        {

            if (txtideliminarproducto.Text != "")
            {
                ENProducto producto = new ENProducto();
                producto.idProd = int.Parse(txtideliminarproducto.Text);

                if (producto.deleteProducto())
                    lblmensajeeliminarproducto.Text = "Producto con id:" + producto.idProd + " borrado con éxito";

                else lblmensajeeliminarproducto.Text = "No es posible borrar el producto con id: " + producto.idProd;

            }

            else lblmensajeeliminarproducto.Text = "El campo Id esta vacio.";

            txtideliminarproducto.Text = string.Empty;
            paneleliminarproductos.Visible = false;
            LoadProducts();
        }

        protected void btnModificarProducto_Click(object sender, EventArgs e)
        {
            int id;

            if(int.TryParse(txtidmodproducto.Text, out id))
            {
                ENProducto productoMod = new ENProducto();

                productoMod.idProd = id;
                productoMod.nombreProd = txtnombremodproducto.Text;
                productoMod.descripcionProd = txtdescripcionmodproducto.Text;
                productoMod.precioProd = float.Parse(txtpreciomodproducto.Text);
                productoMod.imagenProd = txturlmodproducto.Text;
                productoMod.tipoProd = txttipomodproducto.Text;

                CADProducto cadProducto = new CADProducto();

                if (cadProducto.updateProducto(productoMod))
                {
                    panelmodproducto.Visible = false;
                    txtidmodproducto.Text = string.Empty;
                    txtnombremodproducto.Text = string.Empty;
                    txtdescripcionmodproducto.Text = string.Empty;
                    txtpreciomodproducto.Text = string.Empty;
                    txturlmodproducto.Text = string.Empty;
                    txttipomodproducto.Text = string.Empty;
                }
                else
                {

                }
            }
            else
            {

            }
            LoadProducts();
        }

        protected void botonOpenCrearOferta(object sender, EventArgs e)
        {
            // Mostrar el formulario de creación de ofertas
            panelCrearOferta.Visible = true;
            panelcrearmenu.Visible = false;
            pnlCrearIncidencia.Visible = false;
            pnlCrearMensajeForm.Visible = false;
            pnlCrearLocal.Visible = false;
            panelcrearproducto.Visible = false;
            Panelcrearempleado.Visible = false;
            menusmostradosadmin.Visible = false;
        }

        protected void crearOferta(object sender, EventArgs e)
        {
            bool mismo_codigo = false;
            bool existe_oferta = false;

            if (text_id.Text != "" && text_precio.Text != "" && text_codigo.Text != "")
            {
                CADOferta cadoferta = new CADOferta();
                List<ENOferta> ofertas = cadoferta.GetOfertas();

                foreach(ENOferta ofert in ofertas)
                {
                    if(ofert.Codigo == text_codigo.Text)
                    {
                        mismo_codigo = true;
                    }
                    if(ofert.Id.ToString() == text_id.Text.ToString())
                    {
                        existe_oferta = true;
                    }
                }

                if(existe_oferta == false)
                {
                    if (mismo_codigo == false)
                    {
                        ENOferta oferta = new ENOferta();
                        oferta.Id = int.Parse(text_id.Text);
                        oferta.Precio = float.Parse(text_precio.Text);
                        oferta.Codigo = text_codigo.Text;

                        if (oferta.CreateOferta())
                            MensajeOferta.Text = "Oferta " + oferta.Id + " añadido";
                        else MensajeOferta.Text = "No es posible insertar la oferta.";

                    }
                    else
                    {
                        MensajeOferta.Text = "Este código de oferta ya existe";
                    }

                }
                else
                {
                    MensajeOferta.Text = "Ya existe esta oferta";
                }
            }

            else MensajeOferta.Text = "Alguno de los campos no estan especificados.";
        }

        protected void borrarOferta(object sender, EventArgs e)
        {
            if (text_id.Text != "")
            {
                ENOferta oferta = new ENOferta();
                oferta.Id = int.Parse(text_id.Text);

                if (oferta.DeleteOferta())
                    MensajeOferta.Text = "Oferta " + oferta.Id + " borrado con éxito";
                else MensajeOferta.Text = "No es posible borrar la oferta " + oferta.Id;

            }

            else MensajeOferta.Text = "El campo Id esta vacio.";
        }

        protected void mostrarOferta(object sender, EventArgs e)
        {
            if (text_id.Text == "")
                MensajeOferta.Text = "Id de oferta no introducido.";
            else
            {
                ENOferta oferta = new ENOferta();
                oferta.Id = int.Parse(text_id.Text);

                if (oferta.ReadOferta())
                {
                    text_precio.Text = oferta.Precio.ToString();
                    text_codigo.Text = oferta.Codigo.ToString();
                    MensajeOferta.Text = "Oferta " + oferta.Id + " mostrado con éxito.";
                }
                else
                {
                    MensajeOferta.Text = "Oferta no encontrado";
                    text_precio.Text = "";
                    text_codigo.Text = "";
                }
            }
        }

        protected void modificarOferta(object sender, EventArgs e)
        {
            bool mismo_codigo = false;
            bool existe_oferta = false;

            if (text_id.Text != "" && text_precio.Text != "" && text_codigo.Text != "")
            {
                CADOferta cadoferta = new CADOferta();
                List<ENOferta> ofertas = cadoferta.GetOfertas();

                foreach (ENOferta ofert in ofertas)
                {
                    if (ofert.Codigo.ToString() == text_codigo.Text.ToString())
                    {
                        mismo_codigo = true;
                    }
                    if (ofert.Id.ToString() == text_id.Text.ToString())
                    {
                        existe_oferta = true;
                    }
                }

                if (existe_oferta == true)
                {
                    if (mismo_codigo == false)
                    {
                        ENOferta oferta = new ENOferta();
                        oferta.Id = int.Parse(text_id.Text);
                        oferta.Precio = float.Parse(text_precio.Text);
                        oferta.Codigo = text_codigo.Text;

                        if (oferta.UpdateOferta())
                            MensajeOferta.Text = "Oferta " + oferta.Id + " actualizado";
                        else MensajeOferta.Text = "No es posible actualizar la oferta.";

                    }
                    else
                    {
                        MensajeOferta.Text = "Este código de oferta ya existe";
                    }

                }
                else
                {
                    MensajeOferta.Text = "No existe esta oferta";
                }
            }

            else MensajeOferta.Text = "Alguno de los campos no estan especificados.";

        }

        protected bool comprobarEmailCorrecto(string email)
        {
            // Expresión regular para validar el formato de un correo electrónico
            string patron = @"^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$";
            Regex regex = new Regex(patron);

            return regex.IsMatch(email);
        }

        protected bool comprobarDNICorrecto(string dni)
        {
            string patron = @"^\d{8}[a-zA-Z]$";
            if (!System.Text.RegularExpressions.Regex.IsMatch(dni, patron))
                return false;

            string letras = "TRWAGMYFPDXBNJZSQVHLCKE";
            int numero;
            string letraControl;
            try
            {
                numero = int.Parse(dni.Substring(0, 8));
                letraControl = dni.Substring(8, 1).ToUpper();
            }
            catch (Exception)
            {
                return false;
            }

            int indice = numero % 23;
            char letraCalculada = letras[indice];

            return letraControl[0] == letraCalculada;
        }

        protected bool comprobarNombreCorrecto(string nombre)
        {
            string patron = @"^[a-zA-Z\s]+$";
            return System.Text.RegularExpressions.Regex.IsMatch(nombre, patron);
        }

        protected bool comprobarSalarioCorrecto(string salario)
        {
            float resultado;
            return float.TryParse(salario, out resultado);
        }

        protected void btnCrearEmpleado_Click(object sender, EventArgs e)
        {
            if (TextBox1.Text != null && comprobarEmailCorrecto(TextBox1.Text))
            {
                if (TextBox2.Text != null)
                {
                    if (TextBox3.Text != null && comprobarNombreCorrecto(TextBox3.Text))
                    {
                        if (TextBox4.Text != null && comprobarDNICorrecto(TextBox4.Text))
                        {
                            if (TextBox5.Text != null && comprobarSalarioCorrecto(TextBox5.Text))
                            {
                                ENUsuario en = new ENUsuario(TextBox1.Text, TextBox2.Text, TextBox3.Text, TextBox4.Text);
                                en.TipoUsuario = "Empleado";
                                if (en.createUsuario())
                                {
                                    en.setSalario(float.Parse(TextBox5.Text));
                                    Panelcrearempleado.Visible = false;
                                    TextBox1.Text = string.Empty;
                                    TextBox2.Text = string.Empty;
                                    TextBox3.Text = string.Empty;
                                    TextBox4.Text = string.Empty;
                                    TextBox5.Text = string.Empty;
                                }
                                else
                                {
                                    TextBox1.Text = string.Empty;
                                    TextBox2.Text = string.Empty;
                                    TextBox3.Text = string.Empty;
                                    TextBox4.Text = string.Empty;
                                    TextBox5.Text = string.Empty;
                                }

                            }
                            else
                                TextBox5.Text = string.Empty;
                        }
                        else
                            TextBox4.Text = string.Empty;
                    }
                    else
                        TextBox3.Text = string.Empty;
                }
                else
                    TextBox2.Text = string.Empty;
            }
            else
                TextBox1.Text = string.Empty;

        }

        private void LoadMenus()
        {
            CADMenu cadmenu = new CADMenu();
            List<ENMenu> menus = cadmenu.GetMenus();

            rptmenus.DataSource = menus;
            rptmenus.DataBind();
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

    }   
}