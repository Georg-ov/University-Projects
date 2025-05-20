<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="Default.aspx.cs" Inherits="KebabWeb.Default" %>
<asp:Content ID="Content1" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    
<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Products</title>
    <link rel="stylesheet" href="/css/Default.css" />
</head>
    
    <header class="content header">
        <!-- El contenido que tienes en tu archivo HTML original -->
        <img src="/assets/images/logoGrande.png" alt="Icono"/>
    </header>

    <section class="content sau">
        <!-- El contenido que tienes en tu archivo HTML original -->
        <div class="order-container">
            <div class="order-foto">
                <img src="/assets/images/box.jpg" alt="Foto de la revisión"/>

                <img src="/assets/images/menu.jpg" alt="Foto de la revisión"/>

                <img src="/assets/images/rollo.jpg" alt="Foto de la revisión"/>

                <img src="/assets/images/bebidas.jpg" alt="Foto de la revisión"/>
            </div>
        </div>
        <div class="btn-order">
            <a href="/core/Productos.aspx"> Order now</a>
        </div>
    </section>

    <section class="content about">
        <div class="reviews-container">
            <div class="review-box">
                <div class="client-photo-container">
                    <img src="/assets/images/premio1.png" alt="Foto cliente 1" class="client-photo">
                </div>
                <h3>Review</h3>
                <p>"Este local de kebab es simplemente excepcional. Desde el primer bocado, supe que estaba disfrutando de algo especial.
                    El kebab estaba perfectamente sazonado y cocido, y las porciones eran más que generosas. 
                    El ambiente del local es cálido y acogedor, y el personal es atento y amable. 
                    Sin duda, este es un lugar al que volveré."</p>
                <p class="author">- Felipe VI</p>
            </div>
            <div class="review-box">
                <div class="client-photo-container">
                    <img src="/assets/images/premio1.png" alt="Foto cliente 1" class="client-photo">
                </div>
                <h3>Review</h3>
                <p>"He probado muchos lugares de kebab a lo largo de los años, pero este local se lleva la palma. 
                    La calidad de la carne es superior, y la variedad de salsas y aderezos es impresionante.
                    Además, el servicio es rápido y eficiente, lo que hace que la experiencia sea aún mejor.
                    ¡Altamente recomendado!"</p>
                <p class="author">- Maria Becerra</p>
            </div>
            <div class="review-box">
                <div class="client-photo-container">
                    <img src="/assets/images/premio1.png" alt="Foto cliente 1" class="client-photo">
                </div>
                <h3>Review</h3>
                <p>"Este local de kebab es una verdadera joya oculta. 
                    La comida es siempre fresca y deliciosa, y la atención al detalle es evidente en cada plato. 
                    Además, el local tiene un ambiente único y vibrante que te hace sentir como en casa. 
                    Si estás buscando el mejor kebab de la ciudad, este es el lugar."</p>
                <p class="author">- Kanye West</p>
            </div>
        </div>

        <asp:Button ID="ComentariosButton" runat="server" Text="Comentarios" OnClick="ComentariosButton_Click" Visible="false" CssClass="btn-nosotros"/>

        
    </section>

    <section class="seccion-derecha">
            <div>
                <h1 class="about-us">SOBRE</h1>
                <h2 class="description">nuestros restaurantes</h2>
                <hr style="border-top: 1px solid black; width:65%; margin-left:17.5%; margin-top:2px" />
            </div>
            <div class="horizontal-container">
                <div style="flex: 1">
                    <div class="button-container" style="margin-top:2%;">
                        <button style="cursor:pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.678.678 0 0 0 .178.643l2.457 2.457a.678.678 0 0 0 .644.178l2.189-.547a1.745 1.745 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.634 18.634 0 0 1-7.01-4.42 18.634 18.634 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877L1.885.511z" />
                            </svg>
                        </button>
                        <button style="cursor:pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
                                <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z" />
                            </svg>
                        </button>
                        <button style="cursor:pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555ZM0 4.697v7.104l5.803-3.558L0 4.697ZM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757Zm3.436-.586L16 11.801V4.697l-5.803 3.546Z" />
                            </svg>
                        </button>
                    </div>

                    <div class="horario">
                        <h3 class="horario-title">HORARIO</h3>
                        <p class="horario-text">LUNES 24H</p>
                        <p class="horario-text">MARTES 24H</p>
                        <p class="horario-text">MIERCOLES 24H</p>
                        <p class="horario-text">JUEVES 24H</p>
                        <p class="horario-text">VIERNES 24H</p>
                        <p class="horario-text">SABADO 24H</p>

                    </div>
                </div>
                <div class="phone">
                    <h3 class="horario-title">TELÉFONO</h3>
                    <p class="horario-text">+34 673543112</p>
                    <div class="btn-nosotros">
                        <a href="/core/SobreNosotros.aspx"> Sobre Nosotros</a>
                    </div>
                </div>
                
            </div>
        </section>    

    <section class="content price">
        <!-- El contenido que tienes en tu archivo HTML original -->
    </section>
       

</asp:Content>