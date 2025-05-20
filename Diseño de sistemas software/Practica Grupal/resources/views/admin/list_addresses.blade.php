@extends('layouts.master')

@section('content')
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <!-- Cabecera de página -->
    <div style="padding-top: 20px; padding-bottom: 10px; padding-left: 5px;">
        <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #e93578; font-size: 26px;"> Address Administration </p>
    </div>

    <div class="d-flex justify-content-between">
        <div class="d-inline-block">
            <form class="form-inline my-2 my-lg-0 custom-search-form" action="{{ route('search.addresses') }}" method="GET">
                <input class="form-control form-control-sm mr-sm-2 custom-search-input" name="search" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button" type="submit">Search</button>
            </form>
        </div>
        
        <div class="ml-auto d-flex align-items-center">
            <form action="{{ route('order.addresses') }}" method="GET" class="form-inline my-2 my-lg-0">
                <div class="d-flex form-group mr-sm-2">
                    <select class="form-control form-control-sm mr-sm-2" id="sort" name="sort">
                        <option value="street">Order by Street</option>
                        <option value="city">Order by City</option>
                        <option value="province">Order by Province</option>
                        <option value="postal_code">Order by Postal Code</option>
                        <option value="country">Order by Country</option>
                    </select>
                    <button type="submit" class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button">Order</button>
                </div>
            </form>
        </div>
    </div>

    <div style="min-height: 100vh; overflow-x: scroll;">
        <table class="table table-hover border-bottom border-dark">
            <thead class="thead-dark">
                <tr>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">
                        Id
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">
                        Street
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">
                        City
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">
                        Province
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">
                        Postal Code
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">
                        Country
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">
                        User
                    </th>
                    
                    <th style="background-color: #006e8c; color: white; font-family: 'Noto Serif Lao', serif; font-size: 14px; text-align: center; border: 1px solid #c0c0c0;">
                        Options
                    </th>
                    
                </tr>
            </thead>
            <tbody>
                @forelse ($addresses as $address)
                    <tr>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $address->id }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $address->street }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $address->city }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $address->province }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $address->postal_code }}</td>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $address->country }}</td>
                        <td style="border: 1px solid #c0c0c0;">
                            <a href="{{ route('edit.user', ['id' => $address->user_id]) }}" style="font-family: 'Noto Serif Lao', serif; font-size: 14px; color: #e93578; padding-right: 9px; padding-top: 7px;">Users</a>
                        </td>
                        <td style="border: 1px solid #c0c0c0;">
                            <form action="{{ route('edit.addresses', $address->id) }}" method="GET" style="padding-bottom: 3px;">
                                <button type="submit" class="btn btn-sm btn-custom-success" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Update</button>
                            </form>
                            <form action="{{ route('delete.address') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this address?');">
                                @csrf
                                <input type="hidden" name="id" value="{{  $address->id }}">
                                <button type="submit" class="btn btn-sm btn-custom-danger" style="padding-right: 12px; font-family: 'Noto Serif Lao', serif; font-size: 14px;">Delete</button>
                            </form>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No addresses found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $addresses->links() }}
        </div>

    </div>
@endsection

@section('scripts')
@endsection
