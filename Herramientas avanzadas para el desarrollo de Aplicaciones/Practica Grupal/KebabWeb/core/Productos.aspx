<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="Productos.aspx.cs" Inherits="KebabWeb.core.Productos"%>
<%@ Import Namespace="Library" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
    <link rel="stylesheet" href="/css/Productos.css" />
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    
<main>
    <asp:Panel ID="productos" runat="server">
        <div class="header-prod">
            <nav class="navbar1">
                <asp:HyperLink NavigateUrl="/core/Pago.aspx" Text="Pagos" runat="server" />
                <asp:HyperLink NavigateUrl="#oferta" Text="Oferta" runat="server" />
                <asp:Hyperlink NavigateUrl="#carrito" ID="cart" runat="server">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart-fill" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                </asp:Hyperlink>
            </nav>
            <div id="carritoSlider" class="slider">
                <div class="slider-content">
                    <span id="closeSlider-carrito" class="close-slider">&times;</span>
                    <h2>Carrito</h2>
                          <asp:Repeater ID="CartRepeater" runat="server">
                            <HeaderTemplate>
                                <table id="carritoTable">
                            </HeaderTemplate>
                            <ItemTemplate>
                                <tr>
                                   <td><%# ((Tuple<ENProducto, int>)Container.DataItem).Item1.nombreProd %> x<%# ((Tuple<ENProducto, int>)Container.DataItem).Item2 %></td>
                                    <td><%# ((Tuple<ENProducto, int>)Container.DataItem).Item1.precioProd.ToString("C") %></td>
                            </ItemTemplate>
                            <FooterTemplate>
                                
                                </table>
                            </FooterTemplate>
                        </asp:Repeater>
                        <asp:Repeater ID="CartMenuRepeater" runat="server">
                            <HeaderTemplate>
                                <table id="carritoTable">
                            </HeaderTemplate>
                                <ItemTemplate>
                                    <tr>
                                        <td>
                                            <%# Eval("Item1.nombre") %> x<%# Eval("Item2") %>
                                        </td>
                                        <td>
                                            <%# DataBinder.Eval(Container.DataItem, "Item1.Precio", "{0:C}") %>
                                        </td>
                                    </tr>
                            </ItemTemplate>
                            <FooterTemplate>
                              
                                </table>
                            </FooterTemplate>
                        </asp:Repeater> 
                    <table>
                    <tr>
                        <td>
                            <asp:Label ID="TotalLabel" runat="server"/>
                        </td>
                        </tr>
                        <asp:DropDownList ID="LocalesDropDownList" AutoPostBack="true" runat="server" OnSelectedIndexChanged="LocalesDropDownList_SelectedIndexChanged" />
                        <tr>
                    
                    <td>

                    </td>
                    </tr>
                    <tr>
                    <td>
                            <asp:Button ID="OrderButton" OnClick="OrderButtonCart" Text="Order now" runat="server" style="display: block; margin-top: 10px; padding: 10px; background-color: #B22222; color: #fff; text-decoration: none; border-radius: 5px;" />
                    </td>
                    </tr>
                </table>
                </div>
            </div>
            <div class="overlay"></div>
            <div class="main-content">
              
    <!-- Slider de las Ofertas -->
            </div>
            <div id="ofertaSlider" class="slider">
                <div class="slider-content">
                    <span id="closeSlider-oferta" class="close-slider">&times;</span>
                    <h2>Ofertas</h2>
                    <div>
                        <asp:Repeater ID="MostrarOfertas" runat="server">
                            <ItemTemplate>
                                <div style="background-color:#cccccc; margin-top:30px; border: 1px solid black; border-radius: 5px;">
                                    <div style="margin:10px;">
                                        <div style="margin-bottom:10px; display: flex; flex-direction: column; justify-content: center; align-items: center;"><strong>Oferta: <%# Eval("id") %></strong> </div>
                                        <div style="margin-top:5px" > <strong>Precio con descuento:</strong> <%# Eval("precio") %>€</div>
                                        <div style="margin-top:5px" > <strong>Codigo:</strong> <%# Eval("codigo") %></div>
                                    </div>
                                </div>
                            </ItemTemplate>
                        </asp:Repeater>
                    </div>
                </div>
            </div>
            <div class="overlay"></div>
            <div class="main-content">
            </div>

        <!-- Contenido principal de la página -->
        </div>
        <div class="blur-slider"/>
        <div class="backgroundImg">
        </div>

        <div class="productos">
            <nav class="navbar2">
                <asp:HyperLink NavigateUrl="#menus" Text="Menús" runat="server" />
                <asp:HyperLink NavigateUrl="#entrantes" Text="Entrantes" runat="server" />
                <asp:HyperLink NavigateUrl="#kebab" Text="Kebab" runat="server" />
                <asp:HyperLink NavigateUrl="#postres" Text="Postres" runat="server" />
                <asp:HyperLink NavigateUrl="#bebidas" Text="Bebidas" runat="server" />
            </nav>
        </div>
        
           <!-- Buscador de productos -->
        <div class="buscarproducto">
            <asp:Panel runat="server" DefaultButton="btnBuscarProductos">
                <asp:TextBox ID="txtBusqueda" runat="server" CssClass="buscarproducto-input" placeholder="Ingrese su búsqueda..."></asp:TextBox>
                <asp:Button ID="btnBuscarProductos" runat="server" CssClass="buscarproducto-button" Text="Buscar" OnClick="btnBuscarProducto_Click" />
            </asp:Panel>
        </div>

        
        <div>
            <asp:Label ID="lblMensaje" runat="server" CssClass="mensaje-error" Visible="false"></asp:Label>
        </div>

        <!-- Repeater para los productos encontrados -->

        <div>
            <asp:Label ID="lblencontrados" runat="server" CssClass="mensaje-encontrados" Visible="false"></asp:Label>
        </div>
        <div class="wrap column-2 carta">
            <asp:Repeater ID="rptrproductosencontrados" runat="server">
                <ItemTemplate>
                    <div class="plato-carta" data-prod-id='<%# Eval("idProd") %>'>
                        <div class="img-plato-carta">
                            <asp:Image ID="Image1" ImageUrl='<%# Eval("imagenProd") %>' runat="server" />
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

        <!-- Repeater para los menus -->

          <div id ="menus">
                <div class="wrap-title-section">
                   <h2>Menus</h2>
               </div>
               <div class="wrap column-2 carta">

                   <asp:Repeater ID="rptmenus" runat="server">
                        <ItemTemplate>
                            <div class="menu-carta" data-menu-id='<%# Eval("id") %>'>
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
               </div> 

        <!-- Repeater para los entrantes -->

               <div id ="entrantes">
               <div class="wrap-title-section">
                   <h2>Entrantes</h2>
               </div>
               <div class="wrap column-2 carta">

                   <asp:Repeater ID="rptEntrantes" runat="server">
                        <ItemTemplate>
                            <div class="plato-carta" data-prod-id='<%# Eval("idProd") %>'>
          
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
                   
        <!-- Repeater para los productos catalogados como Kebab -->

               <div id ="kebab">
               <div class="wrap-title-section">
                   <h2>Kebab</h2>
               </div>
               <div class="wrap column-2 carta">

                   <asp:Repeater ID="rptKebab" runat="server">
                        <ItemTemplate>
                            <div class="plato-carta" data-prod-id='<%# Eval("idProd") %>'>
                            
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


        <div id="productModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
            <asp:HiddenField ID="hiddenProductId" runat="server" />
            <asp:Image ID="productImg" ImageUrl="" alt="Imagen del producto" runat="server" ClientIDMode="Static" />
            <h2 id="productTitle">Nombre del producto</h2>
            <p id="productDesc">Información breve del producto...</p>
            <div id="ingredientsList">
        </div>
        <asp:Button ID="addToCart" CssClass="addToCart" Text="Añadir a la cesta" OnClick="AddToCart_Click" runat="server"/>
    </div>
</div>

        <div id="menuModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <asp:HiddenField ID="hiddenMenuId" runat="server" />
        <asp:Image ID="menuImg" ImageUrl="" alt="Imagen del menú" runat="server" ClientIDMode="Static" />
        <h2 id="menuTitle">Nombre del menú</h2>
        <p id="menuPrice">Precio del menú...</p>
        <div id="menuList">
        </div>
        <asp:Button ID="addToCartMenu" CssClass="addToCartMenu" Text="Añadir a la cesta" OnClick="AddToCartMenu_Click"  runat="server"/>
    </div>
</div>
 
        
    </asp:Panel>
    <script>
        document.querySelectorAll('.plato-carta').forEach(square => {
            square.addEventListener('click', function () {
                var productId = this.dataset.prodId;
                var productName = this.querySelector('.title-plato-carta h4').innerText;
                var productDesc = this.querySelector('.title-plato-carta p').innerText;
                var productImg = this.querySelector('.img-plato-carta img').src;
                

                // Actualiza la información del modal con los datos del producto
                document.getElementById('<%= hiddenProductId.ClientID %>').value = productId;
                document.getElementById('productTitle').innerText = productName;
                document.getElementById('productDesc').innerText = productDesc;
                document.getElementById('productImg').src = productImg;

                // Mostrar la ventana modal
                document.getElementById('productModal').style.display = 'block';

                // Borroso
                document.getElementById('form1').classList.add('blur-product');

            });
        });

        // Obtener el elemento 
        var closeMenuModal = document.getElementById('productModal').querySelector('.close');

        // Quitar Borroso
        closeMenuModal.onclick = function () {
            document.getElementById('productModal').style.display = 'none';
            document.getElementById('form1').classList.remove('blur-product');
        }

       
        // Boton añadir a la cesta
        document.getElementById('addToCart').addEventListener('click', function () {

            __doPostBack('<%= addToCart.ClientID %>', '');

            // Mostrar el carrito
            document.getElementById("carritoSlider").classList.toggle("open");
            document.querySelector(".blur-slider").classList.toggle("blur");
            
            });

    </script>

   

    <script>
        document.querySelectorAll('.menu-carta').forEach(menu => {
            menu.addEventListener('click', function () {
                var menuId = this.dataset.menuId;
                var menuName = this.querySelector('.title-plato-carta h4').innerText;
                var menuPrice = this.querySelector('.precio-plato-carta span').innerText;
                var menuImg = this.querySelector('.img-plato-carta img').src;

                // Actualiza la información del modal con los datos del menú
                document.getElementById('<%= hiddenMenuId.ClientID %>').value = menuId;
                document.getElementById('menuTitle').innerText = menuName;
                document.getElementById('menuPrice').innerText = menuPrice;
                document.getElementById('menuImg').src = menuImg;

                // Muestra la ventana modal
                document.getElementById('menuModal').style.display = 'block';

                // Borroso
                document.getElementById('form1').classList.add('blur-menu');
            });
        });

        // Obtener el elemento 
        var closeMenuModal = document.getElementById('menuModal').querySelector('.close');

        // Quitar Borroso
        closeMenuModal.onclick = function () {
            document.getElementById('menuModal').style.display = 'none';
            document.getElementById('form1').classList.remove('blur-product');
        }

        //Cerrar ventana si clicka fuera
        window.onclick = function (event) {
            if (event.target == document.getElementById('productModal')) {
                document.getElementById('productModal').style.display = 'none';
                document.getElementById('form1').classList.remove('blur-product');
            }
            else if (event.target == document.getElementById('menuModal')) {
                document.getElementById('menuModal').style.display = 'none';
                document.getElementById('form1').classList.remove('blur-menu');
            }
        }

        // Añadir a la cesta
        document.getElementById('addToCartMenu').addEventListener('click', function () {
            __doPostBack('<%= addToCartMenu.ClientID %>', '');


            // Muestra el carrito
            document.getElementById("carritoSlider").classList.toggle("open");
            document.querySelector(".blur-slider").classList.toggle("blur");
        });
    </script>  
    
    <script> 
         function closeAllSliders(sliderToIgnore) {
            var sliders = document.querySelectorAll('.slider.open');
            sliders.forEach(function(slider) {
                if (slider !== sliderToIgnore) {
                    slider.classList.remove('open');
                }
            });

             if (document.querySelectorAll('.slider.open').length === 0) {
                 document.querySelector('.blur-slider').classList.remove('blur');
             }
        }
    </script>
    <script>
    document.querySelector(".navbar1 a[href='#carrito']").addEventListener("click", function (event) {
            event.preventDefault();
            closeAllSliders(carritoSlider);
            document.getElementById("carritoSlider").classList.toggle("open");
            document.querySelector(".blur-slider").classList.toggle("blur"); 
        });

        document.getElementById('closeSlider-carrito').addEventListener('click', function () {
            document.getElementById('carritoSlider').classList.remove('open');
            document.querySelector('.overlay').classList.remove('open');
            document.querySelector('.blur-slider').classList.remove('blur');
        });

    </script>
    <script>
    document.querySelector(".navbar1 a[href='#oferta']").addEventListener("click", function (event) {
            event.preventDefault();
            closeAllSliders(ofertaSlider);
            document.getElementById("ofertaSlider").classList.toggle("open");
            document.querySelector(".blur-slider").classList.toggle("blur"); 
        });

        document.getElementById('closeSlider-oferta').addEventListener('click', function () {
            document.getElementById('ofertaSlider').classList.remove('open');
            document.querySelector('.overlay').classList.remove('open');
            document.querySelector('.blur-slider').classList.remove('blur');
        });

    </script>


</main>

</asp:Content>
