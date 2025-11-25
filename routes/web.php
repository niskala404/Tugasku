<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\TaskController;
use App\Models\Task;

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Resource route untuk Todo List (index, create, store, edit, update, destroy)
Route::resource('lists', TodoController::class);

// Route untuk operasi task dalam list
// Menambahkan task ke list tertentu
Route::post('/lists/{listId}/tasks', [TaskController::class, 'store'])->name('tasks.store');

// Update isi task berdasarkan ID
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');

// Hapus task berdasarkan ID
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

// Menandai task sebagai selesai
Route::put('/tasks/{id}/done', [TaskController::class, 'done'])->name('tasks.done');

// Debug: menampilkan semua task beserta relasi list-nya
Route::get('/tasks', function () {
    return Task::with('list')->get();
});

// Menghapus task berdasarkan name (opsional untuk testing)
Route::delete('/tasks/name/{name}', function ($name) {
    Task::where('name', $name)->delete();
});

//menampilkan semua task dengan statusnya
Route::get('/tasks/all', [TaskController::class, 'all'])->name('tasks.all');