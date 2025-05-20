<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Artisan;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    // Mostrar todos los usuarios -> administrador
    public function listAll()
    {
        $users = User::paginate(5);
        return view('admin/list_users', ['users' => $users]);
    }

    // Ordenar los usuarios -> por id como default -> administrador
    public function orderUser(Request $request)
    {
        $sort = $request->input('sort', 'id');

        $users = User::orderBy($sort)->paginate(5);

        return view('admin/list_users', compact('users'));
    }

    // Eliminar usuario -> administrador
    public function deleteUser(Request $request)
    {
        $username = $request->username;

        $user = User::where('username', $username)->first();

        if ($user) 
        {
            $user->delete();
            return redirect()->back()->with('success', 'The user has been deleted successfully.');
        } 
        else 
        {
            return redirect()->back()->with('error', 'The user was not found.');
        }
    }

    // Crear usuario -> administrador
    public function createUser(Request $request)
    {
        return view('admin/create_users');
    }

    public function saveUser(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'birthday' => ['required', 'date', 'before_or_equal:' . now()->subYears(18)->format('Y-m-d')],
            'role_type' => 'required|string|in:STUDENT,TEACHER,ADMIN',
            'subscription_type' => [
                'required',
                'string',
                Rule::in(['FREEMIUM', 'PREMIUM']),
                function ($attribute, $value, $fail) use ($request) {
                    if (in_array($request->input('role_type'), ['TEACHER', 'ADMIN']) && $value !== 'PREMIUM') {
                        $fail('If the role type is Teacher or Admin, the subscription type must be PREMIUM.');
                    }
                },
            ],
        ]);

        if ($request->has('image_profile') && !empty($request->image_profile)) {
            $image = $request->file('image_profile');
            $filename = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('users_images', $filename);
            $validatedData['image_profile'] = 'users_images/' . $filename;
        }

        $user = $this->userService->createUser($validatedData);

        $redirectUrl = $validatedData['subscription_type'] === 'PREMIUM' ? 
            route('address.create', ['id' => $user->id]) : 
            route('show.users'); 

        return redirect($redirectUrl)->with('success', 'The user was successfully created!');
    }

    // Editar usuario -> administrador
    public function editUser($id)
    {
        $user = User::findOrFail($id);
        return view('admin/update_users', ['user' => $user]);
    }

    // Actualizar datos del usuario -> administrador
public function updateUser(Request $request, $id)
{
    $validatedData = $request->validate([
        'name' => 'required',
        'username' => 'required|unique:users,username,' . $id,
        'email' => 'required|email|unique:users,email,' . $id,
        'password' => 'nullable|string|min:8|confirmed',
        'about_me' => 'nullable|string',
        'role_type' => 'string|in:STUDENT,TEACHER,ADMIN',
        'subscription_type' => [
            'string',
            Rule::in(['FREEMIUM', 'PREMIUM']),
            function ($attribute, $value, $fail) use ($request) {
                if (in_array($request->input('role_type'), ['TEACHER', 'ADMIN']) && $value !== 'PREMIUM') {
                    $fail('If the role type is Teacher or Admin, the subscription type must be PREMIUM.');
                }
            },
        ],
    ]);

    $imagePath = null;

    if ($request->has('image_profile') && !empty($request->image_profile)) {
        $image = $request->file('image_profile');
        $filename = uniqid() . '.' . $image->getClientOriginalExtension();
        $image->move('users_images', $filename);
        $validatedData['image_profile'] = 'users_images/' . $filename;
    }

    if ($request->has('address')) {
        $validatedData['address'] = $request->validate([
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'province' => 'required|string|max:255',
            'postal_code' => 'required|numeric|max:999999',
            'country' => 'required|string|max:255',
        ]);
    }

    // Asegúrate de que el campo subscription_type esté definido en $validatedData
    $validatedData['subscription_type'] = $validatedData['subscription_type'] ?? User::findOrFail($id)->subscription_type;

    $user = $this->userService->updateUser($id, $validatedData);

    $redirectUrl = $validatedData['subscription_type'] === 'PREMIUM' ? 
        route('address.create', ['id' => $user->id]) : 
        route('show.users'); 

    return redirect($redirectUrl)->with('success', 'The user was successfully updated!');
}

    
    // Barra de búsqueda de usuarios -> administrador
    public function searchUsers(Request $request)
    {
        $search = $request->input('search');

        $users = User::where('name', 'LIKE', "%{$search}%")
                    ->orWhere('username', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->paginate(5);

        return view('admin/list_users', ['users' => $users]);
    }

    // Mostrar perfil del usuario loggeado
    public function showProfile()
    {
        $user = Auth::user();
        return view('user_profile', ['user' => $user]);
    }

    public function editProfile($id)
    {
        $user = User::findOrFail($id);
        return view('edit_profile', ['user' => $user]);
    }

    public function updateProfile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'about_me' => 'nullable|string',
        ]);

        if ($request->has('image_profile') && !empty($request->image_profile)) {
            $image = $request->file('image_profile');

            $filename = uniqid() . '.' . $image->getClientOriginalExtension();

            $image->move('users_images', $filename);

            $imagePath = 'users_images/' . $filename;
        }
    
        $user = User::findOrFail($id);

        if ($user->subscription_type === 'PREMIUM' && $user->address != NULL)
        {
            $request->validate([
                'street' => 'required|string|max:255',
                'city' => 'required|string|max:255',
                'province' => 'required|string|max:255',
                'postal_code' => 'required|numeric|max:999999',
                'country' => 'required|string|max:255',
            ]);

            $address = $user->address ?: new Address;

            $address->street = $request->street;
            $address->city = $request->city;
            $address->province = $request->province;
            $address->postal_code = $request->postal_code;
            $address->country = $request->country;

            $address->user()->associate($user);
            $address->save();
        }
    
        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->about_me = $request->about_me;
        if ($request->image_profile == NULL)
        {
            $user->image_profile = $user->image_profile;
        }
        else
        {
            $user->image_profile = $imagePath;
        }
    
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }
    
        $user->save();
    
        return redirect('/profile')->with('success', 'The user has been successfully updated!');
    }

    public function showTeacherProfile($id)
    {
        $user = User::findorFail($id);
        $title = "ERROR";
        $message = "This user isn't a teacher";

        if ($user->role_type !== 'TEACHER')
        {
            return view('errors/error', ['title' => $title, 'message' => $message]);
        }

        $user = User::with('impartedCourses')->findOrFail($id);
        return view('teacher_profile', ['user' => $user]);
    }
}