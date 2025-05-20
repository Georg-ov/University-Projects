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
        <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #e93578; font-size: 26px;"> Categories Administration </p>
    </div>

    <div class="d-flex justify-content-between">
        <div class="d-inline-block">
            <form class="form-inline my-2 my-lg-0 custom-search-form" action="{{ route('search.categories') }}" method="GET">
                <input class="form-control form-control-sm mr-sm-2 custom-search-input" name="search" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button" type="submit">Search</button>
                <a href="http://127.0.0.1:8000/admin/categories/create" class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button">Create</a>
            </form>
        </div>
        
        <div class="ml-auto d-flex align-items-center">
            <form action="{{ route('order.categories') }}" method="GET" class="form-inline my-2 my-lg-0">
                <div class="d-flex form-group mr-sm-2">
                    <select class="form-control form-control-sm mr-sm-2" id="sort" name="sort">
                        <option value="id" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Order by ID</option>
                        <option value="name" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Order by Nombre</option>
                    </select>
                    <button type="submit" class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button">Order</button>
                </div>
            </form>
        </div>
    </div>

    <div style="min-height: 100vh; overflow-x: scroll;">
        <table class="table table-hover border-bottom border-dark" style="padding-left: 20px;">
            <thead class="thead-dark">
                <tr>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 75px;">
                        Id
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 250px;">
                        Name
                    </th>
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 850px;">
                        Description
                    </th>   
                    <th style="background-color: #ffb4b0; font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 550px;">
                        ImageFileName
                    </th>
                    <th style="background-color: #006e8c; color: white; font-family: 'Noto Serif Lao', serif; font-size: 14px; text-align: center; border: 1px solid #c0c0c0;">
                        Options
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0;">{{ $category->id }}</td>
                        
                        <th style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 250px;">
                            <?php echo chunk_split($category->name, 32, "<br>"); ?>
                        </th>

                        <th style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 850px;">
                            <?php echo chunk_split($category->description, 110, "<br>"); ?>
                        </th>
                        
                        <th style="font-family: 'Noto Serif Lao', serif; font-size: 14px; border: 1px solid #c0c0c0; width: 550px;">
                            <?php echo chunk_split($category->image_file_name, 80, "<br>"); ?>
                        </th>
                        
                        <!-- Botones de editar usuario y borrar pasando el id del usuario -->
                        <td style="border: 1px solid #c0c0c0;">
                            <form action="{{ route('edit.category', ['id' => $category->id]) }}" method="GET" style="padding-bottom: 3px;">
                                <button type="submit" class="btn btn-sm btn-custom-success" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">Update</button>
                            </form>

                            <form action="{{ route('delete.category') }}" method="POST">
                                @csrf
                                <input type="hidden" name="name" value="{{ $category->name }}">
                                <button type="submit" class="btn btn-sm btn-custom-danger" style="padding-right: 12px; font-family: 'Noto Serif Lao', serif; font-size: 14px;" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;"> No Categories were found, try next one.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="d-flex justify-content-center mt-4">
            {{ $categories->appends(['sort' => request('sort')])->links() }}
        </div>

    </div>
@endsection

@section('scripts')

@endsection
