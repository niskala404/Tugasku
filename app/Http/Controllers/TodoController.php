<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TodoList;

class TodoController extends Controller
{
    // Menampilkan semua list milik user yang login
    public function index()
    {
        $lists = TodoList::where('user_id', auth()->id())
            ->with('tasks')
            ->get();

        return view('todo.index', compact('lists'));
    }

    // Menampilkan form membuat list
    public function create()
    {
        return view('todo.create');
    }

    // Menyimpan list baru ke database
    public function store(Request $request)
    {
        $request->validate(['name' => 'required']);

        // CREATE list hanya untuk user yang login
        TodoList::create([
            'name' => $request->name,
            'user_id' => auth()->id(), 
        ]);

        return redirect()->route('lists.index');
    }

    // Menampilkan halaman edit
    public function edit($id)
    {
        $list = TodoList::findOrFail($id);

        // Cek kepemilikan
        if ($list->user_id !== auth()->id()) {
            abort(403); // akses ditolak
        }

        return view('todo.edit', compact('list'));
    }

    // Update list
    public function update(Request $request, $id)
    {
        $request->validate(['name' => 'required']);

        $list = TodoList::findOrFail($id);

        // Cek kepemilikan
        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        $list->update(['name' => $request->name]);

        return redirect()->route('lists.index');
    }

    // Hapus list
    public function destroy($id)
    {
        $list = TodoList::findOrFail($id);

        // Cek kepemilikan
        if ($list->user_id !== auth()->id()) {
            abort(403);
        }

        $list->delete();

        return redirect()->route('lists.index');
    }
}
