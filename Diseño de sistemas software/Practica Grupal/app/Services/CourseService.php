<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CourseService
{
    public function getAllCourses()
    {
        return Course::paginate(5);
    }

    public function getAllCategories()
    {
        return Category::all();
    }

    public function getAvailableCourses(Request $request)
    {
        $sort = $request->input('sort', 'id');

        $query = Course::where('visibility', true);

        if ($sort === 'name_asc') {
            $query->orderBy('name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('name', 'desc');
        } elseif ($sort === 'average_rating') {
            $query->leftJoin('lessons', 'courses.id', '=', 'lessons.course_id')
                ->leftJoin('comments', 'lessons.id', '=', 'comments.lesson_id')
                ->select('courses.*', DB::raw('AVG(comments.rating) as average_rating'))
                ->groupBy('courses.id')
                ->orderBy('average_rating', 'desc');
        } else {
            $query->orderBy('id', 'asc');
        }

        return $query->get();
    }

    public function applyFilters(Request $request)
    {
        $category = $request->input('category');
        $name = $request->input('name');
        $is_free = $request->input('is_free');

        $query = Course::where('visibility', true);

        if ($category) {
            $query->where('category_id', $category);
        }

        if ($name) {
            $query->where('courses.name', 'like', '%' . $name . '%');
        }

        if ($is_free != null) {
            $query->where('is_free', $is_free);
        }

        return $query;
    }

    public function filterAndOrderAvailableCourses(Request $request)
    {
        $query = $this->applyFilters($request);
        $sort = $request->input('sort', 'id');

        if ($sort === 'name_asc') {
            $query->orderBy('courses.name', 'asc');
        } elseif ($sort === 'name_desc') {
            $query->orderBy('courses.name', 'desc');
        } elseif ($sort === 'average_rating') {
            $query->leftJoin('lessons', 'courses.id', '=', 'lessons.course_id')
                ->leftJoin('comments', 'lessons.id', '=', 'comments.lesson_id')
                ->select('courses.*', DB::raw('AVG(comments.rating) as average_rating'))
                ->groupBy('courses.id')
                ->orderBy('average_rating', 'desc');
        } else {
            $query->orderBy('courses.id', 'asc');
        }

        return $query->get();
    }

    public function getCourse($id)
    {
        return Course::find($id);
    }

    public function createCourse(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();

            // Authorization check: Only teachers can create courses
            if (!$user->isTeacher() && !$user->isAdmin()) {
                throw new \Exception('Unauthorized access. Only teachers can create courses.');
            }
            
            $validatedData = $request->validate([
                'name' => 'required',
                'description' => 'required',
                'is_free' => 'required',
                'image_file_name' => 'required',
                'visibility' => 'required',
                'category_id' => 'required',
            ]);

            $image = $request->file('image_file_name');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('course_images', $filename);
            $imagePath = 'course_images/' . $filename;

            date_default_timezone_set('Europe/Madrid');

            $course = Course::create([
                'name' => $validatedData['name'],
                'description' => $validatedData['description'],
                'image_file_name' => $imagePath,
                'is_free' => $validatedData['is_free'],
                'visibility' => $validatedData['visibility'],
                'publish_date' => date('Y-m-d H:i:s'),
                'user_id' => $user->id,
                'category_id' => $validatedData['category_id'],
            ]);

            DB::commit();
            return $course;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create course', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateCourse(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $course = Course::findOrFail($id);
            $user = Auth::user();

            // Authorization check: Only the owner or an admin can update the course
            if (!$user->isAdmin() && !$course->isOwner($user->id)) {
                throw new \Exception('Unauthorized access. Only the owner or an admin can update this course.');
            }

            if ($request->has('name') && !empty($request->name)) {
                $course->name = $request->name;
            }

            if ($request->has('description') && !empty($request->description)) {
                $course->description = $request->description;
            }

            if ($request->has('image') && !empty($request->image)) {
                $image = $request->file('image');
                $filename = uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move('course_images', $filename);
                $imagePath = 'course_images/' . $filename;
                $course->image_file_name = $imagePath;
            }

            if ($request->has('visibility')) {
                $course->visibility = $request->visibility;
            }

            if ($request->has('is_free')) {
                $course->is_free = $request->is_free;
            }

            if ($request->has('category_id') && !empty($request->category_id)) {
                $course->category_id = $request->category_id;
            }

            $course->save();

            DB::commit();
            return $course;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update course', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteCourse($id)
    {
        DB::beginTransaction();
        try {
            $course = Course::find($id);

            if (!$course) {
                throw new \Exception('Course not found');
            }

            $user = Auth::user();

            // Check if the user is an admin or the owner of the course
            if (!$user->isAdmin() && !$course->isOwner($user->id)) {
                throw new \Exception('Unauthorized action');
            }

            $course->delete();

            DB::commit();
            return $course;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete course', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
}
