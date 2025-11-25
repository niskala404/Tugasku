<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Dashboard (akses hanya jika login)
Route::get('/dashboard', [TodoController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Semua route yang butuh login
Route::middleware('auth')->group(function () {

    // CRUD Todo List
    Route::resource('lists', TodoController::class);

    // Task Routes
    Route::post('/tasks/{listId}', [TaskController::class, 'store'])->name('tasks.store');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

    // Tandai task selesai
    Route::put('/tasks/{id}/done', [TaskController::class, 'done'])->name('tasks.done');

    // Melihat semua tasks
    Route::get('/all-tasks', [TaskController::class, 'all'])->name('tasks.all');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth route (register + login)
require __DIR__.'/auth.php';
