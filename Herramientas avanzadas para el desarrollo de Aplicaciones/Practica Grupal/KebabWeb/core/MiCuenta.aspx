<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="MiCuenta.aspx.cs" Inherits="KebabWeb.core.MiCuenta" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
    <link rel="stylesheet" type="text/css" href="css/MiCuenta.css">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet"/>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.1/mdb.min.css" rel="stylesheet"/>
</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">
    <ul class="nav nav-pills nav-justified mb-3" id="ex1" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ex3-tab-1" data-mdb-toggle="pill" href="#ex3-pills-1" role="tab" aria-controls="ex3-pills-1" aria-selected="true">Modificar email</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ex3-tab-2" data-mdb-toggle="pill" href="#ex3-pills-2" role="tab" aria-controls="ex3-pills-2" aria-selected="false">Modificar Contraseña</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="ex3-tab-3" data-mdb-toggle="pill" href="#ex3-pills-3" role="tab" aria-controls="ex3-pills-3" aria-selected="false">Pedidos Realizados</a>
        </li>
        <li class="nav-item" role="presentation">
            <asp:Button class="nav-link puta"  id="botonCerrarSesion" Text = "Cerrar sesión" data-mdb-toggle="pill" role="tab" onClick="btnCerrarSesion" aria-controls="ex3-pills-4" aria-selected="false" runat="server"/>
        </li>
    </ul>

    <div class="tab-content" id="ex2-content">
        <!-- Primer tab-->
        <div class="tab-pane fade" id="ex3-pills-1" role="tabpanel" aria-labelledby="ex3-tab-1">
            <div class="form-outline">
                <asp:TextBox ID="typeEmail" runat="server" TextMode="Email" CssClass="form-control" />
                <label class="form-label" for="typeEmail">Email Nuevo</label>
            </div>
            <div class="form-outline">
                <asp:TextBox ID="typePasswordEmailChange" runat="server" TextMode="Password" CssClass="form-control" />
                <label class="form-label" for="typePassword">Contraseña</label>
            </div>
                <asp:Button ID="btnChangeEmail" runat="server" Text="Guardar" OnClick="btnChangeEmail_Click" CssClass="btn btn-primary"/>
        </div>

        <!-- Segundo tab-->
        <div class="tab-pane fade" id="ex3-pills-2" role="tabpanel" aria-labelledby="ex3-tab-2">
            <div class="form-outline">
                <asp:TextBox ID="typePasswordOld" runat="server" TextMode="Password" CssClass="form-control" />
                <label class="form-label" for="typePasswordOld">Contraseña</label>
            </div>
            <div class="form-outline">
                <asp:TextBox ID="typePasswordNew" runat="server" TextMode="Password" CssClass="form-control" />
                <label class="form-label" for="typePasswordNew">Contraseña nueva</label>
            </div>
            <div class="form-outline">
                <asp:TextBox ID="typePasswordNewR" runat="server" TextMode="Password" CssClass="form-control" />
                <label class="form-label" for="typePasswordNewR">Repita nueva contraseña</label>
            </div>
            <asp:Label ID="lblErrorMessage" runat ="server" CssClass="form-label"></asp:Label>
            
            <asp:Button ID="cambiarContra" runat="server" Text="Cambiar constraseña" OnClick="btnChangePassword_Click" CssClass="btn btn-primary"/>
            
        </div>

        <!-- Tercer tab-->
        <div class="tab-pane fade" id="ex3-pills-3" role="tabpanel" aria-labelledby="ex3-tab-3">
            <table class="table align-middle mb-0 bg-white">
                <thead class="bg-light">
                    <tr>

                        <th>Número de pedido</th>
                        <th>Local</th>
                        <th>Fecha de pedido</th>
                        <th>Total</th>
                        <th>Actions</th>

                    </tr>
                </thead>
                <tbody>
                    <asp:Panel runat="server" CssClass="table-row">
                        <asp:Repeater ID="rptPedidos" runat="server">
                            <ItemTemplate>
                                <tr class="pedidos-local">
                                    <td>
                                        <asp:Label Text='<%# Eval("codP") %>' runat="server" />
                                    </td>
                                    <td>
                                        <asp:Label Text='<%# Eval("idlocal") %>' runat="server" />
                                    </td>
                                    <td>
                                        <asp:Label Text='<%# Eval("fecha") %>' runat="server" />
                                    </td>
                                    <td>
                                        <asp:Label Text='<%# Eval("precio") %>' runat="server" />
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-link btn-sm btn-rounded">Comentar Pedido</button>
                                    </td>
                                </tr>
                            </ItemTemplate>
                        </asp:Repeater>
                    </asp:Panel>
                </tbody>
            </table>
            
        </div>

    <!-- jQuery -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <!-- MDB -->
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.1/mdb.min.js"></script>
</asp:Content>
