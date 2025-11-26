@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<style>
/* (sama persis seperti style kamu — saya tidak mengubahnya) */
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

/* deadline badges */
.deadline-badge {
    display:inline-block;
    padding:.28rem .6rem;
    border-radius:8px;
    font-weight:700;
    color:#fff;
    font-size:.80rem;
    margin-top:.35rem;
}
.deadline-safe { background: var(--accent-teal); }
.deadline-soon { background: var(--accent-orange); }
.deadline-over { background: #ef4444; }

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

/* simple in-page toast */
#inpage-toast {
    position: fixed;
    right: 16px;
    bottom: 16px;
    background: rgba(2,6,23,0.95);
    color: #fff;
    padding: 12px 16px;
    border-radius: 10px;
    z-index: 9999;
    display: none;
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
                                        @php
                                            $list = $tasks->first()->list;
                                            $due = $list->due_date ? Carbon::parse($list->due_date) : null;
                                            $daysLeft = $due ? now()->diffInDays($due, false) : null;
                                        @endphp

                                        <div class="card card-min mb-3"
                                             data-list-id="{{ $list->id }}"
                                             data-due="{{ $due ? $due->toDateString() : '' }}"
                                             data-list-name="{{ e($list->name) }}">
                                            <div class="card-header-compact">
                                                <div>
                                                    <strong>{{ $list->name }}</strong>
                                                    <div class="small text-muted">{{ $tasks->count() }} tugas</div>

                                                    @if($due)
                                                        @if($daysLeft < 0)
                                                            <div class="deadline-badge deadline-over">Lewat: {{ $due->format('d M Y') }}</div>
                                                        @elseif($daysLeft <= 2)
                                                            <div class="deadline-badge deadline-soon">Deadline: {{ $due->format('d M Y') }}</div>
                                                        @else
                                                            <div class="deadline-badge deadline-safe">Deadline: {{ $due->format('d M Y') }}</div>
                                                        @endif
                                                    @endif
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
                                                                        <button class="btn btn-sm btn-outline-success" title="Tandai selesai" aria-label="Tandai selesai">
                                                                            <!-- ceklis icon -->
                                                                            <i class="fas fa-check" aria-hidden="true"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button class="btn btn-sm btn-outline-danger" title="Hapus" aria-label="Hapus tugas">
                                                                        <!-- sampan icon -->
                                                                        <i class="fas fa-ship" aria-hidden="true"></i>
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
                                        <div class="card card-min mb-3"
                                             data-list-id="{{ $list->id }}"
                                             data-due="{{ $list->due_date ? Carbon::parse($list->due_date)->toDateString() : '' }}"
                                             data-list-name="{{ e($list->name) }}">
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
                                                                    <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-ship"></i></button>
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
                                        <div class="card card-min mb-3"
                                             data-list-id="{{ $list->id }}"
                                             data-due="{{ $list->due_date ? Carbon::parse($list->due_date)->toDateString() : '' }}"
                                             data-list-name="{{ e($list->name) }}">
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
                                                                <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-ship"></i></button>
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
                            @php $due = $list->due_date ? Carbon::parse($list->due_date) : null; $daysLeft = $due ? now()->diffInDays($due, false) : null; @endphp
                            <div class="card card-min mb-3"
                                 data-list-id="{{ $list->id }}"
                                 data-due="{{ $due ? $due->toDateString() : '' }}"
                                 data-list-name="{{ e($list->name) }}">
                                <div class="card-header-compact">
                                    <div>
                                        <strong>{{ $list->name }}</strong>
                                        <div class="small text-muted">{{ $list->tasks->count() }} tugas</div>
                                        @if($due)
                                            @if($daysLeft < 0)
                                                <div class="deadline-badge deadline-over">Lewat: {{ $due->format('d M Y') }}</div>
                                            @elseif($daysLeft <= 2)
                                                <div class="deadline-badge deadline-soon">Deadline: {{ $due->format('d M Y') }}</div>
                                            @else
                                                <div class="deadline-badge deadline-safe">Deadline: {{ $due->format('d M Y') }}</div>
                                            @endif
                                        @endif
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('lists.edit', $list->id) }}" class="link-text-accent">Edit</a>

                                        <form action="{{ route('lists.destroy', $list->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin menghapus list ini dan semua tugasnya?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus list"><i class="fas fa-ship"></i></button>
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
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger" title="Hapus tugas"><i class="fas fa-ship"></i></button>
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
        <div class="mt-3">
            @yield('content')
        </div>
    @endisset
</div>

<!-- in-page toast and audio -->
<div id="inpage-toast"></div>

<!-- Pastikan file di public/sound/notif.mp3 -->
<audio id="notif-audio" preload="auto">
    <source src="/sound/notif.mp3" type="audio/mpeg">
</audio>

<script>
(function(){
    const SOON_DAYS = 2; // <= 2 hari dianggap 'mendekat'
    // Minta permission notifikasi (dipicu saat klik/keydown pertama)
    function askPermission() {
        if (!("Notification" in window)) return;
        if (Notification.permission === "default") Notification.requestPermission();
    }

    function showBrowserNotification(title, body) {
        if (!("Notification" in window)) return false;
        if (Notification.permission === "granted") {
            try {
                new Notification(title, { body, icon: '/favicon.ico' });
                return true;
            } catch(e) { return false; }
        }
        return false;
    }

    function showInPage(text){
        const t = document.getElementById('inpage-toast');
        t.textContent = text;
        t.style.display = 'block';
        setTimeout(()=> t.style.display = 'none', 7000);
    }

    function playSound(){
        const audio = document.getElementById('notif-audio');
        if (audio && (audio.currentSrc || audio.src)) {
            const p = audio.play();
            if (p && p.catch) {
                p.catch(()=> fallbackBeep());
            }
        } else fallbackBeep();
    }

    function fallbackBeep(){
        try {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const o = ctx.createOscillator();
            const g = ctx.createGain();
            o.type='sine'; o.frequency.value = 880;
            g.gain.value = 0.03;
            o.connect(g); g.connect(ctx.destination);
            o.start();
            setTimeout(()=>{ o.stop(); ctx.close(); }, 250);
        } catch(e){}
    }

    function checkDeadlines(){
        document.querySelectorAll('[data-due]').forEach(card=>{
            const dueStr = card.getAttribute('data-due');
            const id = card.getAttribute('data-list-id');
            const name = card.getAttribute('data-list-name') || 'Daftar';
            if (!dueStr) return;
            // gunakan format YYYY-MM-DD dari server; tambahkan waktu agar new Date stabil
            const due = new Date(dueStr + 'T00:00:00');
            const now = new Date();
            const diffMs = due - now;
            const diffDays = Math.floor(diffMs / (1000*60*60*24));

            const keyOver = `todo_notify_over_${id}`;
            const keySoon = `todo_notify_soon_${id}`;

            if (diffDays < 0) {
                // lewat deadline -> notifikasi 1x
                if (!localStorage.getItem(keyOver)) {
                    const text = `${name} melewati deadline (${due.toLocaleDateString()}).`;
                    const shown = showBrowserNotification('Deadline Lewat', text);
                    if (!shown) showInPage(text);
                    playSound();
                    localStorage.setItem(keyOver, '1');
                }
            } else if (diffDays <= SOON_DAYS) {
                // mendekat -> notifikasi 1x
                if (!localStorage.getItem(keySoon)) {
                    const text = `${name} akan deadline pada ${due.toLocaleDateString()}.`;
                    const shown = showBrowserNotification('Deadline Mendekat', text);
                    if (!shown) showInPage(text);
                    playSound();
                    localStorage.setItem(keySoon, '1');
                }
            }
        });
    }

    // Minta permission di interaksi pertama (banyak browser blok autoplay audio tanpa interaksi)
    document.addEventListener('click', askPermission, { once: true });
    document.addEventListener('keydown', askPermission, { once: true });

    window.addEventListener('load', ()=>{
        checkDeadlines();
        // jalankan berkala selama page terbuka (cek tiap 1 menit)
        setInterval(checkDeadlines, 1000*60);
    });
})();
</script>
@endsection
