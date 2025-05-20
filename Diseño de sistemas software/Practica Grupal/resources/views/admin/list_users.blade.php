@extends('layouts.master') 

@push('styles')

<style>
    table.border-bottom {
        border-bottom: none;
    }
</style>

@endpush

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Cabecera de página -->
    <div style="padding-top: 20px; padding-bottom: 10px; padding-left: 5px;">
        <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #e93578; font-size: 26px;"> Users Administration </p>
    </div>

    <div class="d-flex justify-content-between">
    <div class="d-inline-block">
        <form class="form-inline my-2 my-lg-0 custom-search-form" action="{{ route('search.users') }}" method="GET">
            <input class="form-control form-control-sm mr-sm-2 custom-search-input" name="search" type="search" placeholder="Search" aria-label="Search">
            <button class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button" type="submit">Search</button>
            <a href="http://127.0.0.1:8000/admin/users/create" class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button">Create</a>
        </form>
    </div>
    
    <div class="ml-auto d-flex align-items-center">
        <form action="{{ route('order.users') }}" method="GET" class="form-inline my-2 my-lg-0">
            <div class="d-flex form-group mr-sm-2">
                <select class="form-control form-control-sm mr-sm-2" id="sort" name="sort">
                    <option value="id" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Order by ID</option>
                    <option value="name" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Order by Nombre</option>
                    <option value="username" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Order by Username</option>
                    <option value="age" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Order by Birthday</option>
                </select>
                <button type="submit" class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button">Order</button>
            </div>
        </form>
    </div>
</div>

    <div style="min-height: 100vh;">
        <table class="table table-hover border-bottom border-dark" style="padding-left: 20px; overflow-x: scroll; display:block;">
            <thead class="thead-dark">
                <tr>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 2.5%;">
                        Id
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 15%;">
                        Name
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 15%;">
                        Username
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 20%;">
                        Email
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 10%;">
                        Birthday
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 15%;">
                        Role
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 15%;">
                        Subscription
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 15%;">
                        Address
                    </th>
                    <th style="background-color: #006e8c; color: white; font-family: 'Noto Serif Lao', serif; font-size: 14px; text-align: center; border: 1px solid #c0c0c0;">
                        Options
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $user->id }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $user->name }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $user->username }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $user->email }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $user->age }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $user->role_type }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $user->subscription_type }}</td>
                        <td style="border: 1px solid #c0c0c0;">
                            @if($user->address()->exists())
                                <a href="{{ route('edit.addresses', ['id' => $user->address->id]) }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px; color: #e93578; padding-right: 9px; padding-top: 7px;">Address</a>
                            @else
                                <a style="font-family: 'Noto Serif Lao', serif; font-size: 14px; color: #e93578; padding-right: 9px; padding-top: 7px;">No Address available.</a>
                            @endif
                        </td>
                        <!-- Botones de editar usuario y borrar pasando el id del usuario -->
                        <td style="border: 1px solid #c0c0c0;">
                            <form action="{{ route('edit.user', ['id' => $user->id]) }}" method="GET" style="padding-bottom: 3px;">
                                <button type="submit" class="btn btn-sm btn-custom-success" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Update</button>
                            </form>
                            <form action="{{ route('delete.user') }}" method="POST">
                                @csrf
                                <input type="hidden" name="username" value="{{ $user->username }}">
                                <button type="submit" class="btn btn-sm btn-custom-danger" style="padding-right: 12px; font-family: 'Noto Serif Lao', serif; font-size: 14px;" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;"> No Users were found, try next time.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $users->appends(['sort' => request('sort')])->links() }}
        </div>

    </div>
@endsection

@section('scripts')

@endsection