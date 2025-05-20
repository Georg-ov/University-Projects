@extends('layouts.master')

@section('title', 'My Courses')

@push('styles')

<style>
    p {
        font-family: 'Orbitron', sans-serif;
        font-size: 16px;
        color: black;
        margin-bottom: 20px;
    }

    .btn-teacher:link,
    .btn-teacher:visited {
        text-transform: uppercase;
        text-decoration: none;
        color: rgb(27, 27, 27);
        padding: 10px 30px;
        border: 1px solid;
        border-radius: 1000px;
        display: inline-block;
        -webkit-transition: all .2s;
        transition: all .2s;
        position: relative;
    }

    .btn-teacher:hover {
        -webkit-transform: translateY(-5px);
            -ms-transform: translateY(-5px);
                transform: translateY(-5px);
        -webkit-box-shadow: 0 10px 20px rgba(27, 27, 27, .5);
                box-shadow: 0 10px 20px rgba(27, 27, 27, .5);
    }

    .btn-teacher:active {
        -webkit-transform: translateY(-3px);
            -ms-transform: translateY(-3px);
                transform: translateY(-3px);
    }

    .btn-teacher::after {
        content: "";
        display: inline-block;
        height: 100%;
        width: 100%;
        border-radius: 100px;
        top: 0;
        left: 0;
        position: absolute;
        z-index: -1;
        -webkit-transition: all .3s;
        transition: all .3s;
    }

    .btn-teacher:hover::after {
        background-color: rgb(0, 238, 255);
        -webkit-transform: scaleX(1.4) scaleY(1.5);
            -ms-transform: scaleX(1.4) scaleY(1.5);
                transform: scaleX(1.4) scaleY(1.5);
        opacity: 0;
    }
</style>

@endpush

@section('content')

<head>
    <title>Courses</title>
    <link rel="stylesheet" href="{{ asset('css/courses/courses.css') }}">
</head>

<div class="container">
    <div class="gradient-custom-2">
        <div class="row align-items-center filter-row">
            <div class="col-lg-6 col-md-12 col-sm-12 col-12">
            </div>
            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <a href={{ route('course.create.show') }} class="btn-teacher">Add Course</a>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-12 col-12">
                <a href="/lessons/create" class="btn-teacher">Add Lesson</a>
            </div>
        </div>
    </div>
    <div class="row">

        @forelse ($user->impartedCourses as $course)
        <div class="col-lg-3 col-md-4 col-sm-6 col-12" style="padding-top: 20px;">
            <div class="card">
                <img src="{{ asset($course->image_file_name) }}" class="card-img-top" alt="course image">
                <div class="card-body">
                    <p class="card-text"> {{ $course->name }} </p>
                </div>
                <a href="{{ route('course.show', ['id' => $course->id]) }}" class="btn btn-primary">VIEW COURSE</a>
            </div>
        </div>

        @empty
        <div style="padding-top: 20px;">
            <p>You have no courses yet.</p>
        </div>
        @endforelse

    </div>
</div>

@endsection