@extends('layouts.master')
@section('title', 'All Courses')

@section('content')
<div style="padding-top: 20px; padding-bottom: 10px; padding-left: 5px;">
    <p class="font-weight-bold h2 mx-auto" style="font-family: 'Orbitron', sans-serif; color: #e93578; font-size: 26px;">Courses Administration</p>
</div>
<div style="display: flex;
    flex-direction: column;
    align-items: flex-end;">
    <a href="{{ route('course.create.show') }}" class="btn btn-outline-success custom-search-button">New Course</a>
</div>

<div class="inline-flex">
    <form class="form-inline my-2 my-lg-0 custom-search-form" action="{{ route('courses.filter') }}" method="GET">
        <div class="filter">
            <label for="category" class="mr-sm-2">Category</label>
            <select class="form-control form-control-sm mr-sm-2 custom-search-input" id="category" name="category">
                <option value="">All categories</option>
                @foreach ($categories as $category)
                <option value="{{ $category->id }}" @if (request('category')==$category->id) selected @endif>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter">
            <label for="visibility" class="mr-sm-2">Visibility</label>
            <select class="form-control form-control-sm mr-sm-2 custom-search-input" id="visibility" name="visibility">
                <option value="" @if (request('visibility')===null) selected @endif>All Visibilities</option>
                <option value="1" @if (request('visibility')==1) selected @endif>Visible</option>
                <option value="0" @if (request('visibility')==='0' ) selected @endif>Not Visible</option>
            </select>
        </div>
        <div class="filter">
            <label for="name" class="mr-sm-2">Search by name</label>
            <input class="form-control form-control-sm custom-search-input" type="search" name="name" id="name" placeholder="Course name" value="{{ request('name') }}">
        </div>
        <button class="btn btn-outline-success custom-search-button filter" type="submit">Apply Filters</button>
        <button class="btn btn-outline-success custom-search-button" type="button" onclick="clearFilters()">Clear Filters</button>
    </form>



    <form action="{{ route('courses.order') }}" method="GET" class="form-inline my-2 my-lg-0 custom-search-form">
        <div class="d-flex form-group mr-sm-2">
            <select class="form-control form-control-sm mr-sm-2 custom-search-input" id="sort" name="sort">
                <option value="id" {{ request('sort') == 'id' ? 'selected' : '' }}>Order by ID</option>
                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Order by Name</option>
            </select>
            <button type="submit" class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button">Apply order</button>
        </div>
    </form>

</div>
</div>
<div style="min-height: 100vh; margin-right: 10px; margin-left: 10px; overflow-x: scroll;">
    <table class="table table-hover border-bottom border-dark" style="padding-left: 20px;">
        <thead class="thead-dark">
            <tr>
                <th class="th-data" style="width: 5%;">Id</th>
                <th class="th-data" style="width: 15%;">Name</th>
                <th class="th-data" style="width: 20%;">Description</th>
                <th class="th-data" style="width: 5%;">Free</th>
                <th class="th-data" style="width: 5%;">Lessons</th>
                <th class="th-data" style="width: 5%;">Visibility</th>
                <th class="th-data" style="width: 10%;">Creation Date</th>
                <th class="th-data" style="width: 10%;">Category</th>
                <th class="th-data" style="width: 10%;">Professor</th>
                <th class="th-options" style="width: 15%;">Options</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($courses as $course)
            <tr>
                <td class="td-data"> {{ $course->id }} </td>
                <td class="td-data"> {{ $course->name }} </td>
                <td class="td-data"> {{ $course->description }} </td>
                <td class="td-data"> {{ $course->is_free ? 'Yes' : 'No' }} </td>
                <td class="td-data"> {{ $course->lessons->count() }} </td>
                <td class="td-data"> {{ $course->visibility ? 'Public' : 'Private'}} </td>
                <td class="td-data"> {{ $course->publish_date }} </td>
                <td class="td-data"> {{ $course->category->name }} </td>
                <td class="td-data"> <a href="{{ route('edit.user', ['id' => $course->user->id]) }}">{{ $course->user->username }}</a> </td>
                <td style="border: 1px solid #c0c0c0;">
                    <a href="{{ route('course.show', ['id' => $course->id]) }}" class="btn btn-sm btn-custom-success" style="color: #e93578; margin: 2px;">View Details</a>
                    <form action="{{ route('course.delete', ['id' => $course->id]) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-custom-success" style="margin: 2px;" onclick="return confirm('Are you sure you want to delete this course?')">Delete</button>
                    </form>
                    <a href="{{ route('course.update.show', ['id' => $course->id]) }}" class="btn btn-sm btn-custom-success" style="margin: 2px;">Edit</a>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="2" style="font-family: 'Noto Serif Lao', serif; font-size: 14px;">There are no courses available.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-center mt-4">
        {{ $courses->appends(['category' => request('category'), 'name' => request('name')])->links() }}
    </div>
</div>

<script>
    function clearFilters() {
        document.getElementById("category").value = "";
        document.getElementById("name").value = "";
        document.getElementById("visibility").value = "";
        document.querySelector("form").submit();
    }
</script>

<style>
    .th-data {
        background-color: #ffb4b0 !important;
        font-family: 'Noto Serif Lao', serif;
        font-size: 14px;
        border: 1px solid #c0c0c0;
    }

    .th-options {
        background-color: #006e8c !important;
        color: white !important;
        font-family: 'Noto Serif Lao', serif;
        font-size: 14px;
        text-align: center;
        border: 1px solid #c0c0c0;
    }

    .td-data {
        font-family: 'Noto Serif Lao', serif;
        font-size: 14px;
        border: 1px solid #c0c0c0;
    }

    .inline-flex {
        display: flex;
        justify-content: space-between;
        flex-direction: row;
    }

    .custom-search-input {
        max-width: fit-content;
        height: 100%;
    }

    .custom-search-form {
        align-items: flex-end;
    }

    .filter {
        margin-right: 10px;
    }
</style>



@endsection