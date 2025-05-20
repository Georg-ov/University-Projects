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

    .alert {
        display: flex;
        align-items: center;
        width: 100%;
        border-radius: 0.375rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.11);
        position: relative;
        padding: 0.5rem 1rem;
        margin-top: 5px;
    }

    .alert-white {
        background-image: linear-gradient(to bottom, #fff, #f9f9f9);
        border-color: #cacaca;
        color: #404040;
    }

    .alert-white .icon {
        text-align: center;
        width: 30px;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        padding-top: 15px;
        border-right: 1px solid #cacaca;
        background: #f9f9f9;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .alert-white .icon i {
        font-size: 14px;
        color: #fff;
    }

    .alert-white .icon:after {
        content: '';
        display: block;
        width: 10px;
        height: 10px;
        position: absolute;
        top: 50%;
        right: -5px;
        background: #fff;
        transform: rotate(45deg);
        border: 1px solid #cacaca;
        border-left: 0;
        border-bottom: 0;
    }

    .alert-danger {
        background-color: #f2dede;
        border-color: #e0b1b8;
        color: #b94a48;
    }

    .alert-white.alert-danger .icon,
    .alert-white.alert-danger .icon:after {
        border-color: #ca452e;
        background: #da4932;
    }

    .alert .btn-close {
        position: absolute;
        top: 50%;
        right: 0.5rem;
        transform: translateY(-50%);
        color: inherit;
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
                    <center><p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #e93578; font-size: 26px;"> Edit {{ $user->name }}'s Profile </p><center>
                </div>
                <form action="{{ route('update.profile', ['id' => $user->id]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-0">
                        <div class="col-lg-6 user-profile d-flex align-items-center justify-content-center">
                            <img src="{{ asset( $user->image_profile )}}" alt="User-Profile-Image">
                        </div>
                        <div class="col-lg-6 user-details custom-input">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Name</h6>
                                        <input class="form-control" type="text" name="name" placeholder="{{ $user->name }}" value="{{ $user->name }}" style="color: white;">
                                        @error('name')
                                            <div class="alert alert-danger alert-white rounded">
                                                <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                <div class="icon">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                                <a style="font-size: 14px;">{{ $message }}</a>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Username</h6>
                                        <input class="form-control" type="text" name="username" placeholder="{{ $user->username }}" value="{{ $user->username }}" style="color: white;">
                                        @error('username')
                                            <div class="alert alert-danger alert-white rounded">
                                                <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                <div class="icon">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                                <a style="font-size: 14px;">{{ $message }}</a>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Email</h6>
                                        <input class="form-control" type="text" name="email" placeholder="{{ $user->email }}" value="{{ $user->email }}" style="color: white;">
                                        @error('email')
                                            <div class="alert alert-danger alert-white rounded">
                                                <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                <div class="icon">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                                <a style="font-size: 14px;">{{ $message }}</a>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Birthday</h6>
                                        <p>{{ $user->age }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Password</h6>
                                        <input class="form-control" type="password" name="password" style="color: white;">
                                        @error('password')
                                            <div class="alert alert-danger alert-white rounded">
                                                <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                <div class="icon">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                                <a style="font-size: 14px;">{{ $message }}</a>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Repeat Password</h6>
                                        <input class="form-control" type="password" name="password_confirmation" style="color: white;">
                                        @error('password_confirmation')
                                            <div class="alert alert-danger alert-white rounded">
                                                <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                <div class="icon">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                                <a style="font-size: 14px;">{{ $message }}</a>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Role</h6>
                                        <p>{{ $user->role_type }}</p> 
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Subscription</h6>
                                        <p>{{ $user->subscription_type }}</p>
                                    </div>
                                </div>
                                @if ($user->subscription_type == 'PREMIUM' && $user->address != NULL)
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Street</h6>
                                        <input class="form-control" type="text" name="street" placeholder="{{ $user->address->street }}" value="{{ $user->address->street }}" style="color: white;">
                                        @error('street')
                                            <div class="alert alert-danger alert-white rounded">
                                                <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                <div class="icon">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                                <a style="font-size: 14px;">{{ $message }}</a>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>City</h6>
                                        <input class="form-control" type="text" name="city" placeholder="{{ $user->address->city }}" value="{{ $user->address->city }}" style="color: white;">
                                        @error('city')
                                            <div class="alert alert-danger alert-white rounded">
                                                <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                <div class="icon">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                                <a style="font-size: 14px;">{{ $message }}</a>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Province</h6>
                                        <input class="form-control" type="text" name="province" placeholder="{{ $user->address->province }}" value="{{ $user->address->province }}" style="color: white;">
                                        @error('province')
                                            <div class="alert alert-danger alert-white rounded">
                                                <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                <div class="icon">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                                <a style="font-size: 14px;">{{ $message }}</a>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Postal Code</h6>
                                        <input class="form-control" type="text" name="postal_code" placeholder="{{ $user->address->postal_code }}" value="{{ $user->address->postal_code }}" style="color: white;">
                                        @error('postal_code')
                                            <div class="alert alert-danger alert-white rounded">
                                                <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                <div class="icon">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                                <a style="font-size: 14px;">{{ $message }}</a>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <h6>Country</h6>
                                        <input class="form-control" type="text" name="country" placeholder="{{ $user->address->country }}" value="{{ $user->address->country }}" style="color: white;">
                                        @error('country')
                                            <div class="alert alert-danger alert-white rounded">
                                                <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                <div class="icon">
                                                    <i class="fa fa-times-circle"></i>
                                                </div>
                                                <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Wrong!</strong>
                                                <a style="font-size: 14px;">{{ $message }}</a>
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endif
                            </div>
                            <div class="text-center">
                                <label for="image_profile" class="btn-edit">
                                    <i style="font-family: 'Noto Serif Lao', serif; font-size: 14px;"></i> Change Photo
                                </label>
                                <input type="file" id="image_profile" name="image_profile" accept="image/*" style="display: none;" style="color: white;">
                                <button class="btn-edit" type="submit">Save changes</button>
                            </div>
                        </div>
                    </div>
                    @if ($user->role_type == 'TEACHER')
                    <div class="row user-details">
                        <div class="form-group custom-input">
                            <h6>About Me</h6>
                            <input class="form-control" type="text" name="about_me" placeholder="{{ $user->about_me }}" value="{{ $user->about_me }}" style="color: white;"> 
                        </div>
                    </div>
                    @endif 
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
