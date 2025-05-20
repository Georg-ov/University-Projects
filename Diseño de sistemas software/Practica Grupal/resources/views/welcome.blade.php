@extends('layouts.master')

@section('content')

<body>
    <div class="container">
        <h1>Welcome to Moktys</h1>
        <p>We're glad you're here!</p>
        <a href="/admin" class="btn btn-sm btn-custom-success">Go to Administration Panel</a>
    </div>
</body>

<style>
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
</style>
@endsection