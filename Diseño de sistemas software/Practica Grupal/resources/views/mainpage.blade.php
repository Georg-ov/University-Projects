@extends('layouts.master')

@push('styles')
<style>
    .container-premium {
        background-color: #ffffff;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        width: 460px;
        height: 120px;
        position: relative;
        border-radius: 6px;
        -webkit-transition: 0.3s ease-in-out;
        transition: 0.3s ease-in-out;
    }

    .container-premium:hover {
        -webkit-transform: scale(1.03);
            -ms-transform: scale(1.03);
                transform: scale(1.03);
        width: 220px;
    }
    .container-premium:hover .left-side {
        width: 100%;
    }

    .left-side {
        background-color: #5de2a3;
        width: 130px;
        height: 120px;
        border-radius: 4px;
        position: relative;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: center;
            -ms-flex-pack: center;
                justify-content: center;
        -webkit-box-align: center;
            -ms-flex-align: center;
                align-items: center;
        cursor: pointer;
        -webkit-transition: 0.3s;
        transition: 0.3s;
        -ms-flex-negative: 0;
            flex-shrink: 0;
        overflow: hidden;
    }

    .right-side {
        width: calc(100% - 130px);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
            -ms-flex-align: center;
                align-items: center;
        overflow: hidden;
        cursor: pointer;
        -webkit-box-pack: justify;
            -ms-flex-pack: justify;
                justify-content: space-between;
        white-space: nowrap;
        -webkit-transition: 0.3s;
        transition: 0.3s;
    }

    .right-side:hover {
        background-color: #f9f7f9;
    }

    .arrow {
        width: 20px;
        height: 20px;
        margin-right: 20px;
    }

    .new {
        font-size: 23px;
        font-family: "Lexend Deca", sans-serif;
        margin-left: 20px;
    }

    .card-premium {
        width: 70px;
        height: 46px;
        background-color: #c7ffbc;
        border-radius: 6px;
        position: absolute;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        z-index: 10;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
            -ms-flex-direction: column;
                flex-direction: column;
        -webkit-box-align: center;
            -ms-flex-align: center;
                align-items: center;
        -webkit-box-shadow: 9px 9px 9px -2px rgba(77, 200, 143, 0.72);
        -moz-box-shadow: 9px 9px 9px -2px rgba(77, 200, 143, 0.72);
        box-shadow: 9px 9px 9px -2px rgba(77, 200, 143, 0.72);
    }

    .card-line {
        width: 65px;
        height: 13px;
        background-color: #80ea69;
        border-radius: 2px;
        margin-top: 7px;
    }

    @media only screen and (max-width: 480px) {
        .container-premium {
            -webkit-transform: scale(0.7);
                -ms-transform: scale(0.7);
                    transform: scale(0.7);
        }
        .container-premium:hover {
            -webkit-transform: scale(0.74);
                -ms-transform: scale(0.74);
                    transform: scale(0.74);
        }

        .new {
            font-size: 18px;
        }
    }

    .buttons {
        width: 8px;
        height: 8px;
        background-color: #379e1f;
        -webkit-box-shadow: 0 -10px 0 0 #26850e, 0 10px 0 0 #56be3e;
                box-shadow: 0 -10px 0 0 #26850e, 0 10px 0 0 #56be3e;
        border-radius: 50%;
        margin-top: 5px;
        -webkit-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
                transform: rotate(90deg);
        margin: 10px 0 0 -30px;
    }

    .container-premium:hover .card-premium {
        -webkit-animation: slide-top 1.2s cubic-bezier(0.645, 0.045, 0.355, 1) both;
                animation: slide-top 1.2s cubic-bezier(0.645, 0.045, 0.355, 1) both;
    }

    .container-premium:hover .post {
        -webkit-animation: slide-post 1s cubic-bezier(0.165, 0.84, 0.44, 1) both;
                animation: slide-post 1s cubic-bezier(0.165, 0.84, 0.44, 1) both;
    }

    @-webkit-keyframes slide-top {
        0% {
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }
        50% {
            -webkit-transform: translateY(-70px) rotate(90deg);
            transform: translateY(-70px) rotate(90deg);
        }
        60% {
            -webkit-transform: translateY(-70px) rotate(90deg);
            transform: translateY(-70px) rotate(90deg);
        }
        100% {
            -webkit-transform: translateY(-8px) rotate(90deg);
            transform: translateY(-8px) rotate(90deg);
        }
    }

    @keyframes slide-top {
        0% {
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }
        50% {
            -webkit-transform: translateY(-70px) rotate(90deg);
            transform: translateY(-70px) rotate(90deg);
        }
        60% {
            -webkit-transform: translateY(-70px) rotate(90deg);
            transform: translateY(-70px) rotate(90deg);
        }
        100% {
            -webkit-transform: translateY(-8px) rotate(90deg);
            transform: translateY(-8px) rotate(90deg);
        }
    }

    .post {
        width: 63px;
        height: 75px;
        background-color: #dddde0;
        position: absolute;
        z-index: 11;
        bottom: 10px;
        top: 120px;
        border-radius: 6px;
        overflow: hidden;
    }

    .post-line {
        width: 47px;
        height: 9px;
        background-color: #545354;
        position: absolute;
        border-radius: 0px 0px 3px 3px;
        right: 8px;
        top: 8px;
    }

    .post-line:before {
        content: "";
        position: absolute;
        width: 47px;
        height: 9px;
        background-color: #757375;
        top: -8px;
    }

    .screen {
        width: 47px;
        height: 23px;
        background-color: #ffffff;
        position: absolute;
        top: 22px;
        right: 8px;
        border-radius: 3px;
    }

    .numbers {
        width: 12px;
        height: 12px;
        background-color: #838183;
        -webkit-box-shadow: 0 -18px 0 0 #838183, 0 18px 0 0 #838183;
                box-shadow: 0 -18px 0 0 #838183, 0 18px 0 0 #838183;
        border-radius: 2px;
        position: absolute;
        -webkit-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
                transform: rotate(90deg);
        left: 25px;
        top: 52px;
    }

    .numbers-line2 {
        width: 12px;
        height: 12px;
        background-color: #aaa9ab;
        -webkit-box-shadow: 0 -18px 0 0 #aaa9ab, 0 18px 0 0 #aaa9ab;
                box-shadow: 0 -18px 0 0 #aaa9ab, 0 18px 0 0 #aaa9ab;
        border-radius: 2px;
        position: absolute;
        -webkit-transform: rotate(90deg);
            -ms-transform: rotate(90deg);
                transform: rotate(90deg);
        left: 25px;
        top: 68px;
    }

    @-webkit-keyframes slide-post {
        50% {
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }
        100% {
            -webkit-transform: translateY(-70px);
            transform: translateY(-70px);
        }
    }

    @keyframes slide-post {
        50% {
            -webkit-transform: translateY(0);
            transform: translateY(0);
        }
        100% {
            -webkit-transform: translateY(-70px);
            transform: translateY(-70px);
        }
    }

    .dollar {
        position: absolute;
        font-size: 16px;
        font-family: "Lexend Deca", sans-serif;
        width: 100%;
        left: 0;
        top: 0;
        color: #4b953b;
        text-align: center;
    }

    .container-premium:hover .dollar {
        -webkit-animation: fade-in-fwd 0.3s 1s backwards;
                animation: fade-in-fwd 0.3s 1s backwards;
    }

    @-webkit-keyframes fade-in-fwd {
        0% {
            opacity: 0;
            -webkit-transform: translateY(-5px);
                    transform: translateY(-5px);
        }
        100% {
            opacity: 1;
            -webkit-transform: translateY(0);
                    transform: translateY(0);
        }
    }

    @keyframes fade-in-fwd {
        0% {
            opacity: 0;
            -webkit-transform: translateY(-5px);
                    transform: translateY(-5px);
        }
        100% {
            opacity: 1;
            -webkit-transform: translateY(0);
                    transform: translateY(0);
        }
    }

    .btn-main-first:link,
    .btn-main-first:visited {
        text-transform: uppercase;
        text-decoration: none;
        color: rgb(255, 255, 255);
        padding: 10px 30px;
        border: 1px solid;
        border-radius: 1000px;
        display: inline-block;
        -webkit-transition: all .2s;
        transition: all .2s;
        position: relative;
    }

    .btn-main-first:hover {
        -webkit-transform: translateY(-5px);
            -ms-transform: translateY(-5px);
                transform: translateY(-5px);
        -webkit-box-shadow: 0 10px 20px rgba(255, 255, 255, .5);
                box-shadow: 0 10px 20px rgba(255, 255, 255, .5);
    }

    .btn-main:link,
    .btn-main:visited {
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

    .btn-main:hover {
        -webkit-transform: translateY(-5px);
            -ms-transform: translateY(-5px);
                transform: translateY(-5px);
        -webkit-box-shadow: 0 10px 20px rgba(27, 27, 27, .5);
                box-shadow: 0 10px 20px rgba(27, 27, 27, .5);
    }

    .btn-main:active {
        -webkit-transform: translateY(-3px);
            -ms-transform: translateY(-3px);
                transform: translateY(-3px);
    }

    .btn-main::after {
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

    .btn-main:hover::after {
        background-color: rgb(0, 238, 255);
        -webkit-transform: scaleX(1.4) scaleY(1.5);
            -ms-transform: scaleX(1.4) scaleY(1.5);
                transform: scaleX(1.4) scaleY(1.5);
        opacity: 0;
    }
</style>
@endpush

@section('title', 'Página Principal')

<!-- la imagen de los no se adapta corretamente -->
@section('content')
    <!-- Sección 1 -->
    <section class="py-0 bg-light">
        <div class="container-fluid px-0">
            <div class="row align-items-start">
                <!-- Columna con la imagen -->
                <div class="col-md-6" style="padding-left: 0px;padding-right: 0px;">
                    @php
                        $firstCourse = $popularCourses->first();
                    @endphp
                    <img src="{{ asset($firstCourse->image_file_name) }}" style="width: 100%; height: 300px;">
                </div>

                <div class="col-md-6 bg-dark text-white p-4" style="height: 300px;">
                    <div class="d-flex flex-column justify-content-center align-items-center" style="border-bottom-style: solid;border-bottom-width: 0px;margin-top: 40px;margin-bottom: 20px;">  
                        <h1 class="text-center mb-4 font-weight-bold" style="font-family: 'Orbitron', sans-serif;">RECOMMENDED</h1>
                        @if ($firstCourse)
                            <p class="text-center mb-5" style="font-family: 'Orbitron', sans-serif;">{{ $firstCourse->name }}</p>
                            @guest
                                <a href="#" class="btn-main-first" style="font-family: 'Orbitron', sans-serif;" onclick="return confirm('You need to be registered!')">Go In</a>
                            @else
                                <a href="{{ route('course.show', ['id' => $firstCourse->id]) }}" class="btn-main-first" style="font-family: 'Orbitron', sans-serif;">Go In</a>
                            @endguest
                        @else
                            <p class="text-center" style="font-family: 'Orbitron', sans-serif;">No se encontró ningún curso.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    @guest
    @else
    @if (Auth::user()->subscription_type == 'FREEMIUM')
        <div style="padding-top: 30px;">
            <a href="/subscribe" style="text-decoration: none;">
                <div class="container container-premium">
                    <div class="left-side">
                        <div class="card-premium">
                            <div class="card-line"></div>
                            <div class="buttons"></div>
                        </div>
                        <div class="post">
                                <div class="post-line"></div>
                                    <div class="screen">
                                <div class="dollar">$</div>
                            </div>
                            <div class="numbers"></div>
                            <div class="numbers-line2"></div>
                        </div>
                    </div>
                    <div class="right-side">
                        <div class="new" style="font-family: 'Orbitron', sans-serif; color: black;">GO PREMIUM NOW</div>
                        <svg viewBox="0 0 451.846 451.847" height="512" width="512" xmlns="http://www.w3.org/2000/svg" class="arrow"><path fill="#cfcfcf" data-old_color="#000000" class="active-path" data-original="#000000" d="M345.441 248.292L151.154 442.573c-12.359 12.365-32.397 12.365-44.75 0-12.354-12.354-12.354-32.391 0-44.744L278.318 225.92 106.409 54.017c-12.354-12.359-12.354-32.394 0-44.748 12.354-12.359 32.391-12.359 44.75 0l194.287 194.284c6.177 6.18 9.262 14.271 9.262 22.366 0 8.099-3.091 16.196-9.267 22.373z"></path></svg>    
                    </div>
                </div>
            </a>
        </div>
    @endif
    @endguest

    <section class="py-5">
        <div class="container">
            <h1 class="text-center mb-4" style="font-family: 'Orbitron', sans-serif; color:black;">Most Popular Courses</h1>
            <div class="row">
                @foreach ($popularCourses as $course)
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <img src="{{ asset($course->image_file_name) }}" alt="{{ $course->name }}" class="card-img-top" style="height: 200px;">
                            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                                <h5 class="card-title" style="height: 5rem;">{{ $course->name }}</h5>
                                @guest
                                    <a href="#" class="btn-main" style="font-family: 'Orbitron', sans-serif;" onclick="return confirm('You need to be registered!')">Learn More</a>
                                @else
                                    <a href="{{ route('course.show', ['id' => $course->id]) }}" class="btn-main" style="font-family: 'Orbitron', sans-serif;">Learn More</a>
                                @endguest
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

@endsection

@section('scripts')

@endsection