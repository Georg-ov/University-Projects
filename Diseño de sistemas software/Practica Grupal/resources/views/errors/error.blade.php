@extends('layouts.master')
@section('title', 'Error')
@section('content')

<body>
    <div class="error-message">
        <h1>{{ $title }}</h1>
        <p>{{ $message }}</p>
        <img src="{{ asset('icons/error.png') }}" alt="404 Not found Icon">
    </div>
</body>
<style>
    .error-message {
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
</style>

@endsection