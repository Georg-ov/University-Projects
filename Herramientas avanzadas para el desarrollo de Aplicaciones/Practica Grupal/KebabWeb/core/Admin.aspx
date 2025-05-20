<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="Admin.aspx.cs" Inherits="KebabWeb.core.Admin" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
    <link rel="stylesheet" href="/css/Admin.css">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    
    <!-- Botón para abrir el formulario de creación de mensajes -->
    <asp:Button ID="btnOpenCrearComentarioForm" runat="server" Text="Crear Comentario" OnClick="btnOpenCrearMensajeForm_Click" CssClass="button-outside"/>
    <!-- Botón para abrir el formulario de creación de incidente -->
    <asp:Button ID="btnOpenCrearIncidenciaForm" runat="server" Text="Crear Incidencia" OnClick="btnOpenCrearIncidenciaForm_Click" CssClass="button-outside"/>
    <!-- Botón para abrir el formulario de creación de Locales -->
    <asp:Button ID="btnOpenCrearLocal" runat="server" Text="Crear Local" OnClick="btnOpenCrearLocalForm_Click" CssClass="button-outside" Visible = true/>
    <!-- Botón para abrir el formulario de creación de ofertas -->
    <asp:Button ID="botonCrearOferta" runat="server" Text="Crear Oferta" OnClick="botonOpenCrearOferta" CssClass="button-outside"/>
    <!-- Boton para creacion de empleado -->
    <asp:Button ID="botonCrearEmpleado" runat="server" Text="Crear Empleado" OnClick="btnOpenCrearEmpleadoForm_Click" CssClass="button-outside"/>

    <!-- Formulario de creación de mensajes -->
    <asp:Panel ID="pnlCrearMensajeForm" runat="server" Visible="false" CssClass="panel" DefaultButton="btnCrearMensaje">
        <asp:Label ID="lblTitleMensaje" runat="server" Text="Creador de Mensaje" CssClass="label" />
        <asp:Label ID="lblMensaje" runat="server" Text="Mensaje:" CssClass="label"/>
        <asp:TextBox ID="txtMensaje" runat="server" CssClass="textbox"/>
      
        <asp:Label ID="lblValoracion" runat="server" Text="Valoración:" CssClass="label"/>
        <asp:TextBox ID="txtValoracion" runat="server" CssClass="textbox" />

        <asp:DropDownList ID="ddlLocales" runat="server" DataTextField="Nombre" DataValueField="Id" />

         <asp:Label ID="lblError" runat="server" CssClass="error" Visible="false"></asp:Label> 

        
        <asp:Button ID="btnCrearMensaje" runat="server" Text="Crear" OnClick="btnCrearComentario_Click" CssClass="button"/>
    </asp:Panel>

    

    <!-- Formulario de creación de incidencia -->
    <asp:Panel ID="pnlCrearIncidencia" runat="server" Visible="false" CssClass="panel" DefaultButton="btnCrearIncidencia">
        <asp:Label ID="lblTitleIncidencia" runat="server" Text="Creador de Incidencia" CssClass="label" />
        <asp:Label ID="lblMensajeIncidencia" runat="server" Text="Mensaje:" CssClass="label" />
        <asp:TextBox ID="txtMensajeIncidencia" runat="server" CssClass="textbox" />

        <asp:Button ID="btnCrearIncidencia" runat="server" Text="Crear" OnClick="btnCrearIncidencia_Click" CssClass="button" />
    </asp:Panel>

    <!-- Formulario de creación de Locales -->
    <asp:Panel ID="pnlCrearLocal" runat="server" Visible="false" CssClass="panel" DefaultButton="btnCrearLocal">
        <asp:Label ID="lblNombreLocal" runat="server" Text="Nombre:" CssClass="label" />
        <asp:TextBox ID="txtNombreLocal" runat="server" CssClass="textbox" />

        <asp:Label ID="lblDireccionLocal" runat="server" Text="Dirección:" CssClass="label" />
        <asp:TextBox ID="txtDireccionLocal" runat="server" CssClass="textbox" />

        <asp:Label ID="lblCiudadLocal" runat="server" Text="Ciudad:" CssClass="label" />
        <asp:TextBox ID="txtCiudadLocal" runat="server" CssClass="textbox" />

        <asp:Label ID="lblTelefonoLocal" runat="server" Text="Teléfono:" CssClass="label" />
        <asp:TextBox ID="txtTelefonoLocal" runat="server" CssClass="textbox" />

        <asp:Label ID="lb1UbicaciónLocal" runat="server" Text="Ubicación:" CssClass="label" />
        <asp:TextBox ID="txtUbicacionLocal" runat="server" CssClass="textbox" />

        <asp:DropDownList ID="ddlOptions" AutoPostBack="true" OnSelectedIndexChanged="ddlOptions_SelectedIndexChanged" runat="server">

        </asp:DropDownList>

        <asp:Button ID="btnCrearLocal" runat="server" Text="Crear" OnClick="btnCrearLocal_Click" CssClass="button" />
    </asp:Panel>


    <!-- Botón para abrir el formulario de creación de menu -->
    <asp:Button ID="botonmenus" runat="server" Text="Menus" OnClick="botonOpenMenus_Click" CssClass="button-outside"/>

     <!-- Botón para abrir el formulario de creación de menu -->
    <asp:Button ID="botonCrearMenu" runat="server" Text="Crear Menu" OnClick="botonOpenCrearMenu_Click" Visible="false" CssClass="button-outside"/>

    <!-- Botón para abrir el formulario de eliminacion de menu -->
    <asp:Button ID="botonEliminarMenu" runat="server" Text="Eliminar Menu" OnClick="botonOpenEliminarMenu_Click" Visible="false" CssClass="button-outside"/>

    <!-- Botón para abrir el formulario de modificacion de menu -->
    <asp:Button ID="botonModificarMenu" runat="server" Text="Modificar Menu" OnClick="botonOpenModificarMenu_Click" Visible="false" CssClass="button-outside"/>
    
    <!-- Formulario de creación de menus -->
    <asp:Panel ID="panelcrearmenu" runat="server" Visible="false" CssClass="panel" DefaultButton="botonconfirmarmenu">
        
        <asp:Label ID="lblTitleMenu" runat="server" Text="Creador de Menu" CssClass="label" />

        <asp:Label ID="lblnombremenu" runat="server" Text="Nombre:" CssClass="label"/>
        <asp:TextBox ID="txtnombremenu" runat="server" CssClass="textbox"/>
      
        <asp:Label ID="lblpreciomenu" runat="server" Text="Precio:" CssClass="label"/>
        <asp:TextBox ID="txtpreciomenu" runat="server" CssClass="textbox" />

        <asp:Label ID="lblid1" runat="server" Text="Producto1:" CssClass="label"/>
        <asp:TextBox ID="txtproducto1" runat="server" CssClass="textbox" />
        
        <asp:Label ID="lblid2" runat="server" Text="Producto2:" CssClass="label"/>
        <asp:TextBox ID="txtproducto2" runat="server" CssClass="textbox" />

        <asp:Label ID="lblid3" runat="server" Text="Producto3:" CssClass="label"/>
        <asp:TextBox ID="txtproducto3" runat="server" CssClass="textbox" />

        <asp:Button ID="botonconfirmarmenu" runat="server" Text="Confirmar creación" OnClick="btnConfirmarMenu_Click" CssClass="button"/>
    </asp:Panel>

    <!-- Formulario de eliminacion de menus de menus -->
    <asp:Panel ID="paneleliminarmenu" runat="server" Visible="false" CssClass="panel" DefaultButton="botoneliminarmenu1">
        
        <asp:Label ID="lbleliminarmenu" runat="server" Text="Eliminador de Menu" CssClass="label" />

        <asp:Label ID="lblideliminarmenu" runat="server" Text="Id del menu:" CssClass="label"/>
        <asp:TextBox ID="txtidelminarmenu" runat="server" CssClass="textbox"/>

        <asp:Button ID="botoneliminarmenu1" runat="server" Text="Eliminar Menu" OnClick="btnEliminarMenu_Click" CssClass="button"/>
        
        <asp:Label ID="lblmensajeeliminarmenu" runat="server"></asp:Label><br />
     </asp:Panel>   
        <!-- Formulario de modificacion de menus -->
    <asp:Panel ID="panelmodmenu" runat="server" Visible="false" CssClass="panel" DefaultButton="botonmodmenu">
        
        <asp:Label ID="lblmodmen" runat="server" Text="Modificador de Menu" CssClass="label" />

        <asp:Label ID="lblidmodmenu" runat="server" Text="Id del Menu a modificar:" CssClass="label"/>
        <asp:TextBox ID="txtidmodmenu" runat="server" CssClass="textbox"/>
      
        <asp:Label ID="lblnombremodmenu" runat="server" Text="Nombre:" CssClass="label"/>
        <asp:TextBox ID="txtnombremodmenu" runat="server" CssClass="textbox"/>
      
        <asp:Label ID="lblpreciomodmenu" runat="server" Text="Precio:" CssClass="label"/>
        <asp:TextBox ID="txtpreciomodmenu" runat="server" CssClass="textbox" />

        <asp:Label ID="lblprod1modmenu" runat="server" Text="Producto1:" CssClass="label"/>
        <asp:TextBox ID="txtprod1modmenu" runat="server" CssClass="textbox" />
        
        <asp:Label ID="lblprod2modmenu" runat="server" Text="Producto2:" CssClass="label"/>
        <asp:TextBox ID="txtprod2modmenu" runat="server" CssClass="textbox" />

        <asp:Label ID="lblprod3modmenu" runat="server" Text="Producto3:" CssClass="label"/>
        <asp:TextBox ID="txtprod3modmenu" runat="server" CssClass="textbox" />

        <asp:Button ID="botonmodmenu" runat="server" Text="Confirmar modificacion" OnClick="btnModificarMenu_Click" CssClass="button"/>
    </asp:Panel>

        <asp:Panel ID="menusmostradosadmin" runat="server" Visible="false">
    
        <div class="cuadrado-blanco"></div>

    <div class="wrap column-2 carta">

        <!-- Repeater para los menus -->

        <asp:Repeater ID="rptmenus" runat="server" Visible="true">
            <ItemTemplate>
                <div class="menu-carta" data-menu-id='<%# Eval("id") %>'>
                    <div class="id-plato-carta">
                        <h4><%# Eval("Id") %></h4>
                    </div>
                    <div class="img-plato-carta">
                        <asp:Image ID=Image1 ImageUrl="/assets/images/Menus.jpg" runat="server" />
                    </div>
                    <div class="title-plato-carta">
                        <h4><%# Eval("Nombre") %></h4>
                    </div>
                    <div class="precio-plato-carta">
                        <span><%# Eval("Precio") %>€</span>
                    </div>
                </div>
            </ItemTemplate>
         </asp:Repeater>

    </div>

    <div class="cuadrado-blanco"></div>

    </asp:Panel>
             
    <!-- Formulario de creación de ofertas -->
    <asp:Panel ID="panelCrearOferta" runat="server" Visible="false" CssClass="panel">
        
        <asp:Label ID="Label1" runat="server" Text="Creador de Oferta" CssClass="label" />

        <asp:Label ID="idOferta" runat="server" Text="Id:" CssClass="label"/>
        <asp:TextBox ID="text_id" runat="server" CssClass="textbox"/>
      
        <asp:Label ID="precioOferta" runat="server" Text="Precio:" CssClass="label"/>
        <asp:TextBox ID="text_precio" runat="server" CssClass="textbox" />

        <asp:Label ID="codigoOferta" runat="server" Text="Código:" CssClass="label"/>
        <asp:TextBox ID="text_codigo" runat="server" CssClass="textbox" />

        <asp:Label ID="MensajeOferta" runat="server"></asp:Label><br />

        <asp:Button ID="ConfirmarOferta" runat="server" Text="Añadir Oferta" OnClick="crearOferta" CssClass="button"/>
        <asp:Button ID="BorrarOferta" runat="server" Text="Borrar Oferta" OnClick="borrarOferta" CssClass="button"/>
        <asp:Button ID="ModificarOferta" runat="server" Text="Modificar Oferta" OnClick="modificarOferta" CssClass="button"/>
        <asp:Button ID="MostrarOfertaPorId" runat="server" Text="Mostrar Oferta" OnClick="mostrarOferta" CssClass="button"/>

    </asp:Panel>
   
     <!-- Boton para el manejador de  Productos -->
    <asp:Button ID="botonproductos" runat="server" Text="Productos" OnClick="botonOpenProductos_Click" CssClass="button-outside"/>

     <!-- Botón para abrir el formulario de creación de producto -->
    <asp:Button ID="botonCrearProducto" runat="server" Text="Crear Producto" OnClick="botonOpenCrearProducto_Click" Visible="false" CssClass="button-outside"/>

    <!-- Botón para abrir el formulario de eliminacion de producto -->
    <asp:Button ID="botonEliminarProducto" runat="server" Text="Eliminar Producto" OnClick="botonOpenEliminarProducto_Click" Visible="false" CssClass="button-outside"/>

    <!-- Botón para abrir el formulario de modificacion de producto -->
    <asp:Button ID="botonModProducto" runat="server" Text="Modificar Producto" OnClick="botonOpenModificarProducto_Click" Visible="false" CssClass="button-outside"/>
    
    <!-- Formulario de creación de productos -->
    <asp:Panel ID="panelcrearproducto" runat="server" Visible="false" CssClass="panel" DefaultButton="botoncrearproducto1">
        
        <asp:Label ID="lblTitleProducto" runat="server" Text="Creador de Producto" CssClass="label" />

        <asp:Label ID="lblnombreproducto" runat="server" Text="Nombre:" CssClass="label"/>
        <asp:TextBox ID="txtnombreproducto" runat="server" CssClass="textbox"/>
      
        <asp:Label ID="lbldescripcionproducto" runat="server" Text="Descripcion:" CssClass="label"/>
        <asp:TextBox ID="txtdescripcionproducto" runat="server" CssClass="textbox" />

        <asp:Label ID="lblprecioproducto" runat="server" Text="Precio:" CssClass="label"/>
        <asp:TextBox ID="txtprecioproducto" runat="server" CssClass="textbox" />
        
        <asp:Label ID="lblimagenproducto" runat="server" Text="URL de la imagen:" CssClass="label"/>
        <asp:TextBox ID="txtimagenproducto" runat="server" CssClass="textbox" />

        <asp:Label ID="lbltipoproducto" runat="server" Text="Tipo:" CssClass="label"/>
        <asp:TextBox ID="txttipoproducto" runat="server" CssClass="textbox" />

        <asp:Button ID="botoncrearproducto1" runat="server" Text="Confirmar creación" OnClick="btnConfirmarProducto_Click" CssClass="button"/>
    </asp:Panel>

    <!-- Formulario de eliminacion de productos -->
    <asp:Panel ID="paneleliminarproductos" runat="server" Visible="false" CssClass="panel" DefaultButton="botoneliminarproducto1">
        
        <asp:Label ID="lbleliminarproducto" runat="server" Text="Eliminador de Producto" CssClass="label" />

        <asp:Label ID="lblideliminarproducto" runat="server" Text="Id del menu:" CssClass="label"/>
        <asp:TextBox ID="txtideliminarproducto" runat="server" CssClass="textbox"/>

        <asp:Button ID="botoneliminarproducto1" runat="server" Text="Eliminar Producto" OnClick="btnEliminarProducto_Click" CssClass="button"/>
        
        <asp:Label ID="lblmensajeeliminarproducto" runat="server"></asp:Label><br />
     </asp:Panel>   

    <!-- Formulario de modificacion de productos -->
    <asp:Panel ID="panelmodproducto" runat="server" Visible="false" CssClass="panel" DefaultButton="botonmodificarproducto">
        
        <asp:Label ID="lblmodproducto" runat="server" Text="Modificador de Producto" CssClass="label" />

        <asp:Label ID="lblidmodproducto" runat="server" Text="Id del Producto a modificar:" CssClass="label"/>
        <asp:TextBox ID="txtidmodproducto" runat="server" CssClass="textbox"/>

        <asp:Label ID="lblnombremodproducto" runat="server" Text="Nombre:" CssClass="label"/>
        <asp:TextBox ID="txtnombremodproducto" runat="server" CssClass="textbox"/>
      
        <asp:Label ID="lbldescripcionmodproducto" runat="server" Text="Descripcion:" CssClass="label"/>
        <asp:TextBox ID="txtdescripcionmodproducto" runat="server" CssClass="textbox" />

        <asp:Label ID="lblpreciomodproducto" runat="server" Text="Precio:" CssClass="label"/>
        <asp:TextBox ID="txtpreciomodproducto" runat="server" CssClass="textbox" />
        
        <asp:Label ID="lblurlmodproducto" runat="server" Text="URL de la imagen:" CssClass="label"/>
        <asp:TextBox ID="txturlmodproducto" runat="server" CssClass="textbox" />

        <asp:Label ID="lbltipomodproducto" runat="server" Text="Tipo:" CssClass="label"/>
        <asp:TextBox ID="txttipomodproducto" runat="server" CssClass="textbox" />

        <asp:Button ID="botonmodificarproducto" runat="server" Text="Confirmar creación" OnClick="btnModificarProducto_Click" CssClass="button"/>
    </asp:Panel>

    <!-- Repeater para los Entrantes -->

    <asp:Panel ID="productosmostraradmin" runat="server" Visible="false">
    <div id ="entrantes">
               <div class="wrap-title-section">
                   <h2>Entrantes</h2>
               </div>
               <div class="wrap column-2 carta">

                   <asp:Repeater ID="rptEntrantes" runat="server">
                        <ItemTemplate>
                            <div class="plato-carta" data-prod-id='<%# Eval("idProd") %>'>
                                <div class="id-plato-carta">
                                    <h4><%# Eval("idProd") %></h4>
                                </div>
                                <div class="img-plato-carta">
                                    <asp:Image ID=Image1 ImageUrl='<%# Eval("imagenProd") %>' runat="server" />
                                </div>
                                <div class="title-plato-carta">
                                    <h4><%# Eval("nombreProd") %></h4>
                                    <p><%# Eval("descripcionProd") %></p>
                                </div>
                                <div class="precio-plato-carta">
                                    <span><%# Eval("precioProd") %>€</span>
                                </div>
                            </div>
                        </ItemTemplate>
                    </asp:Repeater>

                  </div>
                  </div>
                   
        <!-- Repeater para los productos tipo Kebab -->

               <div id ="kebab">
               <div class="wrap-title-section">
                   <h2>Kebab</h2>
               </div>
               <div class="wrap column-2 carta">

                   <asp:Repeater ID="rptKebab" runat="server">
                        <ItemTemplate>
                            <div class="plato-carta" data-prod-id='<%# Eval("idProd") %>'>
                                <div class="id-plato-carta">
                                    <h4><%# Eval("idProd") %></h4>
                                </div>
                                <div class="img-plato-carta">
                                    <asp:Image ID=imgProducto ImageUrl='<%# Eval("imagenProd") %>' runat="server" />
                                </div>
                                <div class="title-plato-carta">
                                    <h4><%# Eval("nombreProd") %></h4>
                                    <p><%# Eval("descripcionProd") %></p>
                                </div>
                                <div class="precio-plato-carta">
                                    <span><%# Eval("precioProd") %>€</span>
                                </div>
                            </div>
                        </ItemTemplate>
                    </asp:Repeater>



               </div>
               </div>

        <!-- Repeater para los Postres -->

               <div id ="postres">
                <div class="wrap-title-section">
                   <h2>Postres</h2>
               </div>
               <div class="wrap column-2 carta">

                   <asp:Repeater ID="rptPostres" runat="server">
                        <ItemTemplate>
                            <div class="plato-carta" data-prod-id='<%# Eval("idProd") %>'>
                                <div class="id-plato-carta">
                                    <h4><%# Eval("idProd") %></h4>
                                </div>
                                <div class="img-plato-carta">
                                    <asp:Image ID=imgProducto ImageUrl='<%# Eval("imagenProd") %>' runat="server" />
                                </div>
                                <div class="title-plato-carta">
                                    <h4><%# Eval("nombreProd") %></h4>
                                    <p><%# Eval("descripcionProd") %></p>
                                </div>
                                <div class="precio-plato-carta">
                                    <span><%# Eval("precioProd") %>€</span>
                                </div>
                            </div>
                        </ItemTemplate>
                    </asp:Repeater>

                
                   </div>
               </div> 

        <!-- Repeater para las Bebidas -->

               <div id ="bebidas">
                <div class="wrap-title-section">
                   <h2>Bebidas</h2>
               </div>
               <div class="wrap column-2 carta">

                    <asp:Repeater ID="rptBebidas" runat="server">
                        <ItemTemplate>
                            <div class="plato-carta" data-prod-id='<%# Eval("idProd") %>'>
                                <div class="id-plato-carta">
                                    <h4><%# Eval("idProd") %></h4>
                                </div>
                                <asp:HiddenField ID="hiddenProdId" Value=<%# Eval("idProd") %> runat="server" />
                                <div class="img-plato-carta">
                                    <asp:Image ID=imgProducto ImageUrl='<%# Eval("imagenProd") %>' CssClass="hidden-prod-id" runat="server" />
                                </div>
                                <div class="title-plato-carta">
                                    <h4><%# Eval("nombreProd") %></h4>
                                    <p><%# Eval("descripcionProd") %></p>
                                </div>
                                <div class="precio-plato-carta">
                                    <span><%# Eval("precioProd") %>€</span>
                                </div>
                            </div>
                        </ItemTemplate>
                    </asp:Repeater>

               </div>
               </div>
        <div class="cuadrado-blanco"></div>

     </asp:Panel>

    <!-- Formulario de creación de empleado -->
    <asp:Panel ID="Panelcrearempleado" runat="server" Visible="false" CssClass="panel">
        <asp:Label ID="Label2" runat="server" Text="Creador de Empleado" CssClass="label" />
        <asp:Label ID="Label3" runat="server" Text="Email:" CssClass="label" />
        <asp:TextBox ID="TextBox1" runat="server" CssClass="textbox" />
        <asp:Label ID="Label4" runat="server" Text="Contraseña:" CssClass="label" />
        <asp:TextBox ID="TextBox2" runat="server" CssClass="textbox" />
        <asp:Label ID="Label5" runat="server" Text="Nombre:" CssClass="label" />
        <asp:TextBox ID="TextBox3" runat="server" CssClass="textbox" />
        <asp:Label ID="Label6" runat="server" Text="DNI:" CssClass="label" />
        <asp:TextBox ID="TextBox4" runat="server" CssClass="textbox" />        
        <asp:Label ID="Label7" runat="server" Text="Salario" CssClass="label" />
        <asp:TextBox ID="TextBox5" runat="server" CssClass="textbox" />

        <asp:Button ID="Button3" runat="server" Text="Crear" OnClick="btnCrearEmpleado_Click" CssClass="button" />
    </asp:Panel>

</asp:Content>

