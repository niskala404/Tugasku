<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoList;

class TodoController extends Controller
{
    // Menampilkan semua list beserta tasks-nya
    public function index()
    {
        // with('tasks') digunakan agar relasi tasks ikut di-load
        $lists = TodoList::with('tasks')->get();
        return view('todo.index', compact('lists'));
    }

    // Menampilkan form untuk membuat list baru
    public function create()
    {
        return view('todo.create');
    }

    // Menyimpan list baru ke database
    public function store(Request $request)
    {
        // Validasi field name wajib diisi
        $request->validate(['name' => 'required']);

        // Insert data ke tabel todo_lists
        TodoList::create(['name' => $request->name]);

        // Redirect ke halaman index setelah berhasil
        return redirect()->route('lists.index');
    }

    // Menampilkan form edit untuk list tertentu
    public function edit($id)
    {
        // Cari list berdasarkan ID, jika tidak ada akan throw 404
        $list = TodoList::findOrFail($id);

        // Mengirim data ke blade edit
        return view('todo.edit', compact('list'));
    }

    // Update data list berdasarkan ID
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate(['name' => 'required']);

        // Ambil data berdasarkan ID
        $list = TodoList::findOrFail($id);

        // Update name list
        $list->update(['name' => $request->name]);

        // Kembali ke index
        return redirect()->route('lists.index');
    }

    // Menghapus list berdasarkan ID
    public function destroy($id)
    {
        // Delete data jika ada
        TodoList::findOrFail($id)->delete();

        // Redirect setelah delete
        return redirect()->route('lists.index');
    }
}
