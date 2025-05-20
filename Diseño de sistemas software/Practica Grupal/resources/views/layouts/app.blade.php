<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Noto+Serif+Lao&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Orbitron&display=swap">

    <style>
        /* Color de fondo de la página */
        body {
            background-color: #eeeeee;
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

    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            
                <a class="navbar-brand" href="/">
                    <img src={{ asset("app_images/moktys-logo.png") }} class="d-inline-block align-top brand-img" alt="Your Logo">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <script src="{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
