<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Lesson;
use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;

class LessonController extends Controller
{
    
    public function listAllLessons()
    {
        $lessons = Lesson::paginate(5);
        return view('admin/list_lessons', ['lessons' => $lessons]);
    }

    public function orderLesson(Request $request)
    {
        $sort = $request->input('sort', 'id');

        $lessons = Lesson::orderBy($sort)->paginate(5);

        return view('admin/list_lessons', compact('lessons'));
    }

    public function deleteLesson(Request $request)
    {
        $name = $request->name;

        $lesson = Lesson::where('name', $name)->first();

        if ($lesson) {
            $lesson->delete();
            return redirect()->back()->with('success', 'The lesson has been deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'The lesson was not found.');
        }
    }

    public function createLesson()
    {
        $user = Auth::user();
        if ($user->role_type == 'STUDENT')
        {
            $title = "ERROR";
            $message = "You need to be a TEACHER to create a lesson.";
            return view('errors/error', ['title' => $title, 'message' => $message]);
        }

        $courses = Course::all();
        $user = Auth::user();
        return view('admin/create_lessons', compact('courses', 'user'));
    }

    public function saveLesson(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'description' => 'required',
            'video_file_name' => 'required',
            'course_id' => 'required'
        ]);

        $video = $request->file('video_file_name');
        $filename = uniqid() . '.' . $video->getClientOriginalExtension();
        $video->move('lesson_images', $filename);
        $imagePath = 'lesson_images/' . $filename;

        $lesson = Lesson::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'video_file_name' => $imagePath,
            'course_id' => $validatedData['course_id'],
        ]);

        $lesson->save();

        return redirect()->route('show.lessons')->with('success', 'The lesson has been successfully created!');
    }

    public function editLesson($id)
    {
        $lesson = Lesson::findOrFail($id);
        $courses = Course::all();
        return view('admin/update_lessons', ['lesson' => $lesson], ['courses' => $courses]);
    }

    public function updateLesson(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'course_id' => 'required'
        ]);

        $lesson = Lesson::findOrFail($id);

        if($request->hasFile('video_file_name')){
            $video = $request->file('video_file_name');
            $filename = uniqid() . '.' . $video->getClientOriginalExtension();
            $video->move('lesson_images', $filename);
            $imagePath = 'lesson_images/' . $filename;
        } else {
            $imagePath = $lesson->video_file_name;
        }
        

        $lesson->update([
            'name' => $request->name,
            'description' => $request->description,
            'video_file_name' => $imagePath,
            'course_id' => $request->course_id
        ]);

        $lesson->save();

        return redirect()->route('show.lessons')->with('success', 'The lesson has been successfully updated!');
    }
    
    public function searchLessons(Request $request)
    {
        $search = $request->input('search');

        $lessons = Lesson::where('name', 'LIKE', "%{$search}%")
                    ->orWhere('video_file_name', 'LIKE', "%{$search}%")
                    ->paginate(10);

        return view('admin/list_lessons', ['lessons' => $lessons]);
    }

    public function storeComment(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required|exists:lessons,id',
            'content' => 'string',
            'rate' => 'integer|min:1|max:5',
            'parent_id' => 'nullable|exists:comments,id'
        ]);
    
        $comment = new Comment;
        $comment->content = $request->content;
        if ($request->rate == NULL)
        {
            $comment->rating = 0;
        }
        else
        {
            $comment->rating = $request->rate;
        }
        $comment->user_id = auth()->id();
        $comment->lesson_id = $request->lesson_id;
        $comment->date = now();
        $comment->parent_id = $request->parent_id;
        $comment->save();
    
        return redirect()->back()->with('success', 'Comment created!');
    }

    public function deleteComment(Request $request)
    {
        $id = $request->id;

        $comment = Comment::where('id', $id)->first();

        if ($comment) 
        {
            $comment->delete();
            return redirect()->back()->with('success', 'Comment successfully deleted!');
        } 
        else 
        {
            return redirect()->back()->with('error', 'Comment not found.');
        }
    }

    public function deleteLessonTeacher(Request $request)
    {
        $id = $request->id;

        $lesson = Lesson::where('id', $id)->first();

        if ($lesson) 
        {
            $lesson->delete();
            return redirect()->back()->with('success', 'Lesson successfully deleted!');
        } 
        else 
        {
            return redirect()->back()->with('error', 'Lesson not found.');
        }
    }
}