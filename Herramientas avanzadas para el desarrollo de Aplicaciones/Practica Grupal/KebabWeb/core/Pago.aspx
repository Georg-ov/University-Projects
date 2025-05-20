<%@ Page Title="" Language="C#" MasterPageFile="~/Kebab.Master" AutoEventWireup="true" CodeBehind="Pago.aspx.cs" Inherits="KebabWeb.core.Pago" %>
<asp:Content ID="Content1" ContentPlaceHolderID="head" runat="server">
</asp:Content>
<asp:Content ID="Content2" ContentPlaceHolderID="ContentPlaceHolder1" runat="server">

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plataforma de pago</title>
    <!-- Enlace de Buchstrap -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <div class="py-5 text-center">
            <img class="d-block mx-auto mb-4" src="/assets/images/logo1_2.png" alt="" width="72" height="72">
            <h2>Plataforma de pago</h2>
            <p class="lead">Hey muy buenas a todos guapisimos</p>
        </div>
        <div class="row">
            <div class="col-md-4 order-md-2 mb-4">
            <h4 class="d-flex justify-content-between align-items-center mb-3">
                <span class="text-muted">Tu carrito</span>
                <span class="badge badge-secondary badge-pill">3</span>
            </h4>
            <ul class="list-group mb-3 sticky-top">
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Nombre del producto</h6>
                        <small class="text-muted">Descripción</small>
                    </div>
                    <span class="text-muted">12€</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Segundo Producto</h6>
                        <small class="text-muted">Descripción</small>
                    </div>
                    <span class="text-muted">6€</span>
                </li>
                <li class="list-group-item d-flex justify-content-between lh-condensed">
                    <div>
                        <h6 class="my-0">Tercer Producto</h6>
                        <small class="text-muted">Descripción</small>
                    </div>
                    <span class="text-muted">3€</span>
                </li>
                <li class="list-group-item d-flex justify-content-between bg-light">
                    <div class="text-success">
                        <h6 class="my-0">Oferta</h6>
                        <small>codigo_oferta</small>
                    </div>
                    <span class="text-success">-4€</span>
                </li>
                <li class="list-group-item d-flex justify-content-between">
                    <span>Total (€)</span>
                    <strong>18€</strong>
                </li>
            </ul>
            <form class="card p-2">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Promo code">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-secondary">Reclamar</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-8 order-md-1">
            <h4 class="mb-3">Dirección de envio</h4>
            <form class="needs-validation" novalidate="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="firstName">Nombre</label>
                        <input type="text" class="form-control" id="firstName" placeholder="" value="" required="">
                        <div class="invalid-feedback"> Es necesario un nombre. </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="lastName">Apellidos</label>
                        <input type="text" class="form-control" id="lastName" placeholder="" value="" required="">
                        <div class="invalid-feedback"> Es necesario un apellido minimo. </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="username">Usuario</label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text">@</span>
                        </div>
                        <input type="text" class="form-control" id="username" placeholder="Username" required="">
                        <div class="invalid-feedback" style="width: 100%;"> Es necesario un usuario. </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="email">Email <span class="text-muted">(Opcional)</span></label>
                    <input type="email" class="form-control" id="email" placeholder="tu@example.com">
                    <div class="invalid-feedback"> Añada un email, para recibir actualizaciones. </div>
                </div>
                <div class="mb-3">
                    <label for="address">Dirección</label>
                    <input type="text" class="form-control" id="address" placeholder="1234 Main St" required="">
                    <div class="invalid-feedback"> Introduzca una dirección. </div>
                </div>
                <hr class="mb-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="save-info">
                    <label class="custom-control-label" for="save-info">Guardar la información</label>
                </div>
                <hr class="mb-4">
                <h4 class="mb-3">Pagos</h4>
                <div class="d-block my-3">
                    <div class="custom-control custom-radio">
                        <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" checked="" required="">
                        <label class="custom-control-label" for="credit">Tarjeta de credito</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" required="">
                        <label class="custom-control-label" for="debit">Tarjeta de Debito</label>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="cc-name">Titular de la tarjeta</label>
                        <input type="text" class="form-control" id="cc-name" placeholder="" required="">
                        <small class="text-muted">Nombre completo</small>
                        <div class="invalid-feedback"> Titular de la tarjeta necesario. </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="cc-number">Número de la tarjeta</label>
                        <input type="text" class="form-control" id="cc-number" placeholder="" required="">
                        <div class="invalid-feedback"> Número de la tarjeta necesario </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="cc-expiration">Caducidad</label>
                        <input type="text" class="form-control" id="cc-expiration" placeholder="" required="">
                        <div class="invalid-feedback"> Tiempo de caducidad requerido. </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="cc-cvv">CVV</label>
                        <input type="text" class="form-control" id="cc-cvv" placeholder="" required="">
                        <div class="invalid-feedback"> Codigo de seguridad necesario </div>
                    </div>
                </div>
                <hr class="mb-4">
                <button class="btn btn-primary btn-lg btn-block" type="submit">Continuar con el pago</button>
            </form>
        </div>
        </div>
    </div>

    <!-- links a Bootstrap y jQuery -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>

</html>
</asp:Content>
