@extends('layouts.app')

@section('content')
<div class="mb-4">
    <h1 class="page-title">
        <i class="fas fa-tasks"></i> All Tasks
    </h1>
    <p class="text-muted">View and manage all your tasks in one place</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Filter Tabs -->
<ul class="nav nav-pills mb-4" id="taskFilter" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="all-tab" data-bs-toggle="pill" data-bs-target="#all" type="button">
            <i class="fas fa-list"></i> All Tasks
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="pending-tab" data-bs-toggle="pill" data-bs-target="#pending" type="button">
            <i class="fas fa-clock"></i> Pending
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="completed-tab" data-bs-toggle="pill" data-bs-target="#completed" type="button">
            <i class="fas fa-check-circle"></i> Completed
        </button>
    </li>
</ul>

<!-- Tab Content -->
<div class="tab-content" id="taskFilterContent">
    <!-- All Tasks -->
    <div class="tab-pane fade show active" id="all" role="tabpanel">
        @if($allTasks->count() > 0)
            @foreach($allTasks->groupBy('todo_list_id') as $listId => $tasks)
                @php
                    $list = $tasks->first()->list;
                @endphp
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-folder"></i>
                        <strong>{{ $list->name }}</strong>
                        <span class="badge bg-light text-dark ms-2">{{ $tasks->count() }} tasks</span>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        @if ($task->is_done)
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <del class="me-2">{{ $task->name }}</del>
                                            <span class="badge bg-success">Done</span>
                                        @else
                                            <i class="far fa-circle text-muted me-2"></i>
                                            <span>{{ $task->name }}</span>
                                        @endif
                                    </div>
                                    <div class="btn-group">
                                        @if (!$task->is_done)
                                        <form action="{{ route('tasks.done', $task->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-success" title="Mark as done">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this task?')">
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
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-inbox"></i>
                <h3>No Tasks Yet</h3>
                <p>Start by creating a list and adding some tasks!</p>
                <a href="{{ route('lists.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Go to Lists
                </a>
            </div>
        @endif
    </div>

    <!-- Pending Tasks -->
    <div class="tab-pane fade" id="pending" role="tabpanel">
        @php
            $pendingTasks = $allTasks->where('is_done', false);
        @endphp
        @if($pendingTasks->count() > 0)
            @foreach($pendingTasks->groupBy('todo_list_id') as $listId => $tasks)
                @php
                    $list = $tasks->first()->list;
                @endphp
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-folder"></i>
                        <strong>{{ $list->name }}</strong>
                        <span class="badge bg-warning text-dark ms-2">{{ $tasks->count() }} pending</span>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <i class="far fa-circle text-muted me-2"></i>
                                        <span>{{ $task->name }}</span>
                                    </div>
                                    <div class="btn-group">
                                        <form action="{{ route('tasks.done', $task->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-success" title="Mark as done">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this task?')">
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
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-check-double"></i>
                <h3>No Pending Tasks</h3>
                <p>Great job! You've completed all your tasks!</p>
            </div>
        @endif
    </div>

    <!-- Completed Tasks -->
    <div class="tab-pane fade" id="completed" role="tabpanel">
        @php
            $completedTasks = $allTasks->where('is_done', true);
        @endphp
        @if($completedTasks->count() > 0)
            @foreach($completedTasks->groupBy('todo_list_id') as $listId => $tasks)
                @php
                    $list = $tasks->first()->list;
                @endphp
                <div class="card mb-4">
                    <div class="card-header" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        <i class="fas fa-folder"></i>
                        <strong>{{ $list->name }}</strong>
                        <span class="badge bg-light text-dark ms-2">{{ $tasks->count() }} completed</span>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            @foreach($tasks as $task)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center flex-grow-1">
                                        <i class="fas fa-check-circle text-success me-2"></i>
                                        <del class="me-2">{{ $task->name }}</del>
                                        <span class="badge bg-success">Done</span>
                                    </div>
                                    <div class="btn-group">
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Delete this task?')">
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
                    </div>
                </div>
            @endforeach
        @else
            <div class="empty-state">
                <i class="fas fa-tasks"></i>
                <h3>No Completed Tasks</h3>
                <p>Complete some tasks to see them here!</p>
            </div>
        @endif
    </div>
</div>
@endsection