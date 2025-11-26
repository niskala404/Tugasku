@extends('layouts.app')

@section('content')
<style>
    :root{
        --bg-page: #ffffff;           /* PUTIH MURNI */
        --panel: #ffffff;
        --muted: #6b7280;
        --text: #0f172a;
        --accent-teal: #06b6d4;
        --accent-teal-deep: #0e7490;
        --accent-orange: #fb923c;
        --success: #10b981;
        --card-radius: 12px;
        --glass: rgba(15,23,42,0.02);
    }

    /* Page & containers */
    body { background: var(--bg-page); color: var(--text); }
    .content-wrap { padding: 1.25rem 0; }
    .dashboard {
        background: var(--panel);
        border-radius: var(--card-radius);
        padding: 1rem;
        color: var(--text);
        border: 1px solid rgba(15,23,42,0.03);
        margin-bottom: 1rem;
        box-shadow: 0 6px 18px rgba(15,23,42,0.04);
    }

    .page-title { font-weight:700; color:var(--text); margin:0; display:flex; gap:.5rem; align-items:center; font-size:1.15rem; }
    .sub { color:var(--muted); margin-top:.25rem; font-size:.95rem; }

    .stats { display:flex; gap:.75rem; margin-top:.85rem; flex-wrap:wrap; }
    /* default stat (netral / putih) */
    .stat {
        background: #ffffff;
        padding:.55rem .9rem;
        border-radius:10px;
        min-width:120px;
        text-align:center;
        border:1px solid rgba(15,23,42,0.03);
        box-shadow: 0 2px 6px rgba(15,23,42,0.03);
    }
    .stat h4 { margin:0; color:var(--text); }
    .stat p { margin:0; color:var(--muted); font-size:.85rem; }

    /* Solid colored stat variants (no gradient) */
    .stat-blue { background: var(--accent-teal); color: #ffffff; border-color: rgba(6,182,212,0.12); }
    .stat-red  { background: var(--accent-orange); color: #ffffff; border-color: rgba(251,146,60,0.12); }

    /* Filter pills */
    .filter-pills .nav-link {
        border-radius:999px;
        padding:.35rem .75rem;
        color:var(--muted);
        background: transparent;
        border:1px solid rgba(15,23,42,0.04);
        font-weight:600;
    }
    /* aktif -> solid biru */
    .filter-pills .nav-link.active{
        background: var(--accent-teal);
        color: #ffffff;
        box-shadow: 0 6px 18px rgba(6,182,212,0.06);
        border-color: rgba(6,182,212,0.12);
    }

    /* Lists (cards) */
    .lists-area { margin-top:1rem; }
    .card-min {
        background: var(--panel);
        border-radius: 10px;
        border: 1px solid rgba(15,23,42,0.04);
        overflow:hidden;
        color:var(--text);
        box-shadow: 0 6px 18px rgba(2,6,23,0.03);
    }
    .card-header-compact {
        display:flex;
        justify-content:space-between;
        align-items:center;
        padding:.75rem 1rem;
        background:transparent;
        border-bottom:1px solid rgba(15,23,42,0.03);
    }
    .card-body { padding:.75rem 1rem; }

    .list-group-item {
        background:transparent;
        border:none;
        padding:.5rem 0;
        color:var(--text);
    }

    .task-name { max-width:70%; display:inline-block; vertical-align:middle; color:var(--text); }
    .task-done { color:var(--muted); text-decoration:line-through; }

    /* Buttons - keep for form submits only */
    .btn {
        border-radius:10px;
        font-weight:700;
    }
    .btn-primary {
        background: var(--accent-teal);
        color: #ffffff;
        border: 1px solid rgba(0,0,0,0.06);
        box-shadow: 0 2px 6px rgba(6,182,212,0.08);
    }
    .btn-primary:hover { background: var(--accent-teal-deep); color:#fff; }

    .btn-accent { background: var(--accent-orange); color: #ffffff; border: 1px solid rgba(0,0,0,0.06); box-shadow: 0 2px 6px rgba(251,146,60,0.08); }
    .btn-outline-success { background: transparent; color: var(--success); border: 1px solid rgba(16,185,129,0.18); }
    .btn-outline-success:hover { background: rgba(16,185,129,0.06); }
    .btn-outline-danger { background: transparent; color: #dc2626; border: 1px solid rgba(220,38,38,0.12); }
    .btn-outline-danger:hover { background: rgba(220,38,38,0.04); }

    .badge { border-radius:999px; padding:.28rem .6rem; font-weight:700; font-size:.80rem; }
    .badge-blue { background: var(--accent-teal); color:#fff; }
    .badge-orange { background: var(--accent-orange); color:#fff; }

    .input-group{ border-radius:10px; overflow:hidden; border:1px solid rgba(15,23,42,0.04); background: #fbfdff; }
    .form-control{ background: transparent; border: none; color: var(--text); padding:.6rem .8rem; box-shadow:none; }
    .form-control::placeholder{ color: rgba(15,23,42,0.35); }

    .empty-state { text-align:center; padding:1.25rem; color:var(--muted); }
    .empty-state i { font-size:2rem; color: rgba(15,23,42,0.06); display:block; margin-bottom:.5rem; }

    /* --- text-link styles --- */
    .link-text {
        color: var(--accent-teal);
        font-weight:700;
        text-decoration: none;
    }
    .link-text:hover { text-decoration: underline; color: var(--accent-teal-deep); }

    .link-text-accent {
        color: var(--accent-orange);
        font-weight:700;
        text-decoration:none;
    }
    .link-text-accent:hover { text-decoration: underline; color:#ef6c00; }

    @media (max-width:768px){
        .stats{ flex-direction:column; }
        .task-name{ max-width:50%; }
    }
</style>

<div class="content-wrap container">
    <div class="dashboard d-flex flex-column flex-md-row justify-content-between">
        <div>
            <h2 class="page-title">
                <i class="fas fa-clipboard-list" style="color:var(--accent-teal)"></i>
                Tugas Saya
            </h2>
            <div class="sub">Kelola daftar dan tugas Anda — tema terang dengan aksen teal & oranye.</div>

            @isset($lists)
            <div class="stats">
                <div class="stat">
                    <h4>{{ $lists->count() }}</h4>
                    <p>Total List</p>
                </div>
                <!-- gunakan kelas stat-blue / stat-red (solid colors, bukan gradasi) -->
                <div class="stat stat-blue">
                    <h4>{{ $lists->sum(fn($l) => $l->tasks->where('is_done', true)->count()) }}</h4>
                    <p>Selesai</p>
                </div>
                <div class="stat stat-red">
                    <h4>{{ $lists->sum(fn($l) => $l->tasks->where('is_done', false)->count()) }}</h4>
                    <p>Belum</p>
                </div>
            </div>
            @endisset
        </div>

        <div class="mt-3 mt-md-0 text-md-end">
            <!-- Ganti tombol "Buat List" menjadi teks link -->
            

            @isset($lists)
            <div class="filter-pills mt-2 d-inline-block">
                <ul class="nav nav-pills">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#all">Semua ({{ $lists->flatMap(fn($l)=>$l->tasks)->count() }})</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pending">Belum ({{ $lists->flatMap(fn($l)=>$l->tasks)->where('is_done', false)->count() }})</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#completed">Selesai ({{ $lists->flatMap(fn($l)=>$l->tasks)->where('is_done', true)->count() }})</button></li>
                </ul>
            </div>
            @endisset
        </div>
    </div>

    {{-- sisa layout (tidak diubah) --}}
    @isset($lists)
        @php $allTasks = $lists->flatMap(fn($l) => $l->tasks); @endphp

        <div class="lists-area">
            @if($lists->count() > 0)
                <div class="row g-3">
                    <div class="col-lg-7">
                        <div class="tab-content">
                            <!-- ALL -->
                            <div class="tab-pane fade show active" id="all">
                                @if($allTasks->count() > 0)
                                    @foreach($allTasks->groupBy('todo_list_id') as $listId => $tasks)
                                        @php $list = $tasks->first()->list; @endphp
                                        <div class="card card-min mb-3">
                                            <div class="card-header-compact">
                                                <div>
                                                    <strong>{{ $list->name }}</strong>
                                                    <div class="small text-muted">{{ $tasks->count() }} tugas</div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <span class="badge badge-orange">{{ $tasks->where('is_done', false)->count() }} open</span>
                                                    <span class="badge badge-blue">{{ $tasks->where('is_done', true)->count() }} done</span>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                    @foreach($tasks as $task)
                                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center gap-2">
                                                                @if($task->is_done)
                                                                    <i class="fas fa-check-circle" style="color:var(--success)"></i>
                                                                    <div class="task-name task-done ms-2">{{ $task->name }}</div>
                                                                @else
                                                                    <i class="far fa-circle" style="color:var(--muted)"></i>
                                                                    <div class="task-name ms-2">{{ $task->name }}</div>
                                                                @endif
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                @if(!$task->is_done)
                                                                    <form action="{{ route('tasks.done', $task->id) }}" method="POST" style="display:inline">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <button class="btn btn-sm btn-outline-success" title="Tandai selesai"><i class="fas fa-check"></i></button>
                                                                    </form>
                                                                @endif
                                                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
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
                                        <h5 class="mt-2">Belum ada tugas</h5>
                                        <p>Tambahkan tugas pada salah satu list Anda.</p>
                                    </div>
                                @endif
                            </div>

                            <!-- PENDING -->
                            <div class="tab-pane fade" id="pending">
                                @php $pendingTasks = $allTasks->where('is_done', false); @endphp
                                @if($pendingTasks->count() > 0)
                                    @foreach($pendingTasks->groupBy('todo_list_id') as $listId => $tasks)
                                        @php $list = $tasks->first()->list; @endphp
                                        <div class="card card-min mb-3">
                                            <div class="card-header-compact">
                                                <strong>{{ $list->name }}</strong>
                                                <span class="badge badge-orange">{{ $tasks->count() }} belum</span>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                    @foreach($tasks as $task)
                                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="far fa-circle" style="color:var(--muted)"></i>
                                                                <div class="task-name ms-2">{{ $task->name }}</div>
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <form action="{{ route('tasks.done', $task->id) }}" method="POST" style="display:inline">
                                                                    @csrf
                                                                    @method('PUT')
                                                                    <button class="btn btn-sm btn-outline-success" title="Tandai selesai"><i class="fas fa-check"></i></button>
                                                                </form>
                                                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
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
                                        <h5 class="mt-2">Tidak ada tugas yang belum selesai</h5>
                                    </div>
                                @endif
                            </div>

                            <!-- COMPLETED -->
                            <div class="tab-pane fade" id="completed">
                                @php $completedTasks = $allTasks->where('is_done', true); @endphp
                                @if($completedTasks->count() > 0)
                                    @foreach($completedTasks->groupBy('todo_list_id') as $listId => $tasks)
                                        @php $list = $tasks->first()->list; @endphp
                                        <div class="card card-min mb-3">
                                            <div class="card-header-compact">
                                                <strong>{{ $list->name }}</strong>
                                                <span class="badge badge-blue">{{ $tasks->count() }} selesai</span>
                                            </div>
                                            <div class="card-body">
                                                <ul class="list-group list-group-flush">
                                                    @foreach($tasks as $task)
                                                        <li class="list-group-item d-flex align-items-center justify-content-between">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <i class="fas fa-check-circle" style="color:var(--success)"></i>
                                                                <div class="task-name task-done ms-2">{{ $task->name }}</div>
                                                            </div>
                                                            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-trash"></i></button>
                                                            </form>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-tasks"></i>
                                        <h5 class="mt-2">Belum ada tugas selesai</h5>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- RIGHT: master lists + add task -->
                    <div class="col-lg-5">
                        @foreach($lists as $list)
                            <div class="card card-min mb-3">
                                <div class="card-header-compact">
                                    <div>
                                        <strong>{{ $list->name }}</strong>
                                        <div class="small text-muted">{{ $list->tasks->count() }} tugas</div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <!-- edit link as plain text -->
                                        <a href="{{ route('lists.edit', $list->id) }}" class="link-text-accent">Edit</a>

                                        <form action="{{ route('lists.destroy', $list->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin menghapus list ini dan semua tugasnya?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus list"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>

                                <div class="card-body">
                                    @if($list->tasks->count() > 0)
                                        <ul class="list-group list-group-flush mb-3">
                                            @foreach($list->tasks as $task)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center gap-2">
                                                        @if($task->is_done)
                                                            <i class="fas fa-check-circle" style="color:var(--success)"></i>
                                                            <div class="task-name task-done ms-2">{{ $task->name }}</div>
                                                        @else
                                                            <i class="far fa-circle" style="color:var(--muted)"></i>
                                                            <div class="task-name ms-2">{{ $task->name }}</div>
                                                        @endif
                                                    </div>

                                                    <div class="d-flex gap-2">
                                                        @if(!$task->is_done)
                                                            <form action="{{ route('tasks.done', $task->id) }}" method="POST" style="display:inline">
                                                                @csrf @method('PUT')
                                                                <button class="btn btn-sm btn-outline-success" title="Tandai selesai"><i class="fas fa-check"></i></button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger" title="Hapus tugas"><i class="fas fa-trash"></i></button>
                                                        </form>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <div class="empty-state"><i class="fas fa-inbox"></i><p class="mb-0">Belum ada tugas.</p></div>
                                    @endif

                                    <form action="{{ route('tasks.store', $list->id) }}" method="POST" class="mt-2">
                                        @csrf
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control" placeholder="Tambah tugas baru..." required>
                                            <button class="btn btn-primary" type="submit"><i class="fas fa-plus"></i> Tambah</button>
                                        </div>
                                        @error('name') <small class="text-danger d-block mt-2"><i class="fas fa-exclamation-circle"></i> {{ $message }}</small> @enderror
                                    </form>
                                </div>
                            </div>
                        @endforeach

                        <div class="card card-min">
                            <div class="card-body text-center">
                                <!-- replace button with plain text link -->
                                <a href="{{ route('lists.create') }}" class="link-text">Buat List Baru</a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card card-min">
                    <div class="card-body text-center">
                        <i class="fas fa-clipboard-list" style="font-size:2rem;color:var(--muted)"></i>
                        <h4 class="mt-2">Belum ada list</h4>
                        <p>Buat list to-do pertama kamu untuk memulai!</p>
                        <a href="{{ route('lists.create') }}" class="link-text">Buat List Pertama</a>
                    </div>
                </div>
            @endif
        </div>
    @else
        {{-- jika $lists tidak ada, tampilkan slot (halaman create/edit) --}}
        <div class="mt-3">
            @yield('content')
        </div>
    @endisset
</div>
@endsection
