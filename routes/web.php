<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\UserController;


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


Route::post('/register', [UserController::class, 'register'])->name('register');

// Show the login form
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login form submission
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Protect user routes with middleware
Route::group(['middleware' => ['auth', 'role:user']], function () {
    Route::get('/user', [UserController::class, 'index'])->name('user.dashboard');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/check-login-status', [LoginController::class, 'checkLoginStatus'])->name('checkLoginStatus');

});

// Route::group(['middleware' => ['auth', 'role:admin']], function () {
//     Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
// });

