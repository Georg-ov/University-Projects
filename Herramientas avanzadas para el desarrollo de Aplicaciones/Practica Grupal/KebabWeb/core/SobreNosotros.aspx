<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="SobreNosotros.aspx.cs" Inherits="KebabWeb.core.SobreNosotros" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">

<!DOCTYPE html>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title></title>
    <link rel="stylesheet" type="text/css" href="../css/Nosotros.css"> 
</head>

<body>
    <!-- cabecera -->
    <div id="cabecera"> 
        <h1>HALAL FOR ALL</h1>
        <div class="btn-order">
            <a href="/core/Productos.aspx"> Order now</a>
        </div>
    </div>

    <!--primera seccion-->
    <div id="section1">
        <!--quienes somos-->
        <div class="pium">
            <img class="imagen1" src="../assets/images/cocinero.jpg">
            <h2>¿Quiénes somos?</h2>
            <p>Kebab Amigo es la empresa líder en kebab delivery a nivel nacional. Operamos en varios locales ofreciendo buena calidad de kebab en cada uno de ellos.</p>
        </div>

        <!--we deliver!-->
        <div class="pium">
            <img class="imagen2" src="../assets/images/rollo.jpg">
            <h2>Máxima Calidad</h2>
            <p>Nuestra capacidad cocina, garantizando siempre el mejor servicio y experiencia en todas los locales en los que operamos, gracias a nuestro modelo de negocio integrado horizaontalmente que nos permite operar con la máxima eficiencia como franquicia.</p>
        </div>
     </div>

    <!--segunda seccion!-->
    <div id="section2">
        <div class="grr">
            <h1>Franquicias</h1>
            <p>En Kebab Amigo contamos con importantes aliados que comparten nuestros valores y que nos permiten llegar a más clientes y mejorar nuestros servicios: los franquiciados. Gracias a este modelo y, al de Máster Franquicias, tenemos un ambicioso plan de expansión, ampliamos nuestro mercado potencial y conseguimos estar presentes en diferentes zonas.</p>
        </div>
        <div class="grr">
            <h1>Prensa</h1>
            <p>Conoce las últimas noticias sobre Kebab Amigo. Si eres periodista o necesitas información sobre la compañía o alguna de las cuatro marcas, no dudes en contactar con nosotros a través del correo</p>
            <a href="mailto:kebabamigo@gmail.com">kebabamigo@gmail.com</a>
        </div>


    </div>
    
</body>
</html>
</asp:Content>
