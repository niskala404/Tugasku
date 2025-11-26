@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<style>
:root{
    --bg-page: #f8fafc; /* softer page */
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

/* layout */
body { background: var(--bg-page); color: var(--text); }
.content-wrap { padding: 1.25rem 0; }
.dashboard { background: transparent; padding: 0; }
.header-area { display:flex; justify-content:space-between; gap:1rem; align-items:center; margin-bottom:1rem }
.page-title { font-weight:800; color:var(--text); display:flex; gap:.6rem; align-items:center; font-size:1.15rem; }
.sub { color:var(--muted); margin-top:.25rem; font-size:.95rem; }

/* stats */
.stats { display:flex; gap:.6rem; margin-top:.7rem; flex-wrap:wrap; align-items:center }
.stat { background: linear-gradient(180deg, rgba(255,255,255,0.9), var(--panel)); padding:.5rem .9rem; border-radius:10px; min-width:110px; text-align:center; border:1px solid rgba(15,23,42,0.03); box-shadow: 0 6px 14px rgba(2,6,23,0.02); transform-origin: center; }
.stat h4 { margin:0; color:var(--text); transition: transform .36s cubic-bezier(.2,.8,.2,1); }
.stat p { margin:0; color:var(--muted); font-size:.82rem; }

/* main grid */
.grid { display:grid; grid-template-columns: 1fr 380px; gap:0.9rem; }
@media (max-width:992px){ .grid{ grid-template-columns: 1fr; } }

/* cards */
.card-min { background: var(--panel); border-radius: var(--card-radius); overflow:hidden; color:var(--text); border:1px solid rgba(15,23,42,0.04); box-shadow: 0 6px 18px rgba(2,6,23,0.03); transition: transform .18s ease, box-shadow .18s ease; }
@media (hover: hover){ .card-min:hover{ transform: translateY(-4px); box-shadow: 0 12px 28px rgba(2,6,23,0.04); } }
.card-header-compact { display:flex; justify-content:space-between; align-items:center; padding:.75rem .9rem; background:transparent; border-bottom:1px solid rgba(15,23,42,0.03); }
.card-body { padding:.6rem .9rem; }

.list-group-item { background:transparent; border:none; padding:.45rem 0; color:var(--text); display:flex; align-items:center; justify-content:space-between; }

.task-name { max-width:72%; display:inline-block; vertical-align:middle; color:var(--text); transition: color .12s ease; }
.task-done { color:var(--muted); text-decoration:line-through; }

/* interactive controls */
.filter-pills .nav-link { border-radius:999px; padding:.32rem .65rem; color:var(--muted); background: transparent; border:1px solid rgba(15,23,42,0.04); font-weight:700; }
.filter-pills .nav-link.active{ background: var(--accent-teal); color: #fff; box-shadow: 0 8px 20px rgba(6,182,212,0.05); border-color: rgba(6,182,212,0.12); }

/* right column */
.sidebar { position:sticky; top:18px; }
.link-text { color: var(--accent-teal); font-weight:700; text-decoration: none; }
.link-text-accent { color: var(--accent-orange); font-weight:700; text-decoration:none; }

/* badges */
.badge { border-radius:999px; padding:.26rem .56rem; font-weight:700; font-size:.78rem; }
.badge-blue { background: var(--accent-teal); color:#fff; }
.badge-orange { background: var(--accent-orange); color:#fff; }

/* deadline badges: subtle, reduced motion */
.deadline-badge { display:inline-block; padding:.26rem .56rem; border-radius:8px; font-weight:700; color:#fff; font-size:.78rem; margin-top:.28rem; }
.deadline-safe { background: var(--accent-teal); }
.deadline-soon { background: var(--accent-orange); }
.deadline-over { background: #ef4444; }

/* tiny microinteractions */
.btn { border-radius:10px; font-weight:700; transition: transform .08s ease; }
.btn:active{ transform: translateY(1px) scale(.997); }
.btn-primary { background: var(--accent-teal); color: #fff; border: none; box-shadow: 0 6px 12px rgba(6,182,212,0.05); }
.btn-primary:hover{ background: var(--accent-teal-deep); }

.input-group{ border-radius:10px; overflow:hidden; border:1px solid rgba(15,23,42,0.04); background: #fbfdff; display:flex; }
.form-control{ background: transparent; border: none; color: var(--text); padding:.6rem .8rem; box-shadow:none; }
.form-control::placeholder{ color: rgba(15,23,42,0.35); }

/* reveal animation: shorter and simpler */
.reveal { opacity:0; transform: translateY(8px) scale(.997); transition: opacity .32s ease, transform .32s cubic-bezier(.2,.8,.2,1); }
.reveal.visible{ opacity:1; transform: translateY(0) scale(1); }

/* search */
.search-wrap { display:flex; gap:.5rem; align-items:center; }
.search { display:flex; gap:.5rem; align-items:center; background:var(--panel); padding:.4rem .55rem; border-radius:999px; border:1px solid rgba(15,23,42,0.04); }

/* empty state */
.empty-state { text-align:center; padding:1.1rem; color:var(--muted); }
.empty-state i { font-size:1.8rem; color: rgba(15,23,42,0.06); display:block; margin-bottom:.5rem; }

/* toast */
#inpage-toast { position: fixed; right: 14px; bottom: 14px; background: rgba(2,6,23,0.95); color: #fff; padding: 10px 14px; border-radius: 10px; z-index: 9999; display: none; transform-origin: right bottom; }

/* responsive tweaks for mobile */
@media (max-width:768px){
    .card-header-compact{ padding:.6rem .8rem; }
    .task-name{ max-width:60%; }
    .search { padding:.35rem .5rem; }
}
@media (max-width:576px){
    .grid{ grid-template-columns: 1fr; }
    .sidebar{ position:static; }
    .card-header-compact{ flex-direction:column; align-items:flex-start; gap:.4rem; }
    .task-name{ max-width:100%; }
    .form-control{ padding:.8rem; }
    .btn{ padding:.55rem .6rem; font-size:.95rem; }
    .badge{ font-size:.72rem; padding:.22rem .46rem }
}
</style>

<div class="content-wrap container">
    <div class="dashboard">
        <div class="header-area">
            <div>
                <h2 class="page-title">
                    <i class="fas fa-clipboard-list" style="color:var(--accent-teal)"></i>
                    Tugas Saya
                </h2>
                <div class="sub">Kelola Tugas Kamu, Tetap Teratur Tanpa Ribet!</div>
            </div>

            <div class="d-flex align-items-center gap-2">
                @isset($lists)
                <div class="filter-pills">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item"><button class="nav-link active" data-bs-toggle="pill" data-bs-target="#all" type="button">Semua ({{ $lists->flatMap(fn($l)=>$l->tasks)->count() }})</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#pending" type="button">Belum ({{ $lists->flatMap(fn($l)=>$l->tasks)->where('is_done', false)->count() }})</button></li>
                        <li class="nav-item"><button class="nav-link" data-bs-toggle="pill" data-bs-target="#completed" type="button">Selesai ({{ $lists->flatMap(fn($l)=>$l->tasks)->where('is_done', true)->count() }})</button></li>
                    </ul>
                </div>
                @endisset
            </div>
        </div>

        @isset($lists)
        <div class="stats">
            <div class="stat" aria-hidden>
                <h4 data-count-target>{{ $lists->count() }}</h4>
                <p>Total List</p>
            </div>
            <div class="stat" aria-hidden>
                <h4 data-count-target>{{ $lists->sum(fn($l) => $l->tasks->where('is_done', true)->count()) }}</h4>
                <p>Selesai</p>
            </div>
            <div class="stat" aria-hidden>
                <h4 data-count-target>{{ $lists->sum(fn($l) => $l->tasks->where('is_done', false)->count()) }}</h4>
                <p>Belum</p>
            </div>
        </div>

        <div class="grid mt-3">
            <div>
                <div class="tab-content">
                    <div class="tab-pane fade show active reveal" id="all">
                        @php $allTasks = $lists->flatMap(fn($l) => $l->tasks); @endphp
                        @if($allTasks->count() > 0)
                            @foreach($allTasks->groupBy('todo_list_id') as $listId => $tasks)
                                @php
                                    $list = $tasks->first()->list;
                                    $due = $list->due_date ? Carbon::parse($list->due_date) : null;
                                    $daysLeft = $due ? now()->diffInDays($due, false) : null;
                                @endphp

                                <div class="card card-min mb-3" data-list-id="{{ $list->id }}" data-due="{{ $due ? $due->toDateString() : '' }}" data-list-name="{{ e($list->name) }}">
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

                                        <div class="d-flex gap-2 align-items-center">
                                            <span class="badge badge-orange" aria-label="open count">{{ $tasks->where('is_done', false)->count() }} open</span>
                                            <span class="badge badge-blue" aria-label="done count">{{ $tasks->where('is_done', true)->count() }} done</span>
                                            <button class="btn btn-sm" data-toggle-collapse aria-expanded="true" title="Sembunyikan/Tampilkan tugas"> <i class="fas fa-chevron-up"></i></button>
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
                                                                @csrf @method('PUT')
                                                                <button class="btn btn-sm btn-outline-success" title="Tandai selesai" aria-label="Tandai selesai"><i class="fas fa-check"></i></button>
                                                            </form>
                                                        @endif
                                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger" title="Hapus" aria-label="Hapus tugas"><i class="fas fa-ship"></i></button>
                                                        </form>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state reveal">
                                <i class="fas fa-inbox"></i>
                                <h5 class="mt-2">Belum ada tugas</h5>
                                <p>Tambahkan tugas pada salah satu list Anda.</p>
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade reveal" id="pending">
                        @php $pendingTasks = $allTasks->where('is_done', false); @endphp
                        @if($pendingTasks->count() > 0)
                            @foreach($pendingTasks->groupBy('todo_list_id') as $listId => $tasks)
                                @php $list = $tasks->first()->list; @endphp
                                <div class="card card-min mb-3" data-list-id="{{ $list->id }}" data-due="{{ $list->due_date ? Carbon::parse($list->due_date)->toDateString() : '' }}" data-list-name="{{ e($list->name) }}">
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
                                                            @csrf @method('PUT')
                                                            <button class="btn btn-sm btn-outline-success" title="Tandai selesai"><i class="fas fa-check"></i></button>
                                                        </form>
                                                        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                            @csrf @method('DELETE')
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
                            <div class="empty-state reveal">
                                <i class="fas fa-check-double"></i>
                                <h5 class="mt-2">Tidak ada tugas yang belum selesai</h5>
                            </div>
                        @endif
                    </div>

                    <div class="tab-pane fade reveal" id="completed">
                        @php $completedTasks = $allTasks->where('is_done', true); @endphp
                        @if($completedTasks->count() > 0)
                            @foreach($completedTasks->groupBy('todo_list_id') as $listId => $tasks)
                                @php $list = $tasks->first()->list; @endphp
                                <div class="card card-min mb-3" data-list-id="{{ $list->id }}" data-due="{{ $list->due_date ? Carbon::parse($list->due_date)->toDateString() : '' }}" data-list-name="{{ e($list->name) }}">
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
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger" title="Hapus"><i class="fas fa-ship"></i></button>
                                                    </form>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="empty-state reveal">
                                <i class="fas fa-tasks"></i>
                                <h5 class="mt-2">Belum ada tugas selesai</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <aside class="sidebar">
                @foreach($lists as $list)
                    @php $due = $list->due_date ? Carbon::parse($list->due_date) : null; $daysLeft = $due ? now()->diffInDays($due, false) : null; @endphp
                    <div class="card card-min mb-3 reveal" data-list-id="{{ $list->id }}" data-due="{{ $due ? $due->toDateString() : '' }}" data-list-name="{{ e($list->name) }}">
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

                            <form action="{{ route('tasks.store', $list->id) }}" method="POST" class="mt-2 add-task-form">
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

                <div class="card card-min reveal">
                    <div class="card-body text-center">
                        <a href="{{ route('lists.create') }}" class="link-text">Buat List Baru</a>
                    </div>
                </div>
            </aside>
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
        @endisset
    </div>
</div>

<!-- in-page toast and audio -->
<div id="inpage-toast" role="status" aria-live="polite"></div>
<audio id="notif-audio" preload="auto"><source src="/sound/notif.mp3" type="audio/mpeg"></audio>

<script>
(function(){
    const SOON_DAYS = 2;
    // tiny helpers
    const qs = s => document.querySelector(s);
    const qsa = s => [...document.querySelectorAll(s)];

    function askPermission(){ if(!('Notification' in window)) return; if(Notification.permission === 'default') Notification.requestPermission(); }
    function showBrowserNotification(title, body){ if(!('Notification' in window)) return false; if(Notification.permission === 'granted'){ try{ new Notification(title, { body, icon: '/favicon.ico' }); return true }catch(e){ return false } } return false }

    function showInPage(text){ const t = qs('#inpage-toast'); if(!t) return; t.textContent = text; t.style.display = 'block'; clearTimeout(t._h); t._h = setTimeout(()=>{ t.style.display = 'none'; }, 5000); }

    function playSound(){ const audio = qs('#notif-audio'); if(audio && (audio.currentSrc || audio.src)){ const p = audio.play(); if(p && p.catch) p.catch(()=> fallbackBeep()); } else fallbackBeep(); }
    function fallbackBeep(){ try{ const ctx = new (window.AudioContext||window.webkitAudioContext)(); const o = ctx.createOscillator(); const g = ctx.createGain(); o.type='sine'; o.frequency.value=880; g.gain.value=0.02; o.connect(g); g.connect(ctx.destination); o.start(); setTimeout(()=>{ o.stop(); ctx.close(); }, 180); }catch(e){} }

    // deadline check (kept as original behaviour)
    function checkDeadlines(){ qsa('[data-due]').forEach(card=>{ const dueStr = card.getAttribute('data-due'); const id = card.getAttribute('data-list-id'); const name = card.getAttribute('data-list-name') || 'Daftar'; if(!dueStr) return; const due = new Date(dueStr + 'T00:00:00'); const now = new Date(); const diffMs = due - now; const diffDays = Math.floor(diffMs / (1000*60*60*24)); const keyOver = `todo_notify_over_${id}`; const keySoon = `todo_notify_soon_${id}`;
            if(diffDays < 0){ if(!localStorage.getItem(keyOver)){ const text = `${name} melewati deadline (${due.toLocaleDateString()}).`; const shown = showBrowserNotification('Deadline Lewat', text); if(!shown) showInPage(text); playSound(); localStorage.setItem(keyOver,'1'); } }
            else if(diffDays <= SOON_DAYS){ if(!localStorage.getItem(keySoon)){ const text = `${name} akan deadline pada ${due.toLocaleDateString()}.`; const shown = showBrowserNotification('Deadline Mendekat', text); if(!shown) showInPage(text); playSound(); localStorage.setItem(keySoon,'1'); } }
        }); }

    // reveal on scroll (subtle)
    const observer = new IntersectionObserver((entries)=>{ entries.forEach(e=>{ if(e.isIntersecting) e.target.classList.add('visible'); }); }, { threshold: .14 });
    qsa('.reveal').forEach(n=> observer.observe(n));

    // collapse toggle for each card header's chevron
    document.addEventListener('click', (ev)=>{
        const btn = ev.target.closest('[data-toggle-collapse]');
        if(btn){ const card = btn.closest('.card-min'); if(!card) return; const body = card.querySelector('.card-body'); const icon = btn.querySelector('i'); if(!body) return; const isHidden = body.style.display === 'none'; body.style.display = isHidden ? '' : 'none'; if(icon) icon.classList.toggle('fa-chevron-up', isHidden); icon && icon.classList.toggle('fa-chevron-down', !isHidden); btn.setAttribute('aria-expanded', isHidden ? 'true' : 'false'); }
    });

    // keyboard shortcut to focus search (/) and quick add (n)
    document.addEventListener('keydown', (e)=>{
        if(e.key === '/' && document.activeElement.tagName.toLowerCase() !== 'input'){ e.preventDefault(); const sq = qs('#global-search'); if(sq) sq.focus(); }
        if(e.key === 'n' && document.activeElement.tagName.toLowerCase() !== 'input'){ const firstAdd = document.querySelector('.add-task-form .form-control'); if(firstAdd){ firstAdd.focus(); } }
    });

    // client-side search (simple): filter visible cards/tasks by text
    const searchInput = qs('#global-search');
    searchInput?.addEventListener('input', (e)=>{
        const v = e.target.value.trim().toLowerCase(); qsa('.card-min').forEach(card=>{
            const name = (card.getAttribute('data-list-name') || '').toLowerCase(); const tasks = Array.from(card.querySelectorAll('.task-name')).map(n=>n.textContent.toLowerCase()).join(' ');
            const match = !v || name.includes(v) || tasks.includes(v);
            card.style.display = match ? '' : 'none';
        });
    });

    // animate counters: disable on small/touch devices to reduce motion
    qsa('[data-count-target]').forEach(el=>{
        const target = parseInt(el.textContent.trim()) || 0;
        const prefersReduced = ('matchMedia' in window && (window.matchMedia('(prefers-reduced-motion: reduce)').matches));
        const isTouch = ('matchMedia' in window && window.matchMedia('(hover: none)').matches) || window.innerWidth < 576;
        if(prefersReduced || isTouch){ el.textContent = target; return; }
        let n=0; const step = Math.max(1, Math.round(target/16)); const iv = setInterval(()=>{ n += step; if(n >= target){ el.textContent = target; clearInterval(iv); } else el.textContent = n; }, 22);
    });

    // ask permission for notification on first interaction
    document.addEventListener('click', askPermission, { once:true });
    document.addEventListener('keydown', askPermission, { once:true });

    window.addEventListener('load', ()=>{ checkDeadlines(); setInterval(checkDeadlines, 60*1000); });

})();
</script>

@endsection
