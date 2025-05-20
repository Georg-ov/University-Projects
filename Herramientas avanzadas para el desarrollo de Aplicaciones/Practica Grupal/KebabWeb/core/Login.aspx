<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="Login.aspx.cs" Inherits="KebabWeb.core.Login" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login y Register - MagtimusPro</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/Login.css">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    <main>
        <div class="contenedor__todo">
            <div class="caja__trasera">
                <div class="caja__trasera-login">
                    <h3>¿Ya tienes una cuenta?</h3>
                    <p>Inicia sesión para entrar en la página</p>
                    <button id="btn__iniciar-sesion">Iniciar Sesión</button>
                </div>
                <div class="caja__trasera-register">
                    <h3>¿Aún no tienes una cuenta?</h3>
                    <p>Regístrate para que puedas iniciar sesión</p>
                    <button id="btn__registrarse">Regístrarse</button>
                </div>
            </div>
            <div class="contenedor__login-register">
                <!--Login-->
                <div class="formulario__login">
                    <h2>Iniciar Sesión</h2>
                    <asp:TextBox ID="EmailTextBox" runat="server" CssClass="input" placeholder="Correo Electronico"></asp:TextBox>
                    <asp:TextBox ID="PasswordTextBox" runat="server" CssClass="input" TextMode="Password" placeholder="Contraseña"></asp:TextBox>
                    <asp:Button ID="LoginButton" runat="server" CssClass="input" Text="Entrar" OnClick="LoginButton_Click" />
                </div>
           

                <!--Register-->
                <div class="formulario__register">
                    <h2>Regístrarse</h2>
                    <asp:TextBox ID="NameTextBox" runat="server" CssClass="input" placeholder="Nombre Completo"></asp:TextBox>
                    <asp:TextBox ID="EmailTextBox2" runat="server" CssClass="input" placeholder="Correo Electronico"></asp:TextBox>
                    <asp:TextBox ID="dniTextBox" runat="server" CssClass="input" placeholder="DNI"></asp:TextBox>
                    <asp:TextBox ID="PasswordTextBox2" runat="server" CssClass="input" placeholder="Contraseña"></asp:TextBox>
                    <asp:Button ID="RegisterButton" runat="server" CssClass="input" Text="Registrarse" OnClick="RegisterButton_Click" />
                </div>
            </div>
        </div>
    </main>
    <script src="/scripts/Login.js"></script>
</asp:Content>
