<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotpasswordController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/tentang', [PageController::class, 'about'])->name('about');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/forgotpassword', [ForgotpasswordController::class, 'index'])->name('auth.forgotpassword');
    Route::post('/forgotpassword', [ForgotpasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [ForgotpasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ForgotpasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/tasks/pending', [TaskController::class, 'pending'])->name('tasks.pending');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/tasks/export', [TaskController::class, 'export'])
    ->name('tasks.export');
    Route::get('/tasks/{task}/attachments/{attachment}', [TaskController::class, 'downloadAttachment'])->name('tasks.attachment.download');
    Route::resource('categories', CategoryController::class);
    Route::resource('tasks', TaskController::class);
});


