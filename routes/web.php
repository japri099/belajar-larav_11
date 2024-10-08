<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\UserController;



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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index']);

// register
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);
// Rute untuk menampilkan halaman pemberitahuan setelah registrasi
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Rute untuk verifikasi email melalui tautan
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Rute untuk mengirim ulang email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Email verifikasi telah dikirim!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// login
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');

// user role
Route::put('/user/update', [UserController::class, 'update'])->name('user.update');

// admin role
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('items', [AdminController::class, 'index'])->name('admin.items.index');
    Route::get('items/create', [AdminController::class, 'create'])->name('admin.items.create');
    Route::post('/items', [AdminController::class, 'store'])->name('items.store');
    Route::get('items/{id}/edit', [AdminController::class, 'edit'])->name('admin.items.edit');
    Route::put('items/{id}', [AdminController::class, 'update'])->name('admin.items.update');
    Route::delete('items/{id}', [AdminController::class, 'destroy'])->name('admin.items.destroy');

});
Route::get('/admin/profile/edit', [AdminController::class, 'editProfile'])->name('profile.edit');
Route::post('/admin/profile', [AdminController::class, 'updateProfile'])->name('profile.update');

// superadmin role
Route::middleware(['auth', 'role:superadmin'])->group(function () {

    // Dashboard Superadmin
    Route::get('/superadmin/dashboard', [SuperadminController::class, 'dashboard'])->name('superadmin.dashboard');

    // CRUD User routes
    Route::post('/superadmin/users', [SuperAdminController::class, 'store'])->name('superadmin.users.store');
    Route::get('/superadmin/users/{id}/edit', [SuperAdminController::class, 'edit'])->name('superadmin.users.edit');
    Route::put('/superadmin/users/{id}', [SuperAdminController::class, 'update'])->name('superadmin.users.update');
    Route::delete('/superadmin/users/{id}', [SuperAdminController::class, 'destroy'])->name('superadmin.users.destroy');

});
