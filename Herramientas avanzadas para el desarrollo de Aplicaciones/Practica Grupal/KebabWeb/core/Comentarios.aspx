<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="Comentarios.aspx.cs" Inherits="KebabWeb.core.Comentarios" %>

<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Comentarios</title>
    <link rel="stylesheet" href="/css/Comentarios.css" />
    <!-- Font Awesome -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
  rel="stylesheet"
/>
<!-- Google Fonts -->
<link
  href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
  rel="stylesheet"
/>
<!-- MDB -->
<link
  href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.1/mdb.min.css"
  rel="stylesheet"
/>

	<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

</asp:Content>

<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">


 <div class="container">
        <asp:Repeater ID="rptMensajes" runat="server">
            <ItemTemplate>
                <div class="comment-block-custom">
    <h1 class="comments-title-custom">Comments</h1>
    <div class="be-comment">
        <div class="img-comment-custom">
            <a href="blog-detail-2.html">
                <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt="" class="ava-comment-custom">
            </a>
        </div>
        <div class="comment-content-custom">
            <span class="comment-name-custom">
                <a href="blog-detail-2.html"><%# Eval("UsuarioEmail") %></a>
            </span>
            <span class="comment-time-custom">
                <i class="fa fa-clock-o"></i>
                <%# Eval("Fecha") %> 
            </span>
            <p class="comment-text-custom">
                <%# Eval("Mensaje") %> 
            </p>
            <span class="comment-local-custom">
                Local: <%# Eval("NombreLocal") %>
            </span>
        </div>
        <div>
             <asp:Button ID="btnEliminarComentario" runat="server" Text="Eliminar comentario" OnClick="btnEliminarComentario_Click" Visible='<%# MostrarBotonEliminar(Eval("UsuarioEmail")) %>' CommandArgument='<%# Eval("ComentarioId") %>' CssClass="btn btn-primary"/>
         </div>
    </div>
</div>
            </ItemTemplate>
        </asp:Repeater>
    </div>
    <asp:ScriptManager ID="ScriptManager2" runat="server">
    </asp:ScriptManager>
  
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.3.1/mdb.min.js"></script>




<asp:Panel ID="addCommentPanel" runat="server">
    <div class="comment-form-container">
        <div class="form-title">Añade tu comentario</div>
        <asp:TextBox ID="txtMensaje" runat="server" TextMode="MultiLine" Rows="3" Columns="40" CssClass="form-control" />        
        <asp:HiddenField ID="ValoracionHidden" runat="server" />
        <div class="rating">
            <span class="star rating">☆</span>
            <span class="star rating">☆</span>
            <span class="star rating">☆</span>
            <span class="star rating">☆</span>
            <span class="star rating">☆</span>            
        </div>
        <asp:DropDownList ID="ddlLocales" runat="server" DataTextField="Nombre" DataValueField="Id" CssClass="form-control" />
        <asp:Button ID="AddCommentButton" runat="server" Text="Añadir comentario" OnClick="AgregarComentario_Click" CssClass="btn btn-primary" />        
        <asp:Label ID="LabelError" runat="server" ForeColor="Red" Visible =false />
    </div>
</asp:Panel>
    
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            const stars = document.querySelectorAll('.star');
            const ratingValue = document.getElementById('<%=ValoracionHidden.ClientID%>');

            stars.forEach(star => {
                star.addEventListener('click', () => {
                    const index = [...stars].indexOf(star);

                    // Guardar la calificación en el campo oculto
                    ratingValue.value = stars.length - index;

                    // Cambiar la apariencia de las estrellas
                    stars.forEach((currentStar, currentIndex) => {
                        currentStar.textContent = currentIndex >= index ? '★' : '☆';
                    });
                });

                star.addEventListener('mouseover', () => {
                    const index = [...stars].indexOf(star);

                    // Cambiar la apariencia de las estrellas al pasar el cursor sobre ellas
                    stars.forEach((currentStar, currentIndex) => {
                        currentStar.textContent = currentIndex >= index ? '★' : '☆';
                    });
                });

                star.addEventListener('mouseout', () => {
                    // Restaurar la apariencia de las estrellas al salir del hover
                    stars.forEach((currentStar, currentIndex) => {
                        currentStar.textContent = ratingValue.value >= stars.length - currentIndex ? '★' : '☆';
                    });
                });
            });
        });

    </script>
    
    

</asp:Content>
