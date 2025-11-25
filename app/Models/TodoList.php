<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TodoList extends Model
{
    // Field yang boleh diisi saat create/update
protected $fillable = ['name', 'user_id']; // tambahkan user_id

public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

public function tasks()
{
    return $this->hasMany(\App\Models\Task::class);
}
}