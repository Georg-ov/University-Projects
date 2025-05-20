<?php

use App\Http\Controllers\CoursesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\BackupController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\RestoreController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/error', function () {
    return view('error');
})->name('error.page');

Route::middleware(['web', 'check.db.tables'])->group(function () {
    
    Route::get('/', [CoursesController::class, 'showMainPage'])->name('mainpage');

    Route::get('/mainpage', [CoursesController::class, 'showMainPage'])->name('mainpage');

    Route::middleware(['auth', 'admin'])->group(function () {

        Route::get('/admin', function () {
            return view('admin/main');
        })->name('admin.panel');

        Route::get('admin/courses', [CoursesController::class, 'getAllCourses'])->name('courses.show');

        Route::get('admin/courses/filter', [CoursesController::class, 'filterCourses'])->name('courses.filter');

        Route::get('admin/courses/order', [CoursesController::class, 'orderCourses'])->name('courses.order');

        Route::get('/admin/users', [UserController::class, 'listAll'])->name('show.users');

        Route::get('/admin/users/order', [UserController::class, 'orderUser'])->name('order.users');

        Route::post('/admin/users/delete', [UserController::class, 'deleteUser'])->name('delete.user');

        Route::get('/admin/users/create', [UserController::class, 'createUser']);

        Route::post('/admin/users/create/save', [UserController::class, 'saveUser'])->name('save.user');

        Route::get('/admin/users/edit/{id}', [UserController::class, 'editUser'])->name('edit.user');

        Route::put('/admin/users/{id}/update', [UserController::class, 'updateUser'])->name('update.user');

        Route::get('/admin/users/search', [UserController::class, 'searchUsers'])->name('search.users');

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Route::get('/admin/categories', [CategoryController::class, 'listAll'])->name('show.categories');

        Route::get('/admin/categories/order', [CategoryController::class, 'orderCategories'])->name('order.categories');

        Route::post('/admin/categories/delete', [CategoryController::class, 'deleteCategory'])->name('delete.category');

        Route::get('/admin/categories/create', [CategoryController::class, 'createCategory']);

        Route::post('/admin/categories/create/save', [CategoryController::class, 'saveCategory'])->name('save.category');

        Route::get('/admin/categories/edit/{id}', [CategoryController::class, 'editCategory'])->name('edit.category');

        Route::put('/admin/categories/{id}/update', [CategoryController::class, 'updateCategory'])->name('update.category');

        Route::get('/admin/categories/search', [CategoryController::class, 'searchCategories'])->name('search.categories');

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Route::get('admin/addresses', [AddressController::class, 'listAll'])->name('list.addresses');

        Route::get('/admin/addresses/order', [AddressController::class, 'orderAddresses'])->name('order.addresses');

        Route::post('admin/addresses/delete', [AddressController::class, 'deleteAddress'])->name('delete.address');

        Route::get('admin/addresses/create/{id}', [AddressController::class, 'createAddress'])->name('address.create');

        Route::post('admin/addresses/create/save', [AddressController::class, 'saveAddress'])->name('save.address');

        Route::get('admin/addresses/edit/{id}', [AddressController::class, 'editAddress'])->name('edit.addresses');

        Route::put('admin/addresses/{id}/update', [AddressController::class, 'updateAddress'])->name('update.address');

        Route::get('admin/addresses/search', [AddressController::class, 'searchAddresses'])->name('search.addresses');

        /////////////////////////////////////////////////////////////////////////////////////////////////////////////

        Route::get('/admin/lessons', [LessonController::class, 'listAllLessons'])->name('show.lessons');

        Route::get('/admin/lessons/order', [LessonController::class, 'orderLesson'])->name('order.lessons');

        Route::get('/admin/lessons/search', [LessonController::class, 'searchLessons'])->name('search.lessons');
    });

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Auth::routes();

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

    Route::view('/contact-us', 'contact_us')->name('contact-us');

    Route::view('/terms-of-service', 'terms_of_service')->name('terms-of.service');

    Route::view('/about-us', 'about_us')->name('about-us');

    Route::get('/mainpage', [CoursesController::class, 'showMainPage'])->name('mainpage');

    Route::get('/course', [CoursesController::class, 'getAvailableCourses'])->name('availableCourses');

    Route::get('course/new', [CoursesController::class, 'createCourse'])->name('course.create.show')->middleware('auth');

    Route::post('course/new', [CoursesController::class, 'saveCourse'])->name('course.create.save')->middleware('auth');

    Route::get('course/{id}', [CoursesController::class, 'getCourse'])->name('course.show')->middleware('auth');

    Route::get('course/update/{id}', [CoursesController::class, 'editCourse'])->name('course.update.show')->middleware('auth');

    Route::put('course/update/{id}', [CoursesController::class, 'updateCourse'])->name('course.update.save')->middleware('auth');

    Route::delete('course/{id}', [CoursesController::class, 'deleteCourse'])->name('course.delete')->middleware('auth');

    Route::get('/courses/order', [CoursesController::class, 'filterAndOrderAvailableCourses'])
        ->name('availableCourses.filterAndOrder');

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Route::post('/lessons/delete', [LessonController::class, 'deleteLesson'])->name('delete.lesson');

    Route::get('/lessons/create', [LessonController::class, 'createLesson'])->name('createLesson')->middleware('auth');

    Route::post('/lessons/create', [LessonController::class, 'saveLesson'])->name('createLesson')->middleware('auth');

    Route::post('/lessons/create/save', [LessonController::class, 'saveLesson'])->name('save.lesson');

    Route::get('/lessons/update/{id}', [LessonController::class, 'editLesson'])->name('edit.lesson');

    Route::put('lessons/update/{id}', [LessonController::class, 'updateLesson'])->name('lesson.update')->middleware('auth');



    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    Route::get('/profile', [UserController::class, 'showProfile'])->name('profile')->middleware('auth');

    Route::get('/profile/edit/{id}', [UserController::class, 'editProfile'])->name('edit.profile');

    Route::put('/profile/{id}/update', [UserController::class, 'updateProfile'])->name('update.profile');

Route::get('/profile/teacher/{id}', [UserController::class, 'showTeacherProfile'])->name('show.profile')->middleware('auth');

    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    Route::middleware(['auth', 'check.subscription'])->group(function () {
        Route::get('/subscribe', [SubscriptionController::class, 'showSubscribeForm'])->name('subscribe');

        Route::post('/subscribe', [SubscriptionController::class, 'processSubscription'])->name('subscribe.process');
    });

    Route::get('/course/{courseId}/lessons/{lessonId}', [CoursesController::class, 'showLesson'])->name('lessons');

    Route::post('/comments', [LessonController::class, 'storeComment'])->name('comments.store');

    Route::post('/comments/delete', [LessonController::class, 'deleteComment'])->name('delete.comment');

    Route::get('/teacher/my-courses', [CoursesController::class, 'showTeacherCourses'])->name('teacher.my-courses');

    Route::post('/teacher/lesson/delete', [LessonController::class, 'deleteLessonTeacher'])->name('delete.teacherlesson');

});

Route::middleware(['web'])->group(function () {

    Route::get('/backup', [BackupController::class, 'createBackup'])->name('create.backup');

    Route::get('/restore', [RestoreController::class, 'showRestoreForm'])->name('restore.form');

    Route::post('/restore', [RestoreController::class, 'restoreBackup'])->name('restore.backup');

});
