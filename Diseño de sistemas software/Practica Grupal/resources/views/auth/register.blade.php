@extends('layouts.master')

@section('content')
<div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
    <div class="col-xl-10">
        <div class="card rounded-3 text-black" style="background-color: #282C2F;">
        <div class="row g-0">
            <div class="col-lg-6">
            <div class="card-body p-md-3 mx-md-4">

                <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-floating mb-3 custom-input">
                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="name" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus />
                    <label for="name" style="color: white; font-family: 'Orbitron', sans-serif;">Name</label>
                    @error('name')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-3 custom-input">
                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="username" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" required autocomplete="username" />
                    <label for="username" style="color: white; font-family: 'Orbitron', sans-serif;">Username</label>
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-3 custom-input">
                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="date" id="birthday" class="form-control @error('birthday') is-invalid @enderror" name="birthday" value="{{ old('birthday') }}" max="{{ now()->subYears(18)->format('Y-m-d') }}" required>
                    <label for="birthday" style="color: white; font-family: 'Orbitron', sans-serif;">Birthday</label>
                    @error('birthday')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-3 custom-input">
                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="email" id="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" />
                    <label for="email" style="color: white; font-family: 'Orbitron', sans-serif;">Email</label>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-3 custom-input">
                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 17px;" type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" />
                    <label for="password" style="color: white; font-family: 'Orbitron', sans-serif;">Password</label>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <div class="form-floating mb-3 custom-input">
                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 17px;" type="password" id="password-confirm" class="form-control" name="password_confirmation" required autocomplete="new-password" />
                    <label for="password-confirm" style="color: white; font-family: 'Orbitron', sans-serif;">Confirm Password</label>
                </div>

                <div class="text-center pt-1 mb-5 pb-1">
                    <button type="submit" class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" style="width: 100%; border-color: #6AA7B9; font-family: 'Orbitron', sans-serif;">{{ __('Register') }}</button>
                    <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: white; font-size: 14px;"> Already have an account? <a href="http://127.0.0.1:8000/login" class="font-weight-bold h2 mx-auto" style="color: #91E4FB; font-family: 'Orbitron', sans-serif; font-size: 14px;">Login here</a></p>
                </div>

                </form>

            </div>
            </div>
            <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
            <div class="text-center px-3 py-4 p-md-5 mx-md-4">
                <img src={{ asset("app_images/foto.png") }} alt="Imagen" class="mb-2" style="max-width: 250px; transform: rotate(20deg);">
                <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: black; font-size: 20px;"><strong> Discover Limitless Learning with <span class="font-weight-bold h2 mx-auto" style="color: white; font-family: 'Orbitron', sans-serif; font-size: 25px;"><strong><em>MOKTYS</em></strong></span></strong></p>
            </div>
            </div>
        </div>
        </div>
    </div>
    </div>
</div>
@endsection
