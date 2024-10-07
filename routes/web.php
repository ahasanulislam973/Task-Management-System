<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminTaskController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [HomeController::class, 'index']);
Route::get('/admin/login', [AdminUserController::class, 'showLogin'])->name('admin/login');



Route::post('/register', [UserController::class, 'register'])->name('register');

// Show the login form
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login form submission
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protect user routes with middleware

// User routes
// Route::group(['middleware' => ['auth', 'role:user']], function () {
//     // Regular user dashboard route
//     Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
//     Route::get('/check-login-status', [LoginController::class, 'checkLoginStatus'])->name('checkLoginStatus');
// });

// // Admin routes (make sure to rename conflicting '/user' route)
// Route::group(['middleware' => ['auth', 'role:admin']], function () {
//     Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');

//     // Renaming this to /admin/users to avoid conflict
//     Route::get('/admin/users', [AdminController::class, 'userList'])->name('user.index'); // Changed from '/user'
//     Route::post('/users/store', [AdminController::class, 'store'])->name('user.store');
//     Route::put('/users/update', [AdminController::class, 'update'])->name('user.update');
// });


Route::group(['middleware' => ['auth:user']], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/check-login-status', [LoginController::class, 'checkLoginStatus'])->name('checkLoginStatus');
});

Route::group(['middleware' => ['auth:admin']], function () {
    Route::get('/admin', [AdminUserController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/users', [AdminUserController::class, 'userList'])->name('user.index');
    Route::post('/users/store', [AdminUserController::class, 'store'])->name('user.store');
    Route::put('/users/update', [AdminUserController::class, 'update'])->name('user.update');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('user.destroy');



    Route::get('/admin/tasks', [AdminTaskController::class, 'list'])->name('task');
    Route::post('/tasks/store', [AdminTaskController::class, 'store'])->name('task.store');
    Route::put('/tasks/update', [AdminTaskController::class, 'update'])->name('task.update');
    Route::delete('/tasks/{id}', [AdminTaskController::class, 'destroy'])->name('task.destroy');

});
