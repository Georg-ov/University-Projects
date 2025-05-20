@extends('layouts.master')
@section('title', 'Edit Lesson')

@section('content')
<head>
    <link rel="stylesheet" href="{{ asset('css/courses/create_or_update.css') }}">
</head>
<div class="container h-100">
    <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-xl-10">
            <div class="card rounded-3 text-black" style="background-color: #282C2F;">
                <form method="POST" action="{{ route('lesson.update', ['lesson' => $lesson, 'id' => $lesson->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row g-0">
                        <div class="col-lg-8">
                            <div class="card-body p-md-3 mx-md-4">
                                <h3 class="text-title">Edit Lesson</h3>
                                <div class="form-floating mb-3 custom-input">
                                    <input value="{{ $lesson->name }}" type="text" class="form-control text-label" id="name" name="name" required>
                                    <label for="name" class="text-label">Lesson Name</label>
                                </div>
                                <div class="form-floating mb-3 custom-input">
                                    <textarea class="form-control text-label" id="description" name="description" rows="5" required>{{ $lesson->description }}</textarea>
                                    <label for="description" class="text-label">Description</label>
                                </div>                          

                                <div class="form-floating mb-3 custom-input">
                                    <select class="form-select text-label" id="course_id" name="course_id" required>
                                        @foreach ($courses as $course)
                                          <option value="{{ $course->id }}" class="select-option">{{ $course->name }}</option>
                                        @endforeach
                                    </select>
                                    <label for="category" class="form-label text-label">Course</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 custom-column">
                            <img class="img-upload" src="{{ asset('app_images/course/upload.png') }}">
                            <div class="mb-3 custom-input">
                                <label for="video_file_name" class="form-label text-label">Choose video</label>
                                <input type="file" class="form-control text-label" id="video_file_name" name="video_file_name" accept="image/*">
                            </div>
                        </div>
                    </div>
                    <div class="row g-1 custom-row">
                        <button type="submit" class="btn btn-darkbtn-block fa-lg gradient-custom-2 mb-3" style="width: 100%; border-color: #6AA7B9; font-family: 'Orbitron', sans-serif; width: 90%;">Update Lesson</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection