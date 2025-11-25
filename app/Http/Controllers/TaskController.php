<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{

    public function all()
    {
        // Ambil semua tasks dengan relasi list, diurutkan berdasarkan status (pending dulu)
        $allTasks = Task::with('list')
            ->orderBy('is_done', 'asc')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('tasks.all', compact('allTasks'));
    }

    // Menambahkan task baru ke sebuah Todo List
    public function store(Request $request, $listId)
    {
        // Validasi input name wajib diisi
        $request->validate(['name' => 'required']);

        // Buat task baru dan kaitkan dengan todo_list_id
        Task::create([
            'todo_list_id' => $listId,
            'name' => $request->name
        ]);

        // Kembali ke halaman sebelumnya
        return back();
    }

    // Mengupdate nama task
    public function update(Request $request, $id)
    {
        // Validasi isi name
        $request->validate(['name' => 'required']);

        // Ambil task berdasarkan ID
        $task = Task::findOrFail($id);

        // Update nama task
        $task->update(['name' => $request->name]);

        // Kembali ke halaman sebelumnya
        return back();
    }

    // Menghapus task
    public function destroy($id)
    {
        // Hapus task berdasarkan ID
        Task::findOrFail($id)->delete();

        // Kembali ke halaman sebelumnya
        return back();
    }

    // Menandai task sebagai selesai
    public function done($id)
    {
        // Ambil task berdasarkan ID
        $task = Task::findOrFail($id);

        // Update kolom is_done menjadi true
        $task->update(['is_done' => true]);

        // Kembali ke halaman sebelumnya
        return back();
    }
}
