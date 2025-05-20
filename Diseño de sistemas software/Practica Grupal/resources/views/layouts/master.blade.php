<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <!-- Incluir Bootstrap CSS -->
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Serif+Lao&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap">
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    @stack('styles')

    <style>
        /* Color de fondo de la página */
        body {
            background-color: #eeeeee;
        }

        footer {
            background-color: darkslategray;
            text-align: center;
            position: fixed;
            width: 100%;
            height: 6%;
            bottom: 0;
        }

        .footer-content {
            display: flex;
            flex-direction: column;
            justify-content: center;
            height: 100%;
        
        }

        .footer-link {
            color: #00FFFF;
            font-family: 'Orbitron';
            margin: 0 0.5rem;
            text-decoration: none;
        }

        .footer-text {
            color: rgb(114, 107, 107);
        }

        /* Botones Update y Delete */
        .btn-custom-success,
        .btn-custom-danger {
            color: #fff;
            background-color: #292c2f;
            border-color: #292c2f;
        }

        .brand-img {
            width: 100%;
            height: 3rem;
        }

        .search-bar {
            margin-right: 10px;
            display: flex;
            flex-direction: row;
        }

        /* Hover botones Update y Delete */
        .btn-custom-success:hover,
        .btn-custom-danger:hover {
            color: #fff;
            background-color: #ffb4b0;
            border-color: #292c2f;
        }

        /* Estilo de Paginación */
        .pagination>li>a,
        .pagination>li>span {
            position: relative;
            float: left;
            padding: 6px 12px;
            margin-left: 10px;
            line-height: 1.42857143;
            color: #000000;
            font-family: 'Noto Serif Lao', serif;
            font-size: 14px;
            text-decoration: none;
            background-color: #ffb4b0;
            border: 1px solid #c0c0c0;
        }

        .pagination>.active>a,
        .pagination>.active>span,
        .pagination>.active>a:hover,
        .pagination>.active>span:hover,
        .pagination>.active>a:focus,
        .pagination>.active>span:focus {
            z-index: 3;
            color: #fff;
            background-color: #006e8c;
            border-color: #c0c0c0;
            cursor: default;
        }

        .pagination>li>a:hover,
        .pagination>li>span:hover,
        .pagination>li>a:focus,
        .pagination>li>span:focus {
            z-index: 2;
            color: #fff;
            background-color: #006e8c;
            border-color: #c0c0c0;
        }

        /* Barra de búsqueda Admin */
        .custom-search-form {
            display: flex;
            padding-bottom: 20px;
        }

        .custom-search-input {
            flex-grow: 1;
        }

        .custom-search-button {
            margin-left: 5px;
            color: #e93578;
            border-color: #e93578;
        }

        .custom-search-button:hover {
            color: #fff;
            background-color: #e93578;
            border-color: #e93578;
        }

        .btn-update {
            color: #e93578;
            border-color: #e93578;
        }

        .btn-update:hover {
            color: #fff;
            background-color: #e93578;
            border-color: #e93578;
        }

        .card {
            padding: 30px 40px;
            margin-top: 60px;
            margin-bottom: 60px;
            border: none !important;
            box-shadow: 0 6px 12px 0 rgba(0, 0, 0, 0.2)
        }

        .blue-text {
            color: #00BCD4
        }

        .form-control-label {
            margin-bottom: 0
        }

        .btn-custom-admin {
            color: #fff;
            border-color: #AAAAAA;
            background: -webkit-linear-gradient(to right, #D79292, #60A9BD);
            background: linear-gradient(to right, #D79292, #60A9BD);
            height: 90px;
            width: 250px;
            margin-bottom: 20px;
            margin-top: 40px;
        }

        .btn-custom-admin:hover {
            color: #fff;
            border-color: #e93578;
        }

        hr.solid {
            color: #000000;
        }

        .gradient-custom-2 {
            background:  #6AA7B9;
            
            background: -webkit-linear-gradient(to right, #D79292, #D79292, #939FAA, #6AA7B9);
            
            background: linear-gradient(to right, #D79292, #D79292, #939FAA, #6AA7B9);
        }
            
        @media (min-width: 768px) {
            .gradient-form {
            height: 100vh !important;
            }
        }

        @media (min-width: 769px) {
            .gradient-custom-2 {
            border-top-right-radius: .3rem;
            border-bottom-right-radius: .3rem;
            }
        }

        .custom-input input.form-control {
            background-color: #282C2F;
            border-color: #AAAAAA;
            height: 50px;
        }

        .custom-input input.form-control:focus {
            border-color: #AAAAAA;
        }

        .custom-input .invalid-feedback {
            color: #F7F6F6; 
        }

        .form-floating>.form-control-plaintext~label::after, .form-floating>.form-control:focus~label::after, .form-floating>.form-control:not(:placeholder-shown)~label::after, .form-floating>.form-select~label::after {
            background-color: transparent;
        }

        @import url('https://fonts.googleapis.com/css?family=Roboto');

        body{
            font-family: 'Roboto', sans-serif;
        }
        * {
            margin: 0;
            padding: 0;
        }
        i {
            margin-right: 10px;
        }

        .navbar-nav,
        .navbar-nav .nav-link,
        .navbar-brand {
            font-family: 'Orbitron', sans-serif;
            font-size: 10px;
        }

        .navbar-logo{
            padding: 15px;
            color: #fff;
        }
        .navbar-mainbg{
            background-color: #292c2f;
            padding: 0px;
        }
        #navbarSupportedContent{
            overflow: hidden;
            position: relative;
        }
        #navbarSupportedContent ul{
            padding: 0px;
            margin: 0px;
        }
        #navbarSupportedContent ul li a i{
            margin-right: 10px;
        }
        #navbarSupportedContent li {
            list-style-type: none;
            float: left;
        }
        #navbarSupportedContent ul li a{
            color: rgba(255,255,255,0.5);
            text-decoration: none;
            font-size: 15px;
            display: block;
            padding: 20px 20px;
            transition-duration:0.6s;
            transition-timing-function: cubic-bezier(0.68, -0.55, 0.265, 1.55);
            position: relative;
        }
        #navbarSupportedContent>ul>li.active>a{
            color: #5161ce;
            background-color: transparent;
            transition: all 0.7s;
        }
        #navbarSupportedContent a:not(:only-child):after {
            content: "\f105";
            position: absolute;
            right: 20px;
            top: 10px;
            font-size: 14px;
            font-family: "Font Awesome 5 Free";
            display: inline-block;
            padding-right: 3px;
            vertical-align: middle;
            font-weight: 900;
            transition: 0.5s;
        }
        #navbarSupportedContent .active>a:not(:only-child):after {
            transform: rotate(90deg);
        }
        .hori-selector{
            display:inline-block;
            position:absolute;
            height: 100%;
            top: 0px;
            left: 0px;
            transition: all 0.3s ease;
            transition-duration:0.6s;
            transition-timing-function: cubic-bezier(0.68, -0.55, 0.265, 1.55);
            background-color: #fff;
            border-top-left-radius: 15px;
            border-top-right-radius: 15px;
            margin-top: 10px;
        }
        .hori-selector .right,
        .hori-selector .left{
            position: absolute;
            width: 25px;
            height: 25px;
            background-color: #fff;
            bottom: 10px;
        }
        .hori-selector .right{
            right: -25px;
        }
        .hori-selector .left{
            left: -25px;
        }
        .hori-selector .right:before,
        .hori-selector .left:before{
            content: '';
            position: absolute;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: #292c2f;
        }
        .hori-selector .right:before{
            bottom: 0;
            right: -25px;
        }
        .hori-selector .left:before{
            bottom: 0;
            left: -25px;
        }


        @media(min-width: 992px){
            .navbar-expand-custom {
                -ms-flex-flow: row nowrap;
                flex-flow: row nowrap;
                -ms-flex-pack: start;
                justify-content: flex-start;
            }
            .navbar-expand-custom .navbar-nav {
                -ms-flex-direction: row;
                flex-direction: row;
            }
            .navbar-expand-custom .navbar-toggler {
                display: none;
            }
            .navbar-expand-custom .navbar-collapse {
                display: -ms-flexbox!important;
                display: flex!important;
                -ms-flex-preferred-size: auto;
                flex-basis: auto;
            }
        }


        @media (max-width: 991px){
            #navbarSupportedContent ul li a{
                padding: 12px 30px;
            }
            .hori-selector{
                margin-top: 0px;
                margin-left: 10px;
                border-radius: 0;
                border-top-left-radius: 25px;
                border-bottom-left-radius: 25px;
            }
            .hori-selector .left,
            .hori-selector .right{
                right: 10px;
            }
            .hori-selector .left{
                top: -25px;
                left: auto;
            }
            .hori-selector .right{
                bottom: -25px;
            }
            .hori-selector .left:before{
                left: -25px;
                top: -25px;
            }
            .hori-selector .right:before{
                bottom: -25px;
                left: -25px;
            }
        }

        .setting-btn {
            margin-right: 10px;
            width: 45px;
            height: 45px;
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
            gap: 6px;
            background-color: rgb(129, 110, 216);
            border-radius: 10px;
            cursor: pointer;
            border: none;
            -webkit-box-shadow: 0px 0px 0px 2px rgb(212, 209, 255);
            box-shadow: 0px 0px 0px 2px rgb(212, 209, 255);
        }

        .bar {
            width: 50%;
            height: 2px;
            background-color: rgb(229, 229, 229);
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            position: relative;
            border-radius: 2px;
        }

        .bar::before {
            content: "";
            width: 2px;
            height: 2px;
            background-color: rgb(126, 117, 255);
            position: absolute;
            border-radius: 50%;
            border: 2px solid white;
            -webkit-transition: all 0.3s;
            transition: all 0.3s;
            -webkit-box-shadow: 0px 0px 5px white;
            box-shadow: 0px 0px 5px white;
        }

        .bar1::before {
            -webkit-transform: translateX(-4px);
            -ms-transform: translateX(-4px);
            transform: translateX(-4px);
        }

        .bar2::before {
            -webkit-transform: translateX(4px);
            -ms-transform: translateX(4px);
            transform: translateX(4px);
        }

        .setting-btn:hover .bar1::before {
            -webkit-transform: translateX(4px);
            -ms-transform: translateX(4px);
            transform: translateX(4px);
        }
        
        .setting-btn:hover .bar2::before {
            -webkit-transform: translateX(-4px);
            -ms-transform: translateX(-4px);
            transform: translateX(-4px);
        }

    </style>

</head>

<body>
<nav class="navbar navbar-expand-custom navbar-mainbg">
        <a class="navbar-brand navbar-logo" href={{ route('mainpage') }}>
            <img src={{ asset("app_images/moktys-logo.png") }} class="d-inline-block align-top brand-img" alt="Your Logo">
        </a>
        <button class="setting-btn navbar-toggler" type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="bar bar1"></span>
            <span class="bar bar2"></span>
            <span class="bar bar1"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <div class="hori-selector"><div class="left"></div><div class="right"></div></div>
                <li class="nav-item active">
                    <a class="home-link" href={{ route('mainpage') }}><i class="fas fa-tachometer-alt"></i>Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href={{ route('availableCourses') }}><i class="far fa-address-book"></i>Courses</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href={{ route('about-us') }}><i class="far fa-clone"></i>About Us</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href={{ route('contact-us') }}><i class="far fa-calendar-alt"></i>Contact Us</a>
                </li>
                @auth
                @if (Auth::user()->role_type == 'TEACHER')
                <li class="nav-item">
                    <a class="nav-link" href={{ route('teacher.my-courses') }}><i class="far fa-calendar-alt"></i>My Courses</a>
                </li>
                @endif
                @endauth
                @auth
                    @if (Auth::user()->role_type == 'ADMIN')
                        <li class="nav-item">
                            <a class="nav-link" href={{ route('admin.panel') }}><i class="far fa-calendar-alt"></i>Administration</a>
                        </li>
                    @endif
                @endauth
            </ul>
            <ul class="navbar-nav ms-auto">
                @guest
                    @if (Route::has('login'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}"><i class="far fa-login"></i>{{ __('Login') }}</a>
                        </li>
                    @endif

                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}"><i class="far fa-register"></i>{{ __('Register') }}</a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a class="nav-link" href={{ route('profile') }}><i class="far fa-profile"></i>Profile</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" style="color: #DD0000;"><i class="far fa-logout"></i>{{ __('Logout') }}</a>
                    </li>                    
                    
                    @if (Auth::user()->subscription_type == 'FREEMIUM')
                    <li>
                        <a href={{ route('subscribe') }} class="nav-link" style="color: #00FFFF;"><i>Go PREMIUM!</i></a>
                    </li>
                    
                    @endif
                @endguest
            </ul>
        </div>
    </nav>

    <div class="container-fluid">
        @yield('content')
    </div>
    
    <div style="height: 275px;"></div>

    <footer class="bg-dark text-center py-3">
        <div class="footer-content">
            <div class="row">
                <div class="col" style="margin-top: 15px;">
                    <a href="{{ route('terms-of.service') }}" class="footer-link">Terms of Service</a>
                    <a href="{{ route('about-us') }}" class="footer-link">About Us</a>
                    <a href="{{ route('contact-us') }}" class="footer-link">Contact Us</a>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <p class="footer-text" style="font-family: 'Orbitron'">© 2024 Moktys</p>
                </div>
            </div>
        </div>
    </footer>
     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        function test(){
            var tabsNewAnim = $('#navbarSupportedContent');
            var selectorNewAnim = $('#navbarSupportedContent').find('li').length;
            var activeItemNewAnim = tabsNewAnim.find('.active');
            var activeWidthNewAnimHeight = activeItemNewAnim.innerHeight();
            var activeWidthNewAnimWidth = activeItemNewAnim.innerWidth();
            var itemPosNewAnimTop = activeItemNewAnim.position();
            var itemPosNewAnimLeft = activeItemNewAnim.position();
            $(".hori-selector").css({
                "top":itemPosNewAnimTop.top + "px", 
                "left":itemPosNewAnimLeft.left + "px",
                "height": activeWidthNewAnimHeight + "px",
                "width": activeWidthNewAnimWidth + "px"
            });
            $("#navbarSupportedContent").on("click","li",function(e){
                $('#navbarSupportedContent ul li').removeClass("active");
                $(this).addClass('active');
                var activeWidthNewAnimHeight = $(this).innerHeight();
                var activeWidthNewAnimWidth = $(this).innerWidth();
                var itemPosNewAnimTop = $(this).position();
                var itemPosNewAnimLeft = $(this).position();
                $(".hori-selector").css({
                    "top":itemPosNewAnimTop.top + "px", 
                    "left":itemPosNewAnimLeft.left + "px",
                    "height": activeWidthNewAnimHeight + "px",
                    "width": activeWidthNewAnimWidth + "px"
                });
            });
        }
        $(document).ready(function(){
            setTimeout(function(){ test(); });
        });
        $(window).on('resize', function(){
            setTimeout(function(){ test(); }, 500);
        });
        $(".navbar-toggler").click(function(){
            $(".navbar-collapse").slideToggle(300);
            setTimeout(function(){ test(); });
        });

        jQuery(document).ready(function($){
            var path = window.location.pathname.split("/").pop();

            if ( path == '' ) {
                path = 'index.html';
            }

            var target = $('#navbarSupportedContent ul li a[href="'+path+'"]');
            target.parent().addClass('active');
        });

        $(document).ready(function() {
            // Event listener for clicking on the logo
            $("#home-link").click(function() {
                $("#navbarSupportedContent ul.navbar-nav li").removeClass("active");
                $("home-link").addClass("active");
            });
        });

        $(window).on('load',function () {
            var current = location.pathname;
            console.log(current);
            $('#navbarSupportedContent ul li a').each(function(){
                var $this = $(this);
                if(current.startsWith('/admin') && $this.attr('href').includes('/admin')){
                    $this.parent().addClass('active');
                } else if (current.startsWith('/profile') && $this.attr('href').includes('/profile')){
                    $this.parent().addClass('active');
                } else if (current.startsWith('/course') && $this.attr('href').includes('/course')) {
                    $this.parent().addClass('active');
                } else if($this.attr('href').indexOf(current) !== -1){
                    $this.parent().addClass('active');
                } else{
                    $this.parent().removeClass('active');
                }
            })
        });

        
    </script>
    
</body>

</html>