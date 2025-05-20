<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="Incidencias.aspx.cs" Inherits="KebabWeb.core.Incidencias" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
    <link rel="stylesheet" href="/css/Incidencias.css" />
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
        <asp:Repeater ID="rptIncidencias" runat="server">
            <ItemTemplate>
                <div class="incidencia">
                    <div class="mensaje_id">
                        <asp:Label ID="lblMensajeId" CssClass="mensaje-id" Text='<%# Eval("mensaje_id") %>' runat="server" />
                    </div>
                    <div class="mensaje">
                        <asp:Label ID="lblMensaje" CssClass="mensaje-texto" Text='<%# Eval("mensaje") %>' runat="server" />
                    </div>
                    <div class="acciones">
                        <asp:Button ID="btnEliminar" CssClass="btn-eliminar" CommandArgument='<%# Eval("mensaje_id") %>' Text="Eliminar" OnClick="btnEliminar_Click" runat="server" />
                    </div>
                </div>
            </ItemTemplate>
        </asp:Repeater>

</asp:Content>
