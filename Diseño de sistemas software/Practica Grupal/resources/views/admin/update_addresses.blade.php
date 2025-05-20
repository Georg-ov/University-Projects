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
    <div style="padding-top: 20px; padding-left: 5px;">
        <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #e93578; font-size: 26px;">Update Address: {{ $address->street }}</p>
    </div>

    <div class="container">
        <div class="row flex-lg-nowrap">
            <div class="col-12 col-lg-auto mb-3" style="width: 200px;"></div>

            <div class="col">
                <div class="row">
                    <div class="col mb-3" style="padding-top: 20px;">
                        <div class="card">
                            <div class="card-body">
                                <div class="e-profile">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a href="" class="active nav-link" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Settings</a></li>
                                    </ul>
                                    <div class="tab-content pt-3">
                                        <div class="tab-pane active">
                                            <form action="{{ route('update.address', ['id' => $address->id]) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Street</label>
                                                        <input class="form-control @error('street') is-invalid @enderror" type="text" name="street" value="{{ old('street', $address->street) }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
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
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">City</label>
                                                        <input class="form-control @error('city') is-invalid @enderror" type="text" name="city" value="{{ old('city', $address->city) }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
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
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Province</label>
                                                        <input class="form-control @error('province') is-invalid @enderror" type="text" name="province" value="{{ old('province', $address->province) }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
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
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Postal Code</label>
                                                        <input class="form-control @error('postal_code') is-invalid @enderror" type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code) }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
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
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Country</label>
                                                        <input class="form-control @error('country') is-invalid @enderror" type="text" name="country" value="{{ old('country', $address->country) }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
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
                                                </div>
                                                <div class="row">
                                                    <div class="col d-flex justify-content-end">
                                                        <a href="{{ route('list.addresses') }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px; color: #e93578; padding-right: 9px; padding-top: 7px;">Back: Addresses</a>
                                                        <a href="http://127.0.0.1:8000/admin/users" style="font-family: 'Noto Serif Lao', serif; font-size: 14px; color: #e93578; padding-right: 9px; padding-top: 7px;">Back: Users</a>
                                                        <button class="btn btn-update" type="submit" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Save Changes</button>
                                                    </div>
                                                </div>
                                            </form>

                                            <form action="{{ route('delete.address') }}" method="POST" style="margin-top: 10px;" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $address->id }}">
                                                <div class="row">
                                                    <div class="col d-flex justify-content-end">
                                                        <button class="btn btn-danger" type="submit" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Delete Address</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-3 mb-3"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection

