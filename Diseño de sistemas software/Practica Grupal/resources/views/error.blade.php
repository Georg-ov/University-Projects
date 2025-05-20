@extends('layouts.master')

@section('content')
<div class="error-container">
    <h1>Database Error</h1>
    <p>Critical tables are missing from the database. Please contact the administrator to restore the database.</p>
    <a href="{{ route('restore.form') }}" class="btn btn-danger">Restore Database</a>
</div>

<style>
    .error-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 80vh;
        text-align: center;
    }

    .error-container h1 {
        font-size: 3em;
        color: #e74c3c;
    }

    .error-container p {
        font-size: 1.5em;
        color: #2c3e50;
    }

    .btn-danger {
        font-size: 1.2em;
        color: #fff;
        background-color: #e74c3c;
        border: none;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        margin-top: 20px;
    }

    .btn-danger:hover {
        background-color: #c0392b;
    }

    body {
        font-family: 'Arial', sans-serif;
        background-color: #ecf0f1;
    }
</style>
@endsection
