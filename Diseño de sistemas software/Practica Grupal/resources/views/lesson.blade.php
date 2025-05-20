@extends('layouts.master')

@push('styles')
<style>
    .rating:not(:checked) > input {
        position: absolute;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .rating:not(:checked) > label {
        float: right;
        cursor: pointer;
        font-size: 30px;
        color: #666;
    }

    .rating:not(:checked) > label:before {
        content: '★';
    }

    .rating > input:checked + label:hover,
    .rating > input:checked + label:hover ~ label,
    .rating > input:checked ~ label:hover,
    .rating > input:checked ~ label:hover ~ label,
    .rating > label:hover ~ input:checked ~ label {
        color: #e58e09;
    }

    .rating:not(:checked) > label:hover,
    .rating:not(:checked) > label:hover ~ label {
        color: #ff9e0b;
    }

    .rating > input:checked ~ label {
        color: #ffa723;
    }

    .form-group.flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 20px;
    }

    .add-comment-section {
        display: none;
    }

    .comments-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .add-comment-link {
        cursor: pointer;
        color: #e93578;
        text-decoration: none;
    }

    .add-comment-link:hover {
        text-decoration: underline;
    }

    .comment {
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
        background-color: #f9f9f9;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .comment-author {
        font-weight: bold;
    }

    .comment-rating {
        font-size: 14px;
        color: #777;
    }

    .comment-content {
        margin-top: 10px;
    }

    .comment-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 10px;
        font-size: 12px;
        color: #777;
    }

    .comment-date {
        margin-top: 0;
    }

    .btn-delete-comment {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: rgb(20, 20, 20);
        border: none;
        font-weight: 600;
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
        -webkit-box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.164);
        cursor: pointer;
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
        overflow: hidden;
        position: relative;
        gap: 2px;
    }

    .svgIcon {
        width: 12px;
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
    }

    .svgIcon path {
        fill: white;
    }

    .btn-delete-comment:hover {
        -webkit-transition-duration: 0.3s;
        transition-duration: 0.3s;
        background-color: rgb(255, 69, 69);
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 0;
    }

    .bin-top {
        -webkit-transform-origin: bottom right;
        -ms-transform-origin: bottom right;
        transform-origin: bottom right;
    }
    .btn-delete-comment:hover .bin-top {
        -webkit-transition-duration: 0.5s;
        transition-duration: 0.5s;
        -webkit-transform: rotate(160deg);
        -ms-transform: rotate(160deg);
        transform: rotate(160deg);
    }

    .content-wrapper {
        display: flex;
        flex-wrap: wrap;
    }

    .reproductor {
        flex: 7;
    }

    .lista-lecciones {
        flex: 0.5;
        min-width: 300px;
    }

    @media (max-width: 768px) {
        .content-wrapper {
            flex-direction: column;
        }
    }
</style>
@endpush

@section('title', 'Lesson')

@section('content')

<div class="botones-superiores" style="margin-top: 10px;">
    <a href="{{ route('course.show', ['id' => $courseId]) }}" class="btn" style="float: left; width: fit-content;">
        <img src="{{ asset('app_images/back-arrow.png') }}" style="width: 20px; height: 20px; margin-right: 5px;">
    </a>

    <a href="{{ route('course.show', ['id' => $courseId]) }}" class="btn bg-dark text-light" style="float: left;">Course</a>
    <div style="clear: both;"></div>
</div>

<div class="container" style="margin-top: 15px; margin-left: 20px; margin-right: 20px;">
    <div class="content-wrapper">
        <div class="reproductor">
            <img src="{{ asset($currentLesson->video_file_name) }}" style="width: 100%; height: auto; margin-top: 10px;">

            <div class="boton-siguiente">
                @php
                    $nextLessonId = null;
                    $foundCurrentLesson = false;
                @endphp

                @foreach($lessons as $lesson)
                    @if($foundCurrentLesson)
                        @php
                            $nextLessonId = $lesson->id;
                            break;
                        @endphp
                    @endif
        
                    @if($lesson->id == $currentLesson->id)
                        @php
                            $foundCurrentLesson = true;
                        @endphp
                    @endif
                @endforeach

                @if($nextLessonId)
                    <a href="{{ url('/course/' . $courseId . '/lessons/' . $nextLessonId) }}">
                        <img src="{{ asset('app_images/next_button.png') }}" style="width: 15%; height: auto; margin-right: 5px; float: right; margin-top: 10px">
                    </a>
                @endif
            </div>

            <div class="titulo-video" style="margin-top: 10px;">
                <h2 style="font-family: 'Orbitron', sans-serif;">{{ $currentLesson->name }}</h2>
            </div>

            <div class="comments-section" style="margin-top: 20px; padding-bottom: 20px;">
                <div class="comments-header">
                    <h3 style="font-family: 'Orbitron', sans-serif; font-weight: bold;">Comments</h3>
                    @auth
                        @if (Auth::user()->id != $lesson->course->user_id)
                            <a id="show-comment-form" class="add-comment-link" onclick="document.getElementById('add-comment-section').style.display = 'block'">Add Comment</a>
                        @endif
                    @endauth
                </div>
                @if ($currentLesson->comments->count() == 0)
                    <p>There are no comments for this lesson</p>
                @else
                    <div class="comments-list" style="max-height: 300px; overflow-y: auto;">
                        @foreach ($currentLesson->comments as $comment)
                            @if ($comment->parent_id == NULL)
                                <div class="comment">
                                    <div class="comment-header">
                                        <div class="comment-author"><img style="height: 20px; width: 30px; padding-right: 10px;" src="{{ asset($comment->user->image_profile) }}">{{ $comment->user->name }}</div>
                                        <div class="comment-rating">Rating: {{ $comment->rating }}/5</div>
                                    </div>
                                    <div class="comment-content">{{ $comment->content }}</div>
                                    <div class="comment-footer">
                                        <div class="comment-date">{{ $comment->date }}</div>
                                        @auth
                                            @if (Auth::user()->id == $lesson->course->user_id)
                                                <a id="show-comment-form" class="add-comment-link" onclick="document.getElementById('add-comment-section-{{ $comment->id }}').style.display = 'block'">Respond</a>
                                                <form action="{{ route('delete.comment') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $comment->id }}">
                                                    <button type="submit" class="btn-delete-comment" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 14" class="svgIcon bin-top">
                                                            <g clip-path="url(#clip0_35_24)">
                                                                <path fill="black" d="M20.8232 2.62734L19.9948 4.21304C19.8224 4.54309 19.4808 4.75 19.1085 4.75H4.92857C2.20246 4.75 0 6.87266 0 9.5C0 12.1273 2.20246 14.25 4.92857 14.25H64.0714C66.7975 14.25 69 12.1273 69 9.5C69 6.87266 66.7975 4.75 64.0714 4.75H49.8915C49.5192 4.75 49.1776 4.54309 49.0052 4.21305L48.1768 2.62734C47.3451 1.00938 45.6355 0 43.7719 0H25.2281C23.3645 0 21.6549 1.00938 20.8232 2.62734ZM64.0023 20.0648C64.0397 19.4882 63.5822 19 63.0044 19H5.99556C5.4178 19 4.96025 19.4882 4.99766 20.0648L8.19375 69.3203C8.44018 73.0758 11.6746 76 15.5712 76H53.4288C57.3254 76 60.5598 73.0758 60.8062 69.3203L64.0023 20.0648Z"></path>
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_35_24">
                                                                    <rect fill="white" height="14" width="69"></rect>
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 57" class="svgIcon bin-bottom">
                                                            <g clip-path="url(#clip0_35_22)">
                                                                <path fill="black" d="M20.8232 -16.3727L19.9948 -14.787C19.8224 -14.4569 19.4808 -14.25 19.1085 -14.25H4.92857C2.20246 -14.25 0 -12.1273 0 -9.5C0 -6.8727 2.20246 -4.75 4.92857 -4.75H64.0714C66.7975 -4.75 69 -6.8727 69 -9.5C69 -12.1273 66.7975 -14.25 64.0714 -14.25H49.8915C49.5192 -14.25 49.1776 -14.4569 49.0052 -14.787L48.1768 -16.3727C47.3451 -17.9906 45.6355 -19 43.7719 -19H25.2281C23.3645 -19 21.6549 -17.9906 20.8232 -16.3727ZM64.0023 1.0648C64.0397 0.4882 63.5822 0 63.0044 0H5.99556C5.4178 0 4.96025 0.4882 4.99766 1.0648L8.19375 50.3203C8.44018 54.0758 11.6746 57 15.5712 57H53.4288C57.3254 57 60.5598 54.0758 60.8062 50.3203L64.0023 1.0648Z"></path>
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_35_22">
                                                                    <rect fill="white" height="57" width="69"></rect>
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @elseif (Auth::user()->id == $comment->user_id || Auth::user()->role_type == 'ADMIN')
                                                <form action="{{ route('delete.comment') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{ $comment->id }}">
                                                    <button type="submit" class="btn-delete-comment" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 14" class="svgIcon bin-top">
                                                            <g clip-path="url(#clip0_35_24)">
                                                                <path fill="black" d="M20.8232 2.62734L19.9948 4.21304C19.8224 4.54309 19.4808 4.75 19.1085 4.75H4.92857C2.20246 4.75 0 6.87266 0 9.5C0 12.1273 2.20246 14.25 4.92857 14.25H64.0714C66.7975 14.25 69 12.1273 69 9.5C69 6.87266 66.7975 4.75 64.0714 4.75H49.8915C49.5192 4.75 49.1776 4.54309 49.0052 4.21305L48.1768 2.62734C47.3451 1.00938 45.6355 0 43.7719 0H25.2281C23.3645 0 21.6549 1.00938 20.8232 2.62734ZM64.0023 20.0648C64.0397 19.4882 63.5822 19 63.0044 19H5.99556C5.4178 19 4.96025 19.4882 4.99766 20.0648L8.19375 69.3203C8.44018 73.0758 11.6746 76 15.5712 76H53.4288C57.3254 76 60.5598 73.0758 60.8062 69.3203L64.0023 20.0648Z"></path>
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_35_24">
                                                                    <rect fill="white" height="14" width="69"></rect>
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 57" class="svgIcon bin-bottom">
                                                            <g clip-path="url(#clip0_35_22)">
                                                                <path fill="black" d="M20.8232 -16.3727L19.9948 -14.787C19.8224 -14.4569 19.4808 -14.25 19.1085 -14.25H4.92857C2.20246 -14.25 0 -12.1273 0 -9.5C0 -6.8727 2.20246 -4.75 4.92857 -4.75H64.0714C66.7975 -4.75 69 -6.8727 69 -9.5C69 -12.1273 66.7975 -14.25 64.0714 -14.25H49.8915C49.5192 -14.25 49.1776 -14.4569 49.0052 -14.787L48.1768 -16.3727C47.3451 -17.9906 45.6355 -19 43.7719 -19H25.2281C23.3645 -19 21.6549 -17.9906 20.8232 -16.3727ZM64.0023 1.0648C64.0397 0.4882 63.5822 0 63.0044 0H5.99556C5.4178 0 4.96025 0.4882 4.99766 1.0648L8.19375 50.3203C8.44018 54.0758 11.6746 57 15.5712 57H53.4288C57.3254 57 60.5598 54.0758 60.8062 50.3203L64.0023 1.0648Z"></path>
                                                            </g>
                                                            <defs>
                                                                <clipPath id="clip0_35_22">
                                                                    <rect fill="white" height="57" width="69"></rect>
                                                                </clipPath>
                                                            </defs>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endauth
                                    </div>
                                    <div id="add-comment-section-{{ $comment->id }}" class="add-comment-section" style="display: none;">
                                        <form action="{{ route('comments.store') }}" method="POST">
                                            @csrf 
                                            <input type="hidden" name="lesson_id" value="{{ $currentLesson->id }}">
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <div class="form-group">
                                                <textarea name="content" style="padding-bottom: 10px;" id="content" class="form-control" rows="3" placeholder="Write your response here..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button">Send</button>
                                        </form>
                                    </div>
                                    @if ($comment->children != NULL)
                                        <div class="comment-replies">
                                            @foreach ($comment->children as $reply)
                                                <div class="comment reply">
                                                    <div class="comment-header">
                                                        <div class="comment-author"><img style="height: 20px; width: 30px; padding-right: 10px;" src="{{ asset($reply->user->image_profile) }}">{{ $reply->user->name }}</div>
                                                    </div>
                                                    <div class="comment-content">{{ $reply->content }}</div>
                                                    <div class="comment-footer">
                                                        <div class="comment-date">{{ $reply->date }}</div>
                                                        @auth
                                                            @if (Auth::user()->id == $lesson->course->user_id)
                                                                <a id="show-comment-form" class="add-comment-link" onclick="document.getElementById('add-comment-section-{{ $comment->id }}').style.display = 'block'">Respond</a>
                                                                <form action="{{ route('delete.comment') }}" method="POST">
                                                                    @csrf 
                                                                    <input type="hidden" name="id" value="{{ $reply->id }}">
                                                                    <button type="submit" class="btn-delete-comment" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 14" class="svgIcon bin-top">
                                                                            <g clip-path="url(#clip0_35_24)">
                                                                                <path fill="black" d="M20.8232 2.62734L19.9948 4.21304C19.8224 4.54309 19.4808 4.75 19.1085 4.75H4.92857C2.20246 4.75 0 6.87266 0 9.5C0 12.1273 2.20246 14.25 4.92857 14.25H64.0714C66.7975 14.25 69 12.1273 69 9.5C69 6.87266 66.7975 4.75 64.0714 4.75H49.8915C49.5192 4.75 49.1776 4.54309 49.0052 4.21305L48.1768 2.62734C47.3451 1.00938 45.6355 0 43.7719 0H25.2281C23.3645 0 21.6549 1.00938 20.8232 2.62734ZM64.0023 20.0648C64.0397 19.4882 63.5822 19 63.0044 19H5.99556C5.4178 19 4.96025 19.4882 4.99766 20.0648L8.19375 69.3203C8.44018 73.0758 11.6746 76 15.5712 76H53.4288C57.3254 76 60.5598 73.0758 60.8062 69.3203L64.0023 20.0648Z"></path>
                                                                            </g>
                                                                            <defs>
                                                                                <clipPath id="clip0_35_24">
                                                                                    <rect fill="white" height="14" width="69"></rect>
                                                                                </clipPath>
                                                                            </defs>
                                                                        </svg>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 57" class="svgIcon bin-bottom">
                                                                            <g clip-path="url(#clip0_35_22)">
                                                                                <path fill="black" d="M20.8232 -16.3727L19.9948 -14.787C19.8224 -14.4569 19.4808 -14.25 19.1085 -14.25H4.92857C2.20246 -14.25 0 -12.1273 0 -9.5C0 -6.8727 2.20246 -4.75 4.92857 -4.75H64.0714C66.7975 -4.75 69 -6.8727 69 -9.5C69 -12.1273 66.7975 -14.25 64.0714 -14.25H49.8915C49.5192 -14.25 49.1776 -14.4569 49.0052 -14.787L48.1768 -16.3727C47.3451 -17.9906 45.6355 -19 43.7719 -19H25.2281C23.3645 -19 21.6549 -17.9906 20.8232 -16.3727ZM64.0023 1.0648C64.0397 0.4882 63.5822 0 63.0044 0H5.99556C5.4178 0 4.96025 0.4882 4.99766 1.0648L8.19375 50.3203C8.44018 54.0758 11.6746 57 15.5712 57H53.4288C57.3254 57 60.5598 54.0758 60.8062 50.3203L64.0023 1.0648Z"></path>
                                                                            </g>
                                                                            <defs>
                                                                                <clipPath id="clip0_35_22">
                                                                                    <rect fill="white" height="57" width="69"></rect>
                                                                                </clipPath>
                                                                            </defs>
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            @elseif (Auth::user()->id == $comment->user_id || Auth::user()->role_type == 'ADMIN')
                                                                <form action="{{ route('delete.comment') }}" method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="id" value="{{ $reply->id }}">
                                                                    <button type="submit" class="btn-delete-comment" onclick="return confirm('Are you sure you want to delete this comment?')">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 14" class="svgIcon bin-top">
                                                                            <g clip-path="url(#clip0_35_24)">
                                                                                <path fill="black" d="M20.8232 2.62734L19.9948 4.21304C19.8224 4.54309 19.4808 4.75 19.1085 4.75H4.92857C2.20246 4.75 0 6.87266 0 9.5C0 12.1273 2.20246 14.25 4.92857 14.25H64.0714C66.7975 14.25 69 12.1273 69 9.5C69 6.87266 66.7975 4.75 64.0714 4.75H49.8915C49.5192 4.75 49.1776 4.54309 49.0052 4.21305L48.1768 2.62734C47.3451 1.00938 45.6355 0 43.7719 0H25.2281C23.3645 0 21.6549 1.00938 20.8232 2.62734ZM64.0023 20.0648C64.0397 19.4882 63.5822 19 63.0044 19H5.99556C5.4178 19 4.96025 19.4882 4.99766 20.0648L8.19375 69.3203C8.44018 73.0758 11.6746 76 15.5712 76H53.4288C57.3254 76 60.5598 73.0758 60.8062 69.3203L64.0023 20.0648Z"></path>
                                                                            </g>
                                                                            <defs>
                                                                                <clipPath id="clip0_35_24">
                                                                                    <rect fill="white" height="14" width="69"></rect>
                                                                                </clipPath>
                                                                            </defs>
                                                                        </svg>
                                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 69 57" class="svgIcon bin-bottom">
                                                                            <g clip-path="url(#clip0_35_22)">
                                                                                <path fill="black" d="M20.8232 -16.3727L19.9948 -14.787C19.8224 -14.4569 19.4808 -14.25 19.1085 -14.25H4.92857C2.20246 -14.25 0 -12.1273 0 -9.5C0 -6.8727 2.20246 -4.75 4.92857 -4.75H64.0714C66.7975 -4.75 69 -6.8727 69 -9.5C69 -12.1273 66.7975 -14.25 64.0714 -14.25H49.8915C49.5192 -14.25 49.1776 -14.4569 49.0052 -14.787L48.1768 -16.3727C47.3451 -17.9906 45.6355 -19 43.7719 -19H25.2281C23.3645 -19 21.6549 -17.9906 20.8232 -16.3727ZM64.0023 1.0648C64.0397 0.4882 63.5822 0 63.0044 0H5.99556C5.4178 0 4.96025 0.4882 4.99766 1.0648L8.19375 50.3203C8.44018 54.0758 11.6746 57 15.5712 57H53.4288C57.3254 57 60.5598 54.0758 60.8062 50.3203L64.0023 1.0648Z"></path>
                                                                            </g>
                                                                            <defs>
                                                                                <clipPath id="clip0_35_22">
                                                                                    <rect fill="white" height="57" width="69"></rect>
                                                                                </clipPath>
                                                                            </defs>
                                                                        </svg>
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endauth
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
            @auth
                @if (Auth::user()->id != $lesson->course->user_id)
                    <div id="add-comment-section" class="add-comment-section">
                        <h3>Add a comment</h3>
                        <form action="{{ route('comments.store') }}" method="POST">
                            @csrf 
                            <input type="hidden" name="lesson_id" value="{{ $currentLesson->id }}">
                            <div class="form-group">
                                <textarea name="content" id="content" class="form-control" rows="3" placeholder="Write your comment here..." required></textarea>
                            </div>
                            <div class="form-group flex" style="padding-bottom: 50px;">
                                <button type="submit" class="btn btn-outline-success btn-sm my-2 my-sm-0 custom-search-button">Send</button>
                                <div class="rating">
                                    <input value="5" name="rate" id="star5" type="radio">
                                    <label title="5 estrellas" for="star5"></label>
                                    <input value="4" name="rate" id="star4" type="radio">
                                    <label title="4 estrellas" for="star4"></label>
                                    <input value="3" name="rate" id="star3" type="radio">
                                    <label title="3 estrellas" for="star3"></label>
                                    <input value="2" name="rate" id="star2" type="radio">
                                    <label title="2 estrellas" for="star2"></label>
                                    <input value="1" name="rate" id="star1" type="radio">
                                    <label title="1 estrella" for="star1"></label>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            @endauth
        </div>
        <div class="lista-lecciones" style="width: 30%; padding-left: 20px; overflow-y: auto; margin-left: 40px;">
        <h3 style="font-family: 'Orbitron', sans-serif; font-weight: bold;">Course's Lessons</h3>
        <ul style="list-style-type: none; padding: 0;">
            @foreach($lessons as $lesson)
            <li style="margin-bottom: 20px; display: flex; align-items: flex-start;">
                <a href="{{ url('/course/' . $courseId . '/lessons/' . $lesson->id) }}" class="lesson-link" style="display: flex; align-items: center; text-decoration: none; color: inherit;">
                    <img src="{{ asset($lesson->video_file_name) }}" style="width: 175px; height: 100px; margin-right: 10px;">
                    <span style="flex: 1;">{{ $lesson->name }}</span>
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    </div>
</div>

@endsection

@push('scripts')
@endpush
