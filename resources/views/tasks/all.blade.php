@extends('layouts.app')

@section('content')
<style>
    /* Container background jadi hitam */
    body { background:#000 !important; }
    .page-title { font-size: 1.25rem; margin-bottom: .25rem; color:#fff; }
    .card-min { border: 0; border-radius: .75rem; background:#111; color:#e9ecef; box-shadow: 0 6px 18px rgba(0,0,0,0.3); }
    .list-header { display:flex; align-items:center; gap:.5rem; }
    .list-header .badge { font-weight:600; }
    .empty-state { text-align:center; padding:3rem 1rem; color:#aaa; }
    .empty-state i { font-size:2.25rem; margin-bottom:.5rem; color:#777; }
    .task-name { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .btn-icon { width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center; padding:0; border-radius:.5rem; }
    .controls .btn { margin-left:.25rem; }
    .nav-pills .nav-link { color:#ccc; }
    .nav-pills .nav-link.active { background:#444; }
    .list-group-item { background:#111; color:#ddd; border-color:#222; }
    @media (max-width:576px){
        .list-header { flex-direction:column; align-items:flex-start; gap:.25rem; }
    }
</style>

<div class="mb-4">
    <h1 class="page-title">
        <i class="fas fa-tasks"></i> Semua Tugas
    </h1>
    <p class="text-muted">Lihat dan kelola semua tugas Anda di sini</p>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> <strong>Berhasil:</strong> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Tutup"></button>
    </div>
@endif

<ul class="nav nav-pills mb-4" id="taskFilter" role="tablist">
    <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#all" type="button">Semua</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pending" type="button">Belum Selesai</button></li>
    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#completed" type="button">Selesai</button></li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="all">
        @if($allTasks->count() > 0)
            @foreach($allTasks->groupBy('todo_list_id') as $listId => $tasks)
                @php $list = $tasks->first()->list; @endphp
                <div class="card card-min mb-3">
                    <div class="card-body">
                        <div class="list-header mb-2">
                            <div>
                                <strong>{{ $list->name }}</strong>
                                <div class="text-muted small"> {{ $tasks->count() }} tugas</div>
                            </div>
                            <div class="ms-auto text-end">
                                <span class="badge bg-light text-dark">{{ $tasks->where('is_done', false)->count() }} belum</span>
                                <span class="badge bg-success text-white ms-1">{{ $tasks->where('is_done', true)->count() }} selesai</span>
                            </div>
                        </div>

                        <ul class="list-group list-group-flush">
                            @foreach($tasks as $task)
                                <li class="list-group-item d-flex align-items-center justify-content-between py-2">
                                    <div class="d-flex align-items-center gap-2 flex-grow-1">
                                        @if($task->is_done)
                                            <i class="fas fa-check-circle text-success"></i>
                                            <div class="task-name ms-2">
                                                <del class="mb-0">{{ $task->name }}</del>
                                                <div class="small text-success">Selesai</div>
                                            </div>
                                        @else
                                            <i class="far fa-circle text-muted"></i>
                                            <div class="task-name ms-2">{{ $task->name }}</div>
                                        @endif
                                    </div>

                                    <div class="controls d-flex align-items-center">
                                        @if(!$task->is_done)
                                            <form action="{{ route('tasks.done', $task->id) }}" method="POST" style="display:inline">
                                                @csrf
                                                @method('PUT')
                                                <button class="btn btn-sm btn-outline-success btn-icon"><i class="fas fa-check"></i></button>
                                            </form>
                                        @endif

                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger btn-icon"><i class="fas fa-trash"></i></button>
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
                <h4 class="mt-2">Belum ada tugas</h4>
                <p>Mulai dengan membuat daftar dan menambahkan tugas.</p>
            </div>
        @endif
    </div>

    <div class="tab-pane fade" id="pending">
        @php $pendingTasks = $allTasks->where('is_done', false); @endphp
        @if($pendingTasks->count() > 0)
            @foreach($pendingTasks->groupBy('todo_list_id') as $listId => $tasks)
                @php $list = $tasks->first()->list; @endphp
                <div class="card card-min mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <strong>{{ $list->name }}</strong>
                            <div class="ms-auto"><span class="badge bg-warning text-dark">{{ $tasks->count() }} belum</span></div>
                        </div>

                        <ul class="list-group list-group-flush">
                            @foreach($tasks as $task)
                                <li class="list-group-item d-flex align-items-center justify-content-between py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="far fa-circle text-muted"></i>
                                        <div class="task-name">{{ $task->name }}</div>
                                    </div>

                                    <div class="controls d-flex align-items-center">
                                        <form action="{{ route('tasks.done', $task->id) }}" method="POST" style="display:inline">
                                            @csrf
                                            @method('PUT')
                                            <button class="btn btn-sm btn-outline-success btn-icon"><i class="fas fa-check"></i></button>
                                        </form>
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger btn-icon"><i class="fas fa-trash"></i></button>
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
                <h4 class="mt-2">Tidak ada tugas yang belum selesai</h4>
                <p>Semua tugas sudah selesai — bagus!</p>
            </div>
        @endif
    </div>

    <div class="tab-pane fade" id="completed">
        @php $completedTasks = $allTasks->where('is_done', true); @endphp
        @if($completedTasks->count() > 0)
            @foreach($completedTasks->groupBy('todo_list_id') as $listId => $tasks)
                @php $list = $tasks->first()->list; @endphp
                <div class="card card-min mb-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-2">
                            <strong>{{ $list->name }}</strong>
                            <div class="ms-auto"><span class="badge bg-success text-white">{{ $tasks->count() }} selesai</span></div>
                        </div>

                        <ul class="list-group list-group-flush">
                            @foreach($tasks as $task)
                                <li class="list-group-item d-flex align-items-center justify-content-between py-2">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="fas fa-check-circle text-success"></i>
                                        <div class="task-name"><del>{{ $task->name }}</del></div>
                                    </div>

                                    <div class="controls">
                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger btn-icon"><i class="fas fa-trash"></i></button>
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
                <h4 class="mt-2">Belum ada tugas selesai</h4>
                <p>Selesaikan beberapa tugas untuk melihatnya di sini.</p>
            </div>
        @endif
    </div>
</div>
@endsection
