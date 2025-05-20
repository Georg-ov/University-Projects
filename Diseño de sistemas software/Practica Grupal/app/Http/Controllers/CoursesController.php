<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\CourseService;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CoursesController extends Controller
{
    protected $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    public function getAllCourses()
    {
        $courses = $this->courseService->getAllCourses();
        $categories = $this->courseService->getAllCategories();

        return view('admin/courses/courses', [
            'courses' => $courses,
            'categories' => $categories,
        ]);
    }

    public function getAvailableCourses(Request $request)
    {
        $courses = $this->courseService->getAvailableCourses($request);
        $categories = $this->courseService->getAllCategories();

        return view('courses.courses', [
            'courses' => $courses,
            'categories' => $categories,
            'sort' => $request->input('sort', 'id'),
            'filters' => $request->only('name', 'category_id', 'is_free'),
        ]);
    }

    public function filterAndOrderAvailableCourses(Request $request)
    {
        $courses = $this->courseService->filterAndOrderAvailableCourses($request);
        $categories = $this->courseService->getAllCategories();

        return view('courses.courses', [
            'courses' => $courses,
            'categories' => $categories,
            'sort' => $request->input('sort', 'id'),
            'filters' => $request->only('name', 'category_id', 'is_free'),
        ]);
    }

    public function getCourse($id)
    {
        $course = $this->courseService->getCourse($id);

        if (!$course) {
            return response()->view('errors.error', [
                'title' => 'Error 404',
                'message' => 'El curso que estás buscando no existe.'
            ], Response::HTTP_NOT_FOUND);
        }

        $user = Auth::user();

        if (!$course->visibility && (!$user || (!$user->isAdmin() && !($course->isOwner($user->id))))) {
            return response()->view('errors.error', [
                'title' => 'Error 404',
                'message' => 'The course you\'re looking for cannot be found.'
            ], Response::HTTP_NOT_FOUND);
        }

        return view('courses.course_details', ['user' => $user, 'course' => $course]);
    }

    public function createCourse()
    {
        $user = Auth::user();

        if (!$user || (!$user->isAdmin() && !$user->isTeacher())) {
            return response()->view('errors.error', [
                'title' => 'Error 403',
                'message' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }

        $categories = $this->courseService->getAllCategories();

        return view('courses.create_course', ['categories' => $categories]);
    }

    public function saveCourse(Request $request)
    {
        $course = $this->courseService->createCourse($request);

        return redirect()->route('course.show', ['id' => $course->id])
            ->with('success', 'The course has been created');
    }

    public function editCourse($id)
    {
        $course = $this->courseService->getCourse($id);
        $categories = $this->courseService->getAllCategories();

        return view('courses.course_update', ['course' => $course, 'categories' => $categories]);
    }

    public function updateCourse(Request $request, $id)
    {
        $course = $this->courseService->updateCourse($request, $id);

        return redirect()->route('course.show', ['id' => $course->id])
            ->with('success', 'The course has been updated.');
    }

    public function deleteCourse($id)
    {
        $course = $this->courseService->deleteCourse($id);

        return redirect()->route('show.profile', ['id' => $course->user->id])
            ->with('success', 'Course deleted successfully.');
    }

    public function showMainPage()
    {
        $popularCourses = Course::orderBy('id', 'asc')
            ->take(3)
            ->get();

        return view('mainpage', ['popularCourses' => $popularCourses]);
    }

    public function showLesson($courseId, $lessonId)
    {
        $course = Course::findOrFail($courseId);
        $user = Auth::user();

        if (!Auth::user())
        {
            $title = "ERROR";
            $message = "You need to be registered.";
            return view('errors/error', ['title' => $title, 'message' => $message]);
        }

        if ($course->is_free === 0 && $user->role_type === 'STUDENT' && $user->subscription_type === 'FREEMIUM')
        {
            $title = "ERROR";
            $message = "This course is for PREMIUM users only.";
            return view('errors/error', ['title' => $title, 'message' => $message]);
        }

        $lesson = Lesson::findOrFail($lessonId);

        $lessons = $course->lessons()->get();

        if (!$lesson) {
            return redirect()->route('mainpage')->with('error', 'No lesson available for this course.');
        }

        return view('lesson', ['currentLesson' => $lesson, 'lessons' => $lessons, 'courseId' => $courseId]);
    }

    public function showTeacherCourses()
    {
        $user = Auth::user();

        if (!Auth::user())
        {
            $title = "ERROR";
            $message = "You need to be registered.";
            return view('errors/error', ['title' => $title, 'message' => $message]);
        }

        if ($user->role_type !== 'TEACHER')
        {
            $title = "ERROR";
            $message = "You need to be a TEACHER to see your courses.";
            return view('errors/error', ['title' => $title, 'message' => $message]);
        }

        $categories = $this->courseService->getAllCategories();

        return view('my-courses', ['user' => $user, 'categories' => $categories,]);
    }
}
