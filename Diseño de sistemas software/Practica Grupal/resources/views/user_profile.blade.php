@extends('layouts.master')

@push('styles')
<style>
    body {
        background-color: #282C2F;
        font-family: 'Orbitron', sans-serif;
    }

    .card {
        background-color: #1C1E21;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-bottom: 30px;
        color: white;
    }

    .user-profile {
        background: linear-gradient(to right, #D79292, #60A9BD);
        padding: 30px;
        border-radius: 10px 0 0 10px;
    }

    .user-profile img {
        border-radius: 50%;
        max-width: 150px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .user-details {
        padding: 30px;
    }

    .user-details h6 {
        font-family: 'Orbitron', sans-serif;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .user-details p {
        font-family: 'Orbitron', sans-serif;
        font-size: 16px;
        color: #C4C4C4;
        margin-bottom: 20px;
    }

    .gradient-custom-2 {
        background: linear-gradient(to right, #60A9BD, #D79292);
        border-radius: 0 10px 10px 0;
        color: black;
        text-align: center;
        padding: 50px;
    }

    .gradient-custom-2 img {
        max-width: 250px;
        transform: rotate(20deg);
        margin-bottom: 20px;
    }

    .gradient-custom-2 p {
        font-size: 20px;
        color: white;
    }

    .container {
        margin-top: 50px;
    }

    .user-details .row {
        display: flex;
        flex-wrap: wrap;
    }

    .user-details .col-md-6 {
        margin-bottom: 20px;
    }

    .btn-edit {
        background-color: #60A9BD;
        color: white;
        font-family: 'Orbitron', sans-serif;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
    }

    .btn-edit:hover {
        background-color: #4a94a6;
    }

    .btn-premium-profile {
        width: -webkit-fit-content;
        width: -moz-fit-content;
        width: fit-content;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        padding: 0.8em 1.1em;
        gap: 0.4rem;
        border: none;
        font-weight: bold;
        border-radius: 30px;
        cursor: pointer;
        text-shadow: 2px 2px 3px rgb(136 0 136 / 50%);
        background: linear-gradient(
            15deg,
            #D79292, #60A9BD
            )
        no-repeat;
        background-size: 300%;
        background-position: left center;
        -webkit-transition: background 0.3s ease;
        transition: background 0.3s ease;
        color: #fff;
    }

    .btn-premium-profile:hover {
        background-size: 320%;
        background-position: right center;
    }

    .btn-premium-profile:hover svg {
        fill: #fff;
    }

    .btn-premium-profile svg {
        width: 23px;
        fill: #f09f33;
        -webkit-transition: 0.3s ease;
        transition: 0.3s ease;
    }

    .text-center {
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .button-container {
        display: flex;
        gap: 20px; /* Espacio entre botones, ajusta según sea necesario */
        align-items: center;
    }

    .btn-edit, .btn-premium-profile {
        padding: 10px 20px; /* Ajusta el padding según sea necesario */
        font-size: 16px; /* Ajusta el tamaño de la fuente según sea necesario */
        text-decoration: none; /* Para eliminar subrayado en el enlace */
    }

</style>
@endpush

@section('title', 'User Profile')

@section('content')

<div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
            <div class="card">
                <div style="padding-bottom: 10px;">
                    <center><p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #e93578; font-size: 26px;"> {{ $user->name }}'s Profile </p><center>
                </div>
                <div class="row g-0">
                    <div class="col-lg-6 user-profile d-flex align-items-center justify-content-center">
                        <img src="{{ asset( $user->image_profile )}}" alt="User-Profile-Image">
                    </div>
                    <div class="col-lg-6 user-details">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Name</h6>
                                <p>{{ $user->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Username</h6>
                                <p>{{ $user->username }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Email</h6>
                                <p>{{ $user->email }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Birthday</h6>
                                <p>{{ $user->age }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Role</h6>
                                <p>{{ $user->role_type }}</p>
                            </div>
                            <div class="col-md-6">
                                <h6>Subscription</h6>
                                <p>{{ $user->subscription_type }}</p>
                            </div>
                            @if ($user->subscription_type == 'PREMIUM' && $user->address != NULL)
                                <div class="col-md-6">
                                    <h6>Street</h6>
                                    <p>{{ $user->address->street }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>City</h6>
                                    <p>{{ $user->address->city }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Province</h6>
                                    <p>{{ $user->address->province }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Postal Code</h6>
                                    <p>{{ $user->address->postal_code }}</p>
                                </div>
                                <div class="col-md-6">
                                    <h6>Country</h6>
                                    <p>{{ $user->address->country }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="text-center">
                            <div class="button-container">
                                <form action="{{ route('edit.profile', ['id' => $user->id]) }}" method="GET">
                                    <button type="submit" class="btn-edit">Edit</button>
                                </form>
                                @if ($user->subscription_type == 'FREEMIUM')
                                    <a href="/subscribe" class="btn-premium-profile">Go PREMIUM</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @if ($user->role_type == 'TEACHER')
                <div class="row user-details">
                    <h6>About Me</h6>
                    <p>{{ $user->about_me }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
