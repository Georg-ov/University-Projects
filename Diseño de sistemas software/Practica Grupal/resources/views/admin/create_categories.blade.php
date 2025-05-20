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

@section('content')
    <div style="padding-top: 20px; padding-left: 5px;">
        <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #e93578; font-size: 26px;"> Create New Category </p>
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
                                    <div class="tab-content pt-3">
                                        <div class="tab-pane active">
                                            <form action="{{ route('save.category') }}" method="POST">
                                                @csrf
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Name</label>
                                                        <input class="form-control" type="text" name="name" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">
                                                        
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
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <label style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Description</label>
                                                        <textarea class="form-control" id="description" name="description" rows="5" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;" required></textarea>
                                                        @error('description')
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
                                                <div class="row">
                                                    <div class="col d-flex justify-content-end">
                                                        <a href="http://127.0.0.1:8000/admin/categories" style="font-family: 'Noto Serif Lao', serif; font-size: 14px; color: #e93578; padding-right: 9px; padding-top: 7px;">Back</a>
                                                        <button class="btn btn-update" type="submit" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Save Category</button>
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
