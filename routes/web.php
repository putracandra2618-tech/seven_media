<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
Route::get('/tasks/pending', [TaskController::class, 'pending'])->name('tasks.pending');

Route::get('/info', function () {
    return response()->json([
        'app' => 'Task Manager',
        'version' => '1.0.0',
        'laravel' => app()->version(),
        'php' => PHP_VERSION,
    ]);
});
