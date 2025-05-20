<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use App\Models\Category;

class CategoryController extends Controller
{
    public function listAll()
    {
        $categories = Category::paginate(5);
        return view('admin/list_categories', ['categories' => $categories]);
    }

    public function orderCategories(Request $request)
    {
        $sort = $request->input('sort', 'id');

        $categories = Category::orderBy($sort)->paginate(5);

        return view('admin/list_categories', compact('categories'));
    }

    public function deleteCategory(Request $request)
    {
        $name = $request->name;

        $category = Category::where('name', $name)->first();

        if ($category) {
            $category->delete();
            return redirect()->back()->with('success', 'The category has been deleted succesfully.');
        } else {
            return redirect()->back()->with('error', 'The category was not found.');
        }
    }

    public function createCategory(Request $request)
    {
        return view('admin/create_categories');
    }

    public function saveCategory(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'required|string|max:255',
        ]);
    
        $existingCategory = Category::where('name', $request->input('name'))->first();
        if ($existingCategory) {
            return redirect()->back()->with('error', 'The category name already exists!');
        }
    
        $category = new Category();
        $category->name = $request->input('name');
        $category->description = $request->input('description');
        $category->image_file_name = 'app_images/default.png';
    
        $category->save();
    
        return redirect()->route('show.categories')->with('success', 'The category has been successfully created!');
    }

    public function editCategory($id)
    {
        $category = Category::findOrFail($id);
        return view('admin/update_categories', ['category' => $category]);
    }

    public function updateCategory(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,'.$id,
            'description' => 'required|string|max:255',
        ]);
    
        $category = Category::findOrFail($id);
    
        $category->name = $request->input('name');
        $category->description = $request->input('description');
    
        if ($request->has('toggle_delete') && $request->input('toggle_delete')) {
            $category->image_file_name = 'app_images/default.png';
        }
    
        if ($request->hasFile('new_image')) {
            $image = $request->file('new_image');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('category_images', $filename);
            $category->image_file_name = 'category_images/' . $filename;
        }
    
        $category->save();
    
        return redirect()->route('show.categories')->with('success', 'The category has been successfully updated!');
    }    
    
    public function searchCategories(Request $request)
    {
        $search = $request->input('search');

        $categories = Category::where('name', 'LIKE', "%{$search}%")->paginate(5);

        return view('admin/list_categories', ['categories' => $categories]);
    }
}