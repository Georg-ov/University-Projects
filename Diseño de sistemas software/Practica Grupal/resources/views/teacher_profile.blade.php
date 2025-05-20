@extends('layouts.master')

@push('styles')
<style>
    .card-img-top {
        display: block;
        margin: 0 auto;
        height: 100px;
        width: 110px;
    }

    .profile-card {
        box-shadow: none !important;
        margin-bottom: 20px !important;
        margin-top: 10px !important;
    }
    .courses-card {
        height: auto;
        box-shadow: none !important;
    }
    .course-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .card-title, .card-text {
        font-family: Geneva, sans-serif;
    }

    /* Quita los bullet points */
    ul.list-group {
        list-style-type: none;
        padding-left: 0;
    }

    /* Añade una línea de separación entre los cursos */
    .list-group-item {
        border: none;
        border-bottom: 1px solid #ddd; /* Cambia este color según tus necesidades */
        padding: 10px 0;
    }

    /* Añade padding extra para separar los cursos */
    .list-group-item .row {
        margin: 0;
    }

    .list-group-item .col-md-4 {
        padding: 0 10px;
    }

    .category-images {
        height: 10px;
        width: 10px;
    }
</style>
@endpush

@section('title', 'Teacher Profile')

@section('content')

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card profile-card" style="margin-top: 60px !important;">
                <img src="{{ $user->profile_image ?? 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Default_pfp.svg/2048px-Default_pfp.svg.png' }}" class="card-img-top" alt="Profile Image">
                <div class="card-body text-center">
                    <h5 class="card-title"><strong>{{ $user->name }}</strong></h5>
                    <p class="card-text">@ {{ $user->username }}</p>
                </div>
            </div>
            <div class="card profile-card">
                <div class="card-body">
                    <h5 class="card-title" style="padding-bottom: 10px;"><strong>About Me</strong></h5>
                    @if ($user->about_me == NULL)
                        <p class="card-text" style="color: #d3d3d3;">This teacher doesn't have a description yet</p>
                    @else
                        <p class="card-text" style="color: #d3d3d3;">{{ $user->about_me }}</p>
                    @endif
                </div>
            </div>
            <div class="card profile-card">
                <div class="card-body">
                    <h5 class="card-title"><strong>Contact Info</strong></h5>
                    <ul class="list-unstyled card-text">
                        <li style="color: #d3d3d3; padding-bottom: 5px;">Email</li>
                        <li style="padding-left: 10px; padding-bottom: 5px;"><img style="width: 30px; length: 30px; padding-right: 10px;" src="{{ asset("app_images/email.webp") }}">{{ $user->email }}</li>
                        <li style="color: #d3d3d3; padding-bottom: 5px;">Location</li>
                        @if ($user->address == NULL)
                            <li style="padding-left: 10px;"><img style="width: 30px; length: 30px; padding-right: 10px;" src="{{ asset("app_images/location.png") }}">No location available.</li>
                        @else
                            <li style="padding-left: 10px;"><img style="width: 30px; length: 30px; padding-right: 10px;" src="{{ asset("app_images/location.png") }}">{{ $user->address->country }}</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card courses-card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Courses</h5>
                </div>
                <div class="card-body course-list card-text">
                    <ul class="list-group list-group-flush">
                        @if ($user->impartedCourses->count() == 0)
                            <li class="list-group-item">This teacher doesn't have any courses yet</li>
                        @else
                            @foreach($user->impartedCourses as $course)
                                <a href="http://127.0.0.1:8000/course/{{ $course->id }}" style="text-decoration: none;"> 
                                    <li class="list-group-item">
                                        <div class="row">
                                            <div class="col-md-4"><strong>{{ $course->name }}</strong></div>
                                            @if ($course->lessons->count() == 1)
                                                <div class="col-md-4">{{ $course->lessons->count() }} lesson</div>
                                            @else
                                                <div class="col-md-4">{{ $course->lessons->count() }} lessons</div>
                                            @endif
                                            <div class="col-md-4"><img class="category-images" src="{{ $course->category->image_file_name }}"> {{ $course->category->name }}</div>
                                        </div>
                                    </li>
                                </a>
                            @endforeach
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection