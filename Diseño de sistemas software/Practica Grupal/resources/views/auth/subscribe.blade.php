@extends('layouts.master')

@push('styles')
<style>
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
        height: 10%;
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

    .form-control::placeholder {
        color: #6c757d;
    }
</style>
@endpush

@section('content')
<div class="container py-5 h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-12">
            <div class="card rounded-3 text-black" style="background-color: #282C2F;">
                <div class="row g-0">
                    <div class="col-lg-6">
                        <div class="card-body p-md-3 mx-md-4">
                            @if(session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('subscribe.process') }}">
                                @csrf

                                <h3 class="mb-4" style="color: white; font-family: 'Orbitron', sans-serif;">Payment Information</h3>

                                <div class="form-floating mb-3 custom-input">
                                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="card_number" class="form-control @error('card_number') is-invalid @enderror" name="card_number" value="{{ old('card_number') }}" autocomplete="card_number" oninput="formatCardNumber(this)" />
                                    <label for="card_number" style="color: white; font-family: 'Orbitron', sans-serif;">Card Number</label>
                                    @error('card_number')
                                        <div class="alert alert-danger alert-white rounded">
                                            <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                            <div class="icon">
                                                <i class="fa fa-times-circle"></i>
                                            </div>
                                            <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Error!</strong>
                                            <a style="font-size: 14px;">{{ $message }}</a>
                                        </div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 custom-input">
                                            <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="expiration_month" class="form-control @error('expiration_month') is-invalid @enderror" name="expiration_month" value="{{ old('expiration_month') }}" autocomplete="expiration_month" placeholder="MM" />
                                            <label for="expiration_month" style="color: white; font-family: 'Orbitron', sans-serif;">Expiration Month (MM)</label>
                                            @error('expiration_month')
                                                <div class="alert alert-danger alert-white rounded">
                                                    <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                    <div class="icon">
                                                        <i class="fa fa-times-circle"></i>
                                                    </div>
                                                    <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Error!</strong>
                                                    <a style="font-size: 14px;">{{ $message }}</a>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-floating mb-3 custom-input">
                                            <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="expiration_year" class="form-control @error('expiration_year') is-invalid @enderror" name="expiration_year" value="{{ old('expiration_year') }}" autocomplete="expiration_year" placeholder="YY" />
                                            <label for="expiration_year" style="color: white; font-family: 'Orbitron', sans-serif;">Expiration Year (YYYY)</label>
                                            @error('expiration_year')
                                                <div class="alert alert-danger alert-white rounded">
                                                    <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                                    <div class="icon">
                                                        <i class="fa fa-times-circle"></i>
                                                    </div>
                                                    <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Error!</strong>
                                                    <a style="font-size: 14px;">{{ $message }}</a>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="cvv" class="form-control @error('cvv') is-invalid @enderror" name="cvv" value="{{ old('cvv') }}" autocomplete="cvv" />
                                    <label for="cvv" style="color: white; font-family: 'Orbitron', sans-serif;">CVC</label>
                                    @error('cvv')
                                        <div class="alert alert-danger alert-white rounded">
                                            <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                            <div class="icon">
                                                <i class="fa fa-times-circle"></i>
                                            </div>
                                            <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Error!</strong>
                                            <a style="font-size: 14px;">{{ $message }}</a>
                                        </div>
                                    @enderror
                                </div>

                                <h3 class="mb-4" style="color: white; font-family: 'Orbitron', sans-serif;">Billing Address</h3>

                                <div class="form-floating mb-3 custom-input">
                                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="cardholder_name" class="form-control @error('cardholder_name') is-invalid @enderror" name="cardholder_name" value="{{ old('cardholder_name') }}" autocomplete="cardholder_name" />
                                    <label for="cardholder_name" style="color: white; font-family: 'Orbitron', sans-serif;">Cardholder Name</label>
                                    @error('cardholder_name')
                                        <div class="alert alert-danger alert-white rounded">
                                            <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                            <div class="icon">
                                                <i class="fa fa-times-circle"></i>
                                            </div>
                                            <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Error!</strong>
                                            <a style="font-size: 14px;">{{ $message }}</a>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="street" class="form-control @error('street') is-invalid @enderror" name="street" value="{{ old('street') }}" autocomplete="street" />
                                    <label for="street" style="color: white; font-family: 'Orbitron', sans-serif;">Street</label>
                                    @error('street')
                                        <div class="alert alert-danger alert-white rounded">
                                            <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                            <div class="icon">
                                                <i class="fa fa-times-circle"></i>
                                            </div>
                                            <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Error!</strong>
                                            <a style="font-size: 14px;">{{ $message }}</a>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="city" class="form-control @error('city') is-invalid @enderror" name="city" value="{{ old('city') }}" autocomplete="city" />
                                    <label for="city" style="color: white; font-family: 'Orbitron', sans-serif;">City</label>
                                    @error('city')
                                        <div class="alert alert-danger alert-white rounded">
                                            <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                            <div class="icon">
                                                <i class="fa fa-times-circle"></i>
                                            </div>
                                            <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Error!</strong>
                                            <a style="font-size: 14px;">{{ $message }}</a>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="province" class="form-control @error('province') is-invalid @enderror" name="province" value="{{ old('province') }}" autocomplete="province" />
                                    <label for="province" style="color: white; font-family: 'Orbitron', sans-serif;">Province</label>
                                    @error('province')
                                        <div class="alert alert-danger alert-white rounded">
                                            <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                            <div class="icon">
                                                <i class="fa fa-times-circle"></i>
                                            </div>
                                            <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Error!</strong>
                                            <a style="font-size: 14px;">{{ $message }}</a>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="postal_code" class="form-control @error('postal_code') is-invalid @enderror" name="postal_code" value="{{ old('postal_code') }}" autocomplete="postal_code" />
                                    <label for="postal_code" style="color: white; font-family: 'Orbitron', sans-serif;">Postal Code</label>
                                    @error('postal_code')
                                        <div class="alert alert-danger alert-white rounded">
                                            <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                            <div class="icon">
                                                <i class="fa fa-times-circle"></i>
                                            </div>
                                            <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Error!</strong>
                                            <a style="font-size: 14px;">{{ $message }}</a>
                                        </div>
                                    @enderror
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <input style="color: white; font-family: 'Orbitron', sans-serif; font-size: 12px;" type="text" id="country" class="form-control @error('country') is-invalid @enderror" name="country" value="{{ old('country') }}" autocomplete="country" />
                                    <label for="country" style="color: white; font-family: 'Orbitron', sans-serif;">Country</label>
                                    @error('country')
                                        <div class="alert alert-danger alert-white rounded">
                                            <button type="button" data-bs-dismiss="alert" aria-hidden="true" class="btn-close"></button>
                                            <div class="icon">
                                                <i class="fa fa-times-circle"></i>
                                            </div>
                                            <strong style="font-size: 14px; margin-left: 30px; margin-right: 2px;">Error!</strong>
                                            <a style="font-size: 14px;">{{ $message }}</a>
                                        </div>
                                    @enderror
                                </div>

                                <div class="text-center pt-1 mb-5 pb-1">
                                    <button type="submit" class="btn btn-primary btn-block fa-lg gradient-custom-2 mb-3" style="width: 100%; border-color: #6AA7B9; font-family: 'Orbitron', sans-serif;">{{ __('Subscribe') }}</button>
                                </div>

                            </form>
                        </div>
                    </div>
                    <div class="col-lg-6 d-flex align-items-center gradient-custom-2">
                        <div class="text-center px-3 py-4 p-md-5 mx-md-4">
                            <img src="{{ asset('app_images/foto.png') }}" alt="Imagen" class="mb-2" style="max-width: 250px; transform: rotate(20deg);">
                            <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: black; font-size: 20px;"><strong> Enjoy premium features with <span class="font-weight-bold h2 mx-auto" style="color: white; font-family: 'Orbitron', sans-serif; font-size: 25px;"><strong><em>MOKTYS</em></strong></span></strong></p>
                            <p class="h4" style="font-family: 'Orbitron', sans-serif; color: white;">Subscribe to MOKTYS</p>
                            <p class="h4" style="font-family: 'Orbitron', sans-serif; color: white;">Unlock all courses NOW</p>
                            <p class="h4" style="font-family: 'Orbitron', sans-serif; color: white;">Enjoy for just 14.99€!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function formatCardNumber(input) {
    // Remove all non-digit characters
    let value = input.value.replace(/\D/g, '');

    // Group digits into sets of 4
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;

    // Set the formatted value back to the input
    input.value = formattedValue;
}
</script>
@endsection
