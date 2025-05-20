@extends('layouts.master')

@section('title', 'About Us - Online Courses Company')

@section('content')
    <div class="container py-5">
        <div class="text-center">
            <h1 class="mb-4" style="font-family: 'Orbitron', sans-serif; color: #000000;">About Us</h1>
        </div>
        
        <div class="row">
            <div class="col-12">
                <h3 style="font-family: 'Orbitron', sans-serif; font-weight: bold; color: #000000;">Our Company</h3>
                <p class="lead">We are a leading provider of online courses, offering high-quality educational content to learners around the globe. Our mission is to make learning accessible, convenient, and enjoyable for everyone.</p>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4" style="font-family: 'Orbitron', sans-serif; font-weight: bold; color: #000000;">Meet Our Team</h2>
            </div>

            <!-- Using Bootstrap classes for responsive columns -->
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card" style="background: linear-gradient(to right, #D79292, #60A9BD);">
                    <div class="card-body text-center" style="color: white;">
                        <h5 class="card-title" style="font-family: 'Orbitron', sans-serif; font-weight: bold;">Luna</h5>
                        <p class="card-text">Founder</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card" style="background: linear-gradient(to right, #D79292, #60A9BD);">
                    <div class="card-body text-center" style="color: white;">
                        <h5 class="card-title" style="font-family: 'Orbitron', sans-serif; font-weight: bold;">Carolina</h5>
                        <p class="card-text">CEO</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card" style="background: linear-gradient(to right, #D79292, #60A9BD);">
                    <div class="card-body text-center" style="color: white;">
                        <h5 class="card-title" style="font-family: 'Orbitron', sans-serif; font-weight: bold;">Guillermo</h5>
                        <p class="card-text">Astrophysicist Programmer</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card" style="background: linear-gradient(to right, #D79292, #60A9BD);">
                    <div class="card-body text-center" style="color: white;">
                        <h5 class="card-title" style="font-family: 'Orbitron', sans-serif; font-weight: bold;">Pablo</h5>
                        <p class="card-text">Manager</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <div class="card" style="background: linear-gradient(to right, #D79292, #60A9BD);">
                    <div class="card-body text-center" style="color: white;">
                        <h5 class="card-title" style="font-family: 'Orbitron', sans-serif; font-weight: bold;">Georg</h5>
                        <p class="card-text">Customer Support</p>
                    </div>
                </div>
            </div>
        </div>
            
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4" style="font-family: 'Orbitron', sans-serif; font-weight: bold; color: #000000;">Our Mission</h2>
                <p class="lead text-center">Our mission is to empower individuals through education. We believe that learning is a lifelong journey, and we strive to provide the tools and resources needed for personal and professional growth.</p>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-12">
                <h2 class="text-center mb-4" style="font-family: 'Orbitron', sans-serif; font-weight: bold; color: #000000;">Collaborators</h2>
            </div>
            <div class="row mt-3">
                <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-4">
                    <h5 style="font-family: 'Orbitron', sans-serif; font-weight: bold;">Orbitron</h5>
                    <img src="{{ asset('app_images/collaborators/orbitron.png') }}" class="d-inline-block align-top brand-img img-fluid" style="height: 300px;">
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-4">
                    <h5 style="font-family: 'Orbitron', sans-serif; font-weight: bold;">UA</h5>
                    <img src="{{ asset('app_images/collaborators/uatron.jpg') }}" class="d-inline-block align-top brand-img img-fluid" style="height: 300px;">
                </div>
                <div class="col-lg-4 col-md-6 col-sm-12 text-center mb-4">
                    <h5 style="font-family: 'Orbitron', sans-serif; font-weight: bold;">Kebab Amigo</h5>
                    <img src="{{ asset('app_images/collaborators/kebabamigotron.png') }}" class="d-inline-block align-top brand-img img-fluid" style="height: 300px;">
                </div>
            </div>
        </div>
    </div>
@endsection
