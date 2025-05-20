@extends('layouts.master')

@section('content')
    <table class="table border-dark" style="padding-left: 20px; height: 900px;">
        <thead class="thead-dark">
            <tr>
                <th style="background-color: #eeeeee; width: 500px; height: 75px;">
                </th>
                <th style="border: 0; border-bottom: 1px solid #000000; border-right: 1px solid #000000; border-left: 1px solid #000000; background-color: #eeeeee;">
                    <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #e93578; font-size: 26px; text-align: center; padding-bottom: 10px;"> Administration Panel </p>
                </th>
                <th style="background-color: #eeeeee; width: 500px;">
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="background-color: #eeeeee; border: 0"></td>
                <td style="border: 0; border-left: 1px solid #000000; border-right: 1px solid #000000; background-color: #eeeeee;">
                    <div style="text-align: center;">
                        <a href="http://localhost:8000/admin/users" class="btn btn-custom-admin" style="font-family: 'Orbitron', sans-serif; color: #000000; font-size: 20px; padding-top: 30px;"><b>Users</b></a>
                    </div>
                    <div style="text-align: center;">
                        <a href="http://localhost:8000/admin/categories" class="btn btn-custom-admin" style="font-family: 'Orbitron', sans-serif; color: #000000; font-size: 20px; padding-top: 30px;"><b>Categories</b></a>
                    </div>
                    <div style="text-align: center;">
                        <a href="http://localhost:8000/admin/addresses" class="btn btn-custom-admin" style="font-family: 'Orbitron', sans-serif; color: #000000; font-size: 20px; padding-top: 30px;"><b>Addresses</b></a>
                    </div>
                    <div style="text-align: center;">
                        <a href="http://localhost:8000/admin/lessons" class="btn btn-custom-admin" style="font-family: 'Orbitron', sans-serif; color: #000000; font-size: 20px; padding-top: 30px;"><b>Lessons</b></a>
                    </div>
                    <div style="text-align: center;">
                        <a href="http://localhost:8000/admin/courses" class="btn btn-custom-admin" style="font-family: 'Orbitron', sans-serif; color: #000000; font-size: 20px; padding-top: 30px;"><b>Courses</b></a> 
                    </div> 
                </td>
            </tr>
        </tbody>
    </table>
@endsection

@section('scripts')
@endsection
