<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    // Field yang boleh diisi saat create/update
    protected $fillable = ['name'];

    // Relasi: satu TodoList memiliki banyak Task
    public function tasks()
    {
        return $this->hasMany(Task::class, 'todo_list_id');
    }
}
