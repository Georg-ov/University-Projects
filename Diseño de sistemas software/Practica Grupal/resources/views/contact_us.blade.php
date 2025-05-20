@extends('layouts.master')

@section('title', 'Contact us')

@section('content')
    <!-- Sección 1: Imagen centrada -->
    <section class="py-5 text-center">
        <div class="container">
            <div class="row">
                <div class="col">
                    <img src={{ asset("app_images/contactus.png") }} style="width; 400px; height: 160px;">
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Columna 1 -->
                <div class="col-md-4 custom-column">
                    <div class="custom-box">
                        <img src={{ asset("app_images/phone_icon.png") }} style="width: 150px; height: 150px; margin-bottom: 20px;">
                        <hr class="divider">
                        <h5 class="text-center mb-4" style="margin-top: 45px; color:#000000; font-family: 'Orbitron', sans-serif;">966 08 22 76</h5>
                    </div>
                </div>
                <!-- Columna 2 -->
                <div class="col-md-4 custom-column">
                    <div class="custom-box">
                        <img src={{ asset("app_images/mail_icon.png") }} style="width: 175px; height: 150px; margin-bottom: 20px;">
                        <hr class="divider">
                        <h5 class="text-center mb-4" style="margin-top: 35px; color:#000000; font-family: 'Orbitron', sans-serif;">moktysadmin @hotmail.org</h5>
                    </div>
                </div>
                <!-- Columna 3 -->
                <div class="col-md-4 custom-column">
                    <div class="custom-box">
                        <img src="{{ asset('app_images/maps_icon.png') }}" style="width: 115px; height: 150px; margin-bottom: 20px;">
                        <hr class="divider">
                        <h5 class="text-center mb-4" style="margin-top: 25px; color: #000000; font-family: 'Orbitron', sans-serif;">
                            <a href="https://www.google.com/maps/place/Isla+de+Alcatraz/@37.8266738,-122.4235655,17z/data=!4m15!1m8!3m7!1s0x808580e1416158eb:0xbf6a4209687ffca3!2sSan+Francisco,+California+94133,+EE.+UU.!3b1!8m2!3d37.8059887!4d-122.4099154!16s%2Fm%2F01zn01v!3m5!1s0x808580f9b38c1c99:0xd15844a27f9a58a5!8m2!3d37.8269775!4d-122.4229555!16zL20vMGg1OTQ?entry=ttu" target="_blank" style="text-decoration: none; color: #000000;">
                                San Francisco, CA 94133, Estados Unidos
                            </a>
                        </h5>
                    </div>
                </div>
                
            </div>
        </div>
    </section>
    
@endsection

<style>

    .custom-column {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0 10px;
    }

    .custom-box {
        text-align: center;
        padding: 40px;
        border: 4px solid #333; /* Grosor y color del borde */
        position: relative; /* Para posicionar la línea */
        width: 260px;
        height: 320px;
        background: -webkit-linear-gradient(to right, #D79292, #60A9BD);
            background: linear-gradient(to right, #D79292, #60A9BD);
    }

    .divider {
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        width: 100%; /* Ancho de la línea */
        border-top: 2px solid #000000; /* Grosor y color de la línea */
        opacity: 1;
    }

    /* Estilos responsivos */
    @media (max-width: 767px) {
        .custom-column {
            margin-bottom: 20px;
        }
    }
</style>

