<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\Admin\AdminController;


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
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin/login');



Route::post('/register', [UserController::class, 'register'])->name('register');

// Show the login form
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login form submission
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Protect user routes with middleware

Route::group(['middleware' => ['auth', 'role:user']], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
    Route::get('/check-login-status', [LoginController::class, 'checkLoginStatus'])->name('checkLoginStatus');

});

Route::group(['middleware' => ['auth', 'role:admin']], function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/user', [AdminController::class, 'userList'])->name('user.index');
    Route::post('/users/store', [AdminController::class, 'store'])->name('user.store');
    Route::put('/users/update', [AdminController::class, 'update'])->name('user.update');

});

