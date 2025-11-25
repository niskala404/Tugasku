<?php

namespace App\Policies;

use App\Models\TodoList;
use App\Models\User;

class TodoListPolicy
{
    /**
     * User boleh melihat semua list miliknya sendiri.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * User hanya boleh lihat list yang dia miliki.
     */
    public function view(User $user, TodoList $todoList): bool
    {
        return $todoList->user_id === $user->id;
    }

    /**
     * Semua user yang login boleh membuat list.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * User hanya boleh update list miliknya.
     */
    public function update(User $user, TodoList $todoList): bool
    {
        return $todoList->user_id === $user->id;
    }

    /**
     * User hanya boleh delete list miliknya.
     */
    public function delete(User $user, TodoList $todoList): bool
    {
        return $todoList->user_id === $user->id;
    }

    /**
     * Restore (opsional), tetap hanya untuk pemilik.
     */
    public function restore(User $user, TodoList $todoList): bool
    {
        return $todoList->user_id === $user->id;
    }

    /**
     * Force delete (opsional), tetap hanya pemilik.
     */
    public function forceDelete(User $user, TodoList $todoList): bool
    {
        return $todoList->user_id === $user->id;
    }
}
