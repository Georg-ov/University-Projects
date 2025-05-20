@extends('layouts.master')

@section('content')
<div class="container">
    <h1>Restore Database Backup</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form action="{{ route('restore.backup') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="backup_file">Backup File:</label>
            <input type="file" id="backup_file" name="backup_file" class="form-control" accept=".zip" required>
        </div>
        <button type="submit" class="btn btn-primary">Restore Backup</button>
    </form>
</div>
@endsection
