<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="Mapa.aspx.cs" Inherits="KebabWeb.core.Mapa" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Mapa</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/Mapa.css"/>
</head>
<body>    
    <div class="container">
        <!--Logo de kebab implementado con ASP.NET-->
        <asp:Image id="imagen" src="/assets/images/logo1_2.png" CssClass="logo2" runat="server"/>
        <div class="locales">
            <h4 style="color:white">Elegir Local</h4>
            <asp:DropDownList ID="Locales" runat="server" AutoPostBack="true" OnSelectedIndexChanged="Cargar_Local">
            </asp:DropDownList>
        </div>
        <!--Div que dentro tiene insertado un mapa con la ubicación del local-->
        <div class="map">
            <iframe id="Ubicación_Local" runat="server" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <!--Div con el nombre del kebab y su dirección-->
        <div class="header">
            <asp:Label ID="Nombre_Local" runat="server"></asp:Label>
            <asp:Label ID="Direccion_Local" runat="server"></asp:Label>
        </div>
    </div>
</body>
</html>
</asp:Content>