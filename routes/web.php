<?php

use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

// ====================================
// Halaman welcome / guest
// ====================================
Route::get('/', function () {
    return view('welcome');
});

// ====================================
// Guest routes (belum login)
// ====================================

// Step 1: Registrasi
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

// Step 2: Step-by-step profile creation
Route::get('/profile/create', [RegisteredUserController::class, 'showProfileForm'])->name('profile.create');
Route::post('/profile', [RegisteredUserController::class, 'storeProfile'])->name('profile.store');

// ====================================
// Authenticated & verified routes
// ====================================
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [TodoController::class, 'index'])->name('dashboard');

    // CRUD Todo List
    Route::resource('lists', TodoController::class);

    // Task routes
    Route::prefix('tasks')->group(function () {
        Route::post('{listId}', [TaskController::class, 'store'])->name('tasks.store');
        Route::put('{id}', [TaskController::class, 'update'])->name('tasks.update');
        Route::delete('{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::put('{id}/done', [TaskController::class, 'done'])->name('tasks.done');
        Route::get('/all', [TaskController::class, 'all'])->name('tasks.all');
    });

    // Profile management (edit/update/delete after account is active)
    Route::prefix('profile')->group(function () {
        Route::get('', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// Auth routes (login, logout, forgot password, etc.)
require __DIR__.'/auth.php';
