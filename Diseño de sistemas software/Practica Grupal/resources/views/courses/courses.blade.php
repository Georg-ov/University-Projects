@extends('layouts.master')

@section('title', 'Contact us')

@section('content')

<head>
    <title>Courses</title>
    <link rel="stylesheet" href="{{ asset('css/courses/courses.css') }}">
</head>

<div class="container">

    <form action="{{ route('availableCourses.filterAndOrder') }}" method="GET">
        <div class="gradient-custom-2">
            <div class="row align-items-center filter-row">
                <div class="col-lg-6 col-md-12 col-sm-12 col-12">
                    <input type="search" name="name" id="name" onchange="this.form.submit()" placeholder="What do you want to learn today?" value="{{ request('name') }}" class="form-control form-control-lg">
                </div>
                <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                    <select id="category" name="category" onchange="this.form.submit()" class="form-select form-select-lg filter-form-select mb-3">
                        <option value="">All categories</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" @if (request('category')==$category->id) selected @endif>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                    <select id="is_free" name="is_free" onchange="this.form.submit()" class="form-select form-select-lg filter-form-select mb-3">
                        <option value="" @if (request('is_free')===null) selected @endif>Availability</option>
                        <option value="1" @if (request('is_free')==1) selected @endif>Free Courses</option>
                        <option value="0" @if (request('is_free')==='0' ) selected @endif>Premium Courses</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row justify-content-end order-by-row">
            <div class="col-12 col-sm-6 col-md-6 col-lg-3">
                <select id="sort" name="sort" onchange="this.form.submit()" class="form-select form-select-lg sort-by-form-select mb-3">
                    <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>Order by Id</option>
                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Order by Name (A-Z)</option>
                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Order by Name (Z-A)</option>
                    <option value="average_rating" {{ request('sort') == 'average_rating' ? 'selected' : '' }}>Order by Rating (best)</option>
                </select>
            </div>
        </div>

    </form>




    <div class="row">

        @forelse ($courses as $course)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
            <div class="card">
                <img src="{{ asset($course->image_file_name) }}" class="card-img-top" alt="course image">
                <div class="card-body">
                    <p class="card-text"> {{ $course->name }} </p>
                </div>
                <a href="{{ route('course.show', ['id' => $course->id]) }}" class="btn btn-primary">VIEW COURSE</a>
            </div>
        </div>

        @empty
        <p>There are no courses available.</p>
        @endforelse

    </div>
</div>
@endsection