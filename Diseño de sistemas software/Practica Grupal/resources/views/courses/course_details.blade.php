@extends('layouts.master')

@push('styles')

<style>
    .btn-delete-comment {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: rgb(20, 20, 20);
        border: none;
        font-weight: 600;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        cursor: pointer;
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
        overflow: hidden;
        position: relative;
        gap: 2px;
    }

    .svgIcon {
        width: 12px;
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
    }

    .svgIcon path {
        fill: white;
    }

    .btn-delete-comment:hover {
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
        background-color: rgb(255, 69, 69);
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 0;
    }

    .bin-top {
        -webkit-transform-origin: bottom right;
        -ms-transform-origin: bottom right;
        transform-origin: bottom right;
    }
    .btn-delete-comment:hover .bin-top {
        -webkit-transition-duration: 0.5s;
        transition-duration: 0.5s;
        -webkit-transform: rotate(160deg);
        -ms-transform: rotate(160deg);
        transform: rotate(160deg);
    }
</style>

@endpush

@section('title', 'Course Details')

@section('content')
<div class="">
    <div class="row">
        <div class="col-12">
            <a href=" {{ route('availableCourses') }}" class="back-link">
                <img class="img-back" src="{{ asset('icons/left-arrow.png') }}" alt="404 Not found Icon">
                <button type="button" class="btn btn-dark">Back to Courses</button>
            </a>
        </div>
    </div>
    <div class="row justify-content-end">
        @if ($course->isOwner($user->id) || $user->isAdmin())
        <div class="col-12 col-md-3">
            <form action="{{ route('course.delete', ['id' => $course->id]) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-custom-success btn-edit" style="margin: 2px;" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
            </form>
        </div>
        <div class="col-12 col-md-3">
            <a href="{{ route('course.update.show', ['id' => $course->id]) }}" class="btn btn-custom-success btn-edit" style="margin: 2px;">Edit</a>
        </div>
        @endif
    </div>
    @if (!$course->is_free && !$user->hasPremium())
    <div class="row alert-row">
        <div class="col">
            <div class="alert alert-info" role="alert">
                This course is only available for Premium users. Subscribe now to watch this course!
                <a href="{{ route('subscribe') }}" class="btn-premium-profile">BECOME PREMIUM</a>
            </div>

        </div>
    </div>
    @endif
    <div class="main">

        <div class="row justify-content-center section-row" id="cover-page">
            <div class="col-md-8 col-12">
                <div class="card mb-3">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">{{ $course->name }}</h5>
                                <p class="card-text">{{ $course->description }}</p>
                                <p class="card-text"><small class="text-muted"> Created on {{ $course->publish_date }} </small></p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <img src={{ asset($course->image_file_name) }} class="img-fluid rounded-end" alt="...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center section-row" id="content" class="course-section">
            <div class="col-md-8 col-12">
                <div class="row justify-content-center">
                    <img src={{ asset("app_images/course/separator.png") }} class="content-separator">
                </div>
                <div class="row justify-content-center">
                    <h2 style="width: auto;">Course Content</h2>
                </div>
                <div class="row">
                    @if ($course->lessons->isEmpty())
                    <p>This course has no lessons.</p>
                    @else
                    <div class="flex-end">
                        <button type="button" class="btn btn-outline-dark" disabled>{{ count($course->lessons) }} LESSONS</button>
                    </div>
                    @foreach ($course->lessons as $lesson)
                    @auth
                    @if (Auth::user()->subscription_type == 'PREMIUM' || $course->is_free)
                    <div class="lesson" style="display: flex; align-items: center; margin-bottom: 20px;">
                        <a href="{{ url('/course/' . $course->id . '/lessons/' . $lesson->id) }}" style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                            <img src="{{ asset('app_images/course/play-button-arrowhead.png') }}" class="content-play-icon" style="width: 20px; height: 20px; margin-right: 10px;">
                            <p style="margin: 0;">{{ $lesson->name }}</p>
                            @if (Auth::user()->id == $course->user_id || Auth::user()->role_type == 'ADMIN')
                                <form action="{{ route('delete.teacherlesson') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $lesson->id }}">
                                    <button type="submit" class="btn-delete-comment" onclick="return confirm('Are you sure you want to delete this lesson?')" style="margin-left: 20px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 14" class="svgIcon bin-top">
                                            <g clip-path="url(#clip0_35_24)">
                                                <path fill="black" d="M20.8232 2.62734L19.9948 4.21304C19.8224 4.54309 19.4808 4.75 19.1085 4.75H4.92857C2.20246 4.75 0 6.87266 0 9.5C0 12.1273 2.20246 14.25 4.92857 14.25H64.0714C66.7975 14.25 69 12.1273 69 9.5C69 6.87266 66.7975 4.75 64.0714 4.75H49.8915C49.5192 4.75 49.1776 4.54309 49.0052 4.21305L48.1768 2.62734C47.3451 1.00938 45.6355 0 43.7719 0H25.2281C23.3645 0 21.6549 1.00938 20.8232 2.62734ZM64.0023 20.0648C64.0397 19.4882 63.5822 19 63.0044 19H5.99556C5.4178 19 4.96025 19.4882 4.99766 20.0648L8.19375 69.3203C8.44018 73.0758 11.6746 76 15.5712 76H53.4288C57.3254 76 60.5598 73.0758 60.8062 69.3203L64.0023 20.0648Z"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_35_24">
                                                    <rect fill="white" height="14" width="69"></rect>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 57" class="svgIcon bin-bottom">
                                            <g clip-path="url(#clip0_35_22)">
                                                <path fill="black" d="M20.8232 -16.3727L19.9948 -14.787C19.8224 -14.4569 19.4808 -14.25 19.1085 -14.25H4.92857C2.20246 -14.25 0 -12.1273 0 -9.5C0 -6.8727 2.20246 -4.75 4.92857 -4.75H64.0714C66.7975 -4.75 69 -6.8727 69 -9.5C69 -12.1273 66.7975 -14.25 64.0714 -14.25H49.8915C49.5192 -14.25 49.1776 -14.4569 49.0052 -14.787L48.1768 -16.3727C47.3451 -17.9906 45.6355 -19 43.7719 -19H25.2281C23.3645 -19 21.6549 -17.9906 20.8232 -16.3727ZM64.0023 1.0648C64.0397 0.4882 63.5822 0 63.0044 0H5.99556C5.4178 0 4.96025 0.4882 4.99766 1.0648L8.19375 50.3203C8.44018 54.0758 11.6746 57 15.5712 57H53.4288C57.3254 57 60.5598 54.0758 60.8062 50.3203L64.0023 1.0648Z"></path>
                                            </g>
                                            <defs>
                                                <clipPath id="clip0_35_22">
                                                    <rect fill="white" height="57" width="69"></rect>
                                                </clipPath>
                                            </defs>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </a>
                    </div>
                    @else
                    <div class="lesson" style="display: flex; align-items: center; margin-bottom: 20px;">
                        <a style="text-decoration: none; color: inherit; display: flex; align-items: center;">
                            <img src="{{ asset('app_images/course/play-button-arrowhead.png') }}" class="content-play-icon" style="width: 20px; height: 20px; margin-right: 10px;">
                            <p style="margin: 0;">{{ $lesson->name }}</p>
                        </a>
                    </div>
                    @endif
                    @endauth
                    @endforeach

                    @endif
                </div>
            </div>
        </div>
        <div class="row justify-content-center section-row">
            <div id="teacher" class="card teacher-card mb-3" style="max-width: 540px;">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src={{ asset("app_images/default-avatar.jpg") }} class="img-fluid rounded-start" alt="...">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">{{ $course->user->username }}</h5>
                            <a href="{{ route('show.profile', ['id' => $course->user->id]) }}" class="btn btn-custom-success">View Teacher Profile</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>


<style>
    .btn-premium-profile {
        width: -webkit-fit-content;
        width: -moz-fit-content;
        width: fit-content;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        padding: 0.8em 1.1em;
        gap: 0.4rem;
        border: none;
        font-weight: bold;
        border-radius: 30px;
        cursor: pointer;
        text-shadow: 2px 2px 3px rgb(136 0 136 / 50%);
        background: linear-gradient(15deg,
                #D79292, #60A9BD) no-repeat;
        background-size: 300%;
        background-position: left center;
        -webkit-transition: background 0.3s ease;
        transition: background 0.3s ease;
        color: #fff;
    }

    .btn-premium-profile {
        padding: 10px 20px;
        /* Ajusta el padding según sea necesario */
        font-size: 16px;
        /* Ajusta el tamaño de la fuente según sea necesario */
        text-decoration: none;
        /* Para eliminar subrayado en el enlace */
    }

    .btn-premium-profile:hover {
        background-size: 320%;
        background-position: right center;
    }

    .btn-premium-profile:hover svg {
        fill: #fff;
    }

    .btn-premium-profile svg {
        width: 23px;
        fill: #f09f33;
        -webkit-transition: 0.3s ease;
        transition: 0.3s ease;
    }


    .teacher-card {
        background-color: #292C2F;
        font-family: 'Orbitron';
        color: whitesmoke;
    }

    .btn-custom-success {
        background-color: #F06E9C;
    }

    .alert-row {
        position: fixed;
        z-index: 2;
        width: 100%;
        margin-top: 16px !important;
    }

    .section-row {
        padding: 55px 0;
    }

    .back-link {
        margin: 20px 0 0 20px;
        text-decoration: none;
        display: block;
        width: fit-content;
    }

    .container {
        padding: 0;
        margin: 0;
    }

    .container-fluid {
        padding: 0;
    }

    #content {
        background-color: #92d36e70;
    }

    .flex-end {
        display: flex;
        justify-content: flex-end;
        width: 100%;
        margin-bottom: 10px;
    }

    .row {
        margin: 0 5px;
    }

    .btn-edit {
        width: 100%;
        margin: 0 5px;
    }

    .img-back {
        height: 30px;
    }

    .content-play-icon {
        height: 23px;
        margin: 0 0 0 10px;
    }

    .alert {
        color: whitesmoke;
        background-color: #292C2F;
        border-color: transparent;
        display: flex;
        justify-content: space-between;
        align-items: center;
        
        a {
            font-family: 'Orbitron'
        }
    }

    .main {
        width: 100%;
        position: relative;
    }

    .lesson {
        background: #292C2F;
        width: 100%;
        border-radius: 5px;
        padding: 5px;
        margin-bottom: 20px;
        display: flex;
        flex-direction: row;
        flex-wrap: wrap;
        align-items: center;

        p {
            margin: 5px 0 5px 10px;
            color: whitesmoke;
        }
    }
</style>

@endsection