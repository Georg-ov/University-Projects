<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="Locales.aspx.cs" Inherits="KebabWeb.core.Locales" EnableEventValidation="false" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
        <link rel="stylesheet" href="/css/Locales.css">
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    <h1>Listado de Locales</h1>
    <table>
        <tr>
            <th class="box">ID</th>
            <th class="box">Nombre</th>
            <th class="box">Dirección</th>
            <th class="box">Ciudad</th>
            <th class="box">Teléfono</th>
            <th class="box">Acciones</th>
        </tr>
        <tr>
            <asp:Repeater ID="rptLocales" runat="server">
                <ItemTemplate>
                    <div class="container-local">
                        <div class="box">
                            <asp:Label ID="lblId" CssClass="id-local" Text='<%# Eval("ID") %>' runat="server" />
                        </div>
                            <div class="box">
                                <asp:Label ID="lblNombre" CssClass="nombre-local" Text='<%# Eval("nombre") %>' runat="server" />
                            </div>
                            <div class="box">
                                <asp:Label ID="lblDireccion" CssClass="direccion-local" Text='<%# Eval("direccion") %>' runat="server" />
                            </div>
                            <div class="box">
                                <asp:Label ID="lblCiudad" CssClass="ciudad-local" Text='<%# Eval("Ciudad") %>' runat="server" />
                            </div>
                            <div class="box">
                                <asp:Button ID="btnEliminar" CssClass="btn-eliminar" CommandArgument='<%# Eval("ID") %>' Text="Eliminar" OnClick="btnEliminar_Click" runat="server" />
                            </div>
                    </div>
                </ItemTemplate>
            </asp:Repeater>
        </tr>

    </table>
</asp:Content>
