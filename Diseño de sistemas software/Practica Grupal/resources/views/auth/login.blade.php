@extends('layouts.master')

@section('content')
<div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
            <div class="card rounded-3 text-black" style="background-color: #282C2F;">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="card-body p-md-3 mx-md-4">

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="mb-3 text-center">
                                    <img src={{ asset("app_images/foto.png") }} alt="Imagen" class="mb-2" style="max-width: 170px; transform: rotate(20deg);">
                                    <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #91E4FB; font-size: 20px; padding-bottom: 20px;"><strong> Learn more with <span class="font-weight-bold h2 mx-auto" style="color: white; font-family: 'Orbitron', sans-serif; font-size: 20px;"><strong>MOKTYS</strong></span></strong></p>
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus />
                                    <label for="email" style="color: white; font-family: 'Orbitron', sans-serif;">Email Address</label>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 17px;" type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" />
                                    <label for="password" style="color: white; font-family: 'Orbitron', sans-serif;">Password</label>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember" style="font-family: 'Orbitron', sans-serif; color: white; font-size: 14px;">Remember Me</label>
                                </div>

                                <div class="text-center pt-1 mb-5 pb-1">
                                    <button type="submit" class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" style="width: 100%; border-color: #6AA7B9; font-family: 'Orbitron', sans-serif;">{{ __('Login') }}</button>
                                    <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: white; font-size: 14px;"> Don't have an account? <a href="http://127.0.0.1:8000/register" class="font-weight-bold h2 mx-auto" style="color: #91E4FB; font-family: 'Orbitron', sans-serif; font-size: 14px;">Register here</a></p>
                                </div>

                            </form>

                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                        <div class="text-white px-3 py-4 p-md-5 mx-md-4">
                            <p class="h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: black; font-size: 20px;">Unlock Infinite Knowledge with MOTKYS: Where Learning Knows No Limits!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection