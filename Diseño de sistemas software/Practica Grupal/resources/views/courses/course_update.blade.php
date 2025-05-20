@extends('layouts.master')
@section('title', 'Edit Course')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/courses/create_or_update.css') }}">
</head>
<div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
            <div class="card rounded-3 text-black" style="background-color: #282C2F;">
                <form method="POST" action="{{ route('course.update.save', ['course' => $course, 'id' => $course->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-0">
                        <div class="col-lg-8">
                            <div class="card-body p-md-3 mx-md-4">
                                <h3 class="text-title">Edit Course</h3>
                                <div class="form-floating mb-3 custom-input">
                                    <input value="{{ $course->name }}" type="text" class="form-control text-label" id="name" name="name" required>
                                    <label for="name" class="text-label">Course Name</label>
                                </div>
                                <div class="form-floating mb-3 custom-input">
                                    <textarea class="form-control text-label" id="description" name="description" rows="5" required>{{ $course->description }}</textarea>
                                    <label for="description" class="text-label">Description</label>
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <select class="form-select text-label" name="visibility" id="visibility" required>
                                        <option value="1" {{ $course->visibility ? 'selected' : '' }} class="select-option">Public</option>
                                        <option value="0" {{ $course->visibility ? '' : 'selected' }} class="select-option">Private</option>
                                    </select>
                                    <label for="visibility" class="form-label text-label">Visibility</label>
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <select class="form-select text-label" name="is_free" id="is_free" required>
                                        <option value="1" {{ $course->is_free ? 'selected' : '' }} class="select-option">Free</option>
                                        <option value="0" {{ $course->is_free ? '' : 'selected' }} class="select-option">Premium</option>
                                    </select>
                                    <label for="is_free" class="form-label text-label">Availability</label>
                                </div>

                                <div class="form-floating mb-3 custom-input">
                                    <select class="form-select text-label" id="category_id" name="category_id" required>
                                        @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" class="select-option" {{ $category->id === $course->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="category" class="form-label text-label">Category</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 custom-column">
                            <img class="img-upload" src="{{ asset('app_images/course/upload.png') }}">
                            <div class="mb-3 custom-input">
                                <label for="image_file_name" class="form-label text-label">Choose the Cover Image</label>
                                <input type="file" class="form-control text-label" id="image_file_name" name="image_file_name" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="row g-1 custom-row">
                        <button type="submit" class="btn btn-darkbtn-block fa-lg gradient-custom-2 mb-3" style="width: 100%; border-color: #6AA7B9; font-family: 'Orbitron', sans-serif; width: 90%;">Update Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection