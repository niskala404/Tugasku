@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="page-title">
        <i class="fas fa-clipboard-list"></i> My To-Do Lists
    </h1>
    <a href="{{ route('lists.create') }}" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add New List
    </a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($lists->count() > 0)
    <!-- Statistics -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stats-card">
                <h3>{{ $lists->count() }}</h3>
                <p><i class="fas fa-list"></i> Total Lists</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                <h3>{{ $lists->sum(function($list) { return $list->tasks->where('is_done', true)->count(); }) }}</h3>
                <p><i class="fas fa-check-circle"></i> Completed Tasks</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <h3>{{ $lists->sum(function($list) { return $list->tasks->where('is_done', false)->count(); }) }}</h3>
                <p><i class="fas fa-clock"></i> Pending Tasks</p>
            </div>
        </div>
    </div>

    @foreach ($lists as $list)
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <i class="fas fa-folder"></i>
                    <strong>{{ $list->name }}</strong>
                    <span class="badge bg-light text-dark ms-2">
                        {{ $list->tasks->count() }} tasks
                    </span>
                </div>
                <div>
                    <a href="{{ route('lists.edit', $list->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    
                    <form action="{{ route('lists.destroy', $list->id) }}" 
                          method="POST" 
                          style="display:inline"
                          onsubmit="return confirm('Are you sure you want to delete this list and all its tasks?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                {{-- Task List --}}
                @if ($list->tasks->count() > 0)
                    <ul class="list-group list-group-flush mb-3">
                        @foreach ($list->tasks as $task)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center flex-grow-1">
                                    @if ($task->is_done)
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <del class="me-2">{{ $task->name }}</del>
                                        <span class="badge bg-success">
                                            <i class="fas fa-check"></i> Done
                                        </span>
                                    @else
                                        <i class="far fa-circle text-muted me-2"></i>
                                        <span>{{ $task->name }}</span>
                                    @endif
                                </div>

                                <div class="btn-group">
                                    {{-- Mark Done --}}
                                    @if (!$task->is_done)
                                    <form action="{{ route('tasks.done', $task->id) }}" 
                                          method="POST" 
                                          style="display:inline">
                                        @csrf
                                        @method('PUT')
                                        <button class="btn btn-sm btn-success" title="Mark as done">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif

                                    {{-- Delete Task --}}
                                    <form action="{{ route('tasks.destroy', $task->id) }}" 
                                          method="POST" 
                                          style="display:inline"
                                          onsubmit="return confirm('Delete this task?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" title="Delete task">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>No tasks yet. Add your first task below!</p>
                    </div>
                @endif

                {{-- Add Task --}}
                <form action="{{ route('tasks.store', $list->id) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" 
                               name="name" 
                               class="form-control" 
                               placeholder="Add a new task..."
                               required>
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-plus"></i> Add Task
                        </button>
                    </div>

                    @error('name')
                        <small class="text-danger d-block mt-2">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </small>
                    @enderror
                </form>
            </div>
        </div>
    @endforeach
@else
    <div class="empty-state">
        <i class="fas fa-clipboard-list"></i>
        <h3>No Lists Yet</h3>
        <p class="mb-4">Create your first to-do list to get started!</p>
        <a href="{{ route('lists.create') }}" class="btn btn-primary btn-lg">
            <i class="fas fa-plus"></i> Create First List
        </a>
    </div>
@endif
@endsection