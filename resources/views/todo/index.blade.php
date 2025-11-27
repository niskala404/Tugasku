@extends('layouts.app')

@section('content')
@php
    use Carbon\Carbon;
@endphp

<style>
:root{
    --bg-page: #f8fafc;
    --panel: #ffffff;
    --muted: #6b7280;
    --text: #0f172a;
    --accent-teal: #06b6d4;
    --accent-teal-deep: #0e7490;
    --accent-orange: #fb923c;
    --success: #10b981;
    --card-radius: 12px;
    --glass: rgba(15,23,42,0.02);
    --accent-teal-light: rgba(6,182,212,0.10);
    --dark-percent-bg: #0f172a; /* warna gelap untuk persen */
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

/* main single-column area */
.main-column { display:block; margin-top:1rem; }
@media (min-width:992px){
    .main-column { max-width: 980px; margin-left:auto; margin-right:auto; }
}

/* cards */
.card-min { background: var(--panel); border-radius: var(--card-radius); overflow:hidden; color:var(--text); border:1px solid rgba(15,23,42,0.04); box-shadow: 0 6px 18px rgba(2,6,23,0.03); transition: transform .18s ease, box-shadow .18s ease; margin-bottom:1rem; }
@media (hover: hover){ .card-min:hover{ transform: translateY(-4px); box-shadow: 0 12px 28px rgba(2,6,23,0.04); } }

/* header layout - we separate title area and meta area (meta hidden when collapsed) */
.card-header-compact { display:flex; justify-content:space-between; align-items:center; padding:.95rem .9rem; background:transparent; border-bottom:1px solid rgba(15,23,42,0.03); gap:1rem; }
.card-header-compact .header-title { display:flex; flex-direction:column; gap:.18rem; }
.card-header-compact .header-actions { display:flex; gap:.6rem; align-items:center; }
.card-header-compact .header-meta { display:flex; gap:.6rem; align-items:center; }

/* collapse/expand button */
.btn-collapse {
    background: transparent;
    border: 1px solid rgba(15,23,42,0.06);
    padding: .36rem .5rem;
    border-radius: 8px;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    cursor: pointer;
}
.btn-collapse:focus { outline: 3px solid rgba(6,182,212,0.12); }

/* body */
.card-body { padding:.6rem .9rem; }

/* collapsed state: hide meta + body, show only title */
.card-min.collapsed .card-body { display:none; }
.card-min.collapsed .card-header-compact .header-meta { display:none; }
.card-min.collapsed .card-header-compact .small.text-muted { display:none; }

/* reveal animation */
.reveal { opacity:0; transform: translateY(8px) scale(.997); transition: opacity .32s ease, transform .32s cubic-bezier(.2,.8,.2,1); }
.reveal.visible{ opacity:1; transform: translateY(0) scale(1); }

/* list & tasks */
.list-group-item { background:transparent; border:none; padding:.45rem 0; color:var(--text); display:flex; align-items:center; justify-content:space-between; }
.list-group-item.task-done-item { background: var(--accent-teal-light); border-radius:8px; padding:.5rem; transition: background .18s ease; }
.task-name { max-width:72%; display:inline-block; vertical-align:middle; color:var(--text); transition: color .12s ease; }
.task-done { color:var(--muted); text-decoration:line-through; }

/* dark percent badge */
.badge-dark-percent {
    background: linear-gradient(180deg, rgba(255,255,255,0.04), var(--dark-percent-bg));
    color: #fff;
    padding: .42rem .6rem;
    border-radius: 10px;
    font-weight:800;
    font-size:0.9rem;
    min-width:56px;
    text-align:center;
    box-shadow: 0 6px 18px rgba(15,23,42,0.12);
    transition: transform .16s ease, background .18s ease;
    letter-spacing: .6px;
}
.badge-dark-percent.update { transform: scale(1.06); }
.badge-dark-percent .small-label { display:block; font-weight:600; font-size:.7rem; opacity:.85; }

/* other UI bits kept same */
.filter-pills .nav-link { border-radius:999px; padding:.32rem .65rem; color:var(--muted); background: transparent; border:1px solid rgba(15,23,42,0.04); font-weight:700; }
.filter-pills .nav-link.active{ background: var(--accent-teal); color: #fff; box-shadow: 0 8px 20px rgba(6,182,212,0.05); border-color: rgba(6,182,212,0.12); }

.link-text { color: var(--accent-teal); font-weight:700; text-decoration: none; }
.link-text-accent { color: var(--accent-orange); font-weight:700; text-decoration:none; }

.badge { border-radius:999px; padding:.26rem .56rem; font-weight:700; font-size:.78rem; }
.badge-blue { background: var(--accent-teal); color:#fff; }
.badge-orange { background: var(--accent-orange); color:#fff; }

.deadline-badge { display:inline-block; padding:.26rem .56rem; border-radius:8px; font-weight:700; color:#fff; font-size:.78rem; margin-top:.28rem; }
.deadline-safe { background: var(--accent-teal); }
.deadline-soon { background: var(--accent-orange); }
.deadline-over { background: #ef4444; }

.btn { border-radius:10px; font-weight:700; transition: transform .08s ease; }
.btn:active{ transform: translateY(1px) scale(.997); }
.btn-primary { background: var(--accent-teal); color: #fff; border: none; box-shadow: 0 6px 12px rgba(6,182,212,0.05); }
.btn-primary:hover{ background: var(--accent-teal-deep); }

.input-group{ border-radius:10px; overflow:hidden; border:1px solid rgba(15,23,42,0.04); background: #fbfdff; display:flex; }
.form-control{ background: transparent; border: none; color: var(--text); padding:.6rem .8rem; box-shadow:none; }
.form-control::placeholder{ color: rgba(15,23,42,0.35); }

/* empty state */
.empty-state { text-align:center; padding:1.1rem; color:var(--muted); }
.empty-state i { font-size:1.8rem; color: rgba(15,23,42,0.06); display:block; margin-bottom:.5rem; }

/* toast */
#inpage-toast { position: fixed; right: 14px; bottom: 14px; background: rgba(2,6,23,0.95); color: #fff; padding: 10px 14px; border-radius: 10px; z-index: 9999; display: none; transform-origin: right bottom; }

/* responsive tweaks */
@media (max-width:768px){
    .card-header-compact{ padding:.6rem .8rem; gap:.6rem; }
    .task-name{ max-width:60%; }
}
@media (max-width:576px){
    .task-name{ max-width:100%; }
    .form-control{ padding:.8rem; }
    .btn{ padding:.55rem .6rem; font-size:.95rem; }
    .badge{ font-size:.72rem; padding:.22rem .46rem }
}
</style>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<div class="content-wrap container">
    <div class="dashboard">
        <div class="header-area">
            <div>
                <h2 class="page-title">Tugas Saya</h2>
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

        <div class="main-column mt-3">
            <div class="tab-content">
                {{-- ALL TAB --}}
                <div class="tab-pane fade show active reveal" id="all">
                    @foreach($lists as $list)
                        @php
                            $due = $list->due_date ? Carbon::parse($list->due_date) : null;
                            $daysLeft = $due ? now()->diffInDays($due, false) : null;
                            $total = $list->tasks->count();
                            $done = $list->tasks->where('is_done', true)->count();
                            $percent = $total ? round(($done / $total) * 100) : 0;
                        @endphp

                        {{-- default: collapsed (only title visible) --}}
                        <div class="card card-min collapsed" data-list-id="{{ $list->id }}" data-due="{{ $due ? $due->toDateString() : '' }}" data-list-name="{{ e($list->name) }}">
                            <div class="card-header-compact" role="button" aria-expanded="false" tabindex="0">
                                <div class="header-title">
                                    <strong>{{ $list->name }}</strong>
                                    <div class="small text-muted">{{ $total }} tugas</div>
                                </div>

                                <div class="header-actions">
                                    <div class="header-meta">
                                        <!-- percent + deadline + actions (hidden while collapsed) -->
                                        <span class="badge-dark-percent" data-percent-target="{{ $percent }}">
                                            <span class="small-label">Selesai</span>
                                            <strong class="percent-text">{{ $percent }}%</strong>
                                        </span>

                                        @if($due)
                                            @if($daysLeft < 0)
                                                <div class="deadline-badge deadline-over">Lewat: {{ $due->format('d M Y') }}</div>
                                            @elseif($daysLeft <= 2)
                                                <div class="deadline-badge deadline-soon">Deadline: {{ $due->format('d M Y') }}</div>
                                            @else
                                                <div class="deadline-badge deadline-safe">Deadline: {{ $due->format('d M Y') }}</div>
                                            @endif
                                        @endif

                                        <a href="{{ route('lists.edit', $list->id) }}" class="link-text-accent">Edit</a>
                                        <form action="{{ route('lists.destroy', $list->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Yakin menghapus list ini dan semua tugasnya?')">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="Hapus list">Hapus</button>
                                        </form>
                                    </div>

                                    <!-- collapse/expand button (row control) -->
                                    <button type="button" class="btn-collapse" data-toggle-collapse aria-expanded="false" title="Buka / Tutup daftar">
                                        <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                        <span class="sr-only">Toggle</span>
                                    </button>
                                </div>
                            </div>

                            {{-- hidden body until user clicks header or chevron --}}
                            <div class="card-body" aria-hidden="true">
                                @if($list->tasks->count() > 0)
                                    <ul class="list-group list-group-flush mb-3">
                                        @foreach($list->tasks as $task)
                                            <li class="list-group-item d-flex justify-content-between align-items-center list-task-row {{ $task->is_done ? 'task-done-item' : '' }}" data-task-id="{{ $task->id }}">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="task-name {{ $task->is_done ? 'task-done' : '' }}">{{ $task->name }}</div>
                                                </div>

                                                <div class="d-flex gap-2">
                                                    @if(!$task->is_done)
                                                        <form action="{{ route('tasks.done', $task->id) }}" method="POST" style="display:inline" class="mark-done-form" data-sound="/sound/selesai.mp3">
                                                            @csrf @method('PUT')
                                                            <button class="btn btn-sm btn-outline-success" title="Tandai selesai">Selesai</button>
                                                        </form>
                                                    @endif
                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger" title="Hapus tugas">Hapus</button>
                                                    </form>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <div class="empty-state mb-3"><i class="fas fa-inbox"></i><p class="mb-0">Belum ada tugas di list ini.</p></div>
                                @endif

                                {{-- ADD TASK FORM INSIDE EACH LIST CARD --}}
                                <form action="{{ route('tasks.store', $list->id) }}" method="POST" class="mt-2 add-task-form">
                                    @csrf
                                    <div class="input-group">
                                        <input type="text" name="name" class="form-control" placeholder="Tambah tugas baru..." required>
                                        <button class="btn btn-primary" type="submit">Tambah</button>
                                    </div>
                                    @error('name') <small class="text-danger d-block mt-2">{{ $message }}</small> @enderror
                                </form>
                            </div>
                        </div>
                    @endforeach

                    {{-- create list quick link --}}
                    <div class="card card-min reveal">
                        <div class="card-body text-center">
                            <a href="{{ route('lists.create') }}" class="link-text">Buat List Baru</a>
                        </div>
                    </div>
                </div>

                {{-- PENDING TAB (collapsed by default as well) --}}
                <div class="tab-pane fade reveal" id="pending">
                    @php $hasPending = false; @endphp
                    @foreach($lists as $list)
                        @php $pending = $list->tasks->where('is_done', false); @endphp
                        @if($pending->count() > 0)
                            @php $hasPending = true; @endphp
                            <div class="card card-min collapsed" data-list-id="{{ $list->id }}">
                                <div class="card-header-compact" role="button" aria-expanded="false" tabindex="0">
                                    <div class="header-title">
                                        <strong>{{ $list->name }}</strong>
                                        <div class="small text-muted">{{ $pending->count() }} belum</div>
                                    </div>
                                    <div class="header-actions">
                                        <div class="header-meta">
                                            <span class="badge badge-orange">{{ $pending->count() }} belum</span>
                                            <a href="{{ route('lists.edit', $list->id) }}" class="link-text-accent">Edit</a>
                                        </div>
                                        <button type="button" class="btn-collapse" data-toggle-collapse aria-expanded="false" title="Buka / Tutup daftar">
                                            <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                            <span class="sr-only">Toggle</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body" aria-hidden="true">
                                    <ul class="list-group list-group-flush mb-3">
                                        @foreach($pending as $task)
                                            <li class="list-group-item d-flex justify-content-between align-items-center list-task-row" data-task-id="{{ $task->id }}">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="task-name">{{ $task->name }}</div>
                                                </div>
                                                <div class="d-flex gap-2">
                                                    <form action="{{ route('tasks.done', $task->id) }}" method="POST" style="display:inline" class="mark-done-form" data-sound="/sound/selesai.mp3">
                                                        @csrf @method('PUT')
                                                        <button class="btn btn-sm btn-outline-success">Selesai</button>
                                                    </form>
                                                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                        @csrf @method('DELETE')
                                                        <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                                    </form>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <form action="{{ route('tasks.store', $list->id) }}" method="POST" class="mt-2 add-task-form">
                                        @csrf
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control" placeholder="Tambah tugas baru..." required>
                                            <button class="btn btn-primary" type="submit">Tambah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @unless($hasPending)
                        <div class="empty-state reveal">
                            <i class="fas fa-check-double"></i>
                            <h5 class="mt-2">Tidak ada tugas yang belum selesai</h5>
                        </div>
                    @endunless
                </div>

                {{-- COMPLETED TAB --}}
                <div class="tab-pane fade reveal" id="completed">
                    @php $hasCompleted = false; @endphp
                    @foreach($lists as $list)
                        @php $doneTasks = $list->tasks->where('is_done', true); @endphp
                        @if($doneTasks->count() > 0)
                            @php $hasCompleted = true; @endphp
                            <div class="card card-min collapsed" data-list-id="{{ $list->id }}">
                                <div class="card-header-compact" role="button" aria-expanded="false" tabindex="0">
                                    <div class="header-title">
                                        <strong>{{ $list->name }}</strong>
                                        <div class="small text-muted">{{ $doneTasks->count() }} selesai</div>
                                    </div>
                                    <div class="header-actions">
                                        <div class="header-meta">
                                            <span class="badge badge-blue">{{ $doneTasks->count() }} selesai</span>
                                            <a href="{{ route('lists.edit', $list->id) }}" class="link-text-accent">Edit</a>
                                        </div>
                                        <button type="button" class="btn-collapse" data-toggle-collapse aria-expanded="false" title="Buka / Tutup daftar">
                                            <i class="fas fa-chevron-down" aria-hidden="true"></i>
                                            <span class="sr-only">Toggle</span>
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body" aria-hidden="true">
                                    <ul class="list-group list-group-flush">
                                        @foreach($doneTasks as $task)
                                            <li class="list-group-item d-flex align-items-center justify-content-between task-done-item list-task-row" data-task-id="{{ $task->id }}">
                                                <div class="d-flex align-items-center gap-2">
                                                    <div class="task-name task-done">{{ $task->name }}</div>
                                                </div>
                                                <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus tugas ini?')">
                                                    @csrf @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>

                                    <form action="{{ route('tasks.store', $list->id) }}" method="POST" class="mt-2 add-task-form">
                                        @csrf
                                        <div class="input-group">
                                            <input type="text" name="name" class="form-control" placeholder="Tambah tugas baru..." required>
                                            <button class="btn btn-primary" type="submit">Tambah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    @endforeach

                    @unless($hasCompleted)
                        <div class="empty-state reveal">
                            <i class="fas fa-tasks"></i>
                            <h5 class="mt-2">Belum ada tugas selesai</h5>
                        </div>
                    @endunless
                </div>
            </div>
        </div>

        @else
            <div class="card card-min">
                <div class="card-body text-center">
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
<audio id="done-audio" preload="auto"><source src="/sound/selesai.mp3" type="audio/mpeg"></audio>

<script>
(function(){
    const SOON_DAYS = 2;
    const qs = s => document.querySelector(s);
    const qsa = s => [...document.querySelectorAll(s)];

    function askPermission(){ if(!('Notification' in window)) return; if(Notification.permission === 'default') Notification.requestPermission(); }
    function showBrowserNotification(title, body){ if(!('Notification' in window)) return false; if(Notification.permission === 'granted'){ try{ new Notification(title, { body, icon: '/favicon.ico' }); return true }catch(e){ return false } } return false; }

    function showInPage(text){ const t = qs('#inpage-toast'); if(!t) return; t.textContent = text; t.style.display = 'block'; clearTimeout(t._h); t._h = setTimeout(()=>{ t.style.display = 'none'; }, 5000); }

    function playSound(src){
        if(src){
            try{
                const a = document.createElement('audio');
                a.src = src;
                a.preload = 'auto';
                const p = a.play();
                if(p && p.catch) p.catch(()=> {});
                return;
            }catch(e){
                return;
            }
        }
        const audio = qs('#notif-audio');
        if(audio && (audio.currentSrc || audio.src)){
            const p = audio.play();
            if(p && p.catch) p.catch(()=> {});
        }
    }

    function checkDeadlines(){ qsa('[data-due]').forEach(card=>{ const dueStr = card.getAttribute('data-due'); const id = card.getAttribute('data-list-id'); const name = card.getAttribute('data-list-name') || 'Daftar'; if(!dueStr) return; const due = new Date(dueStr + 'T00:00:00'); const now = new Date(); const diffMs = due - now; const diffDays = Math.floor(diffMs / (1000*60*60*24)); const keyOver = `todo_notify_over_${id}`; const keySoon = `todo_notify_soon_${id}`;
            if(diffDays < 0){ if(!localStorage.getItem(keyOver)){ const text = `${name} melewati deadline (${due.toLocaleDateString()}).`; const shown = showBrowserNotification('Deadline Lewat', text); if(!shown) showInPage(text); playSound('/sound/notif.mp3'); localStorage.setItem(keyOver,'1'); } }
            else if(diffDays <= SOON_DAYS){ if(!localStorage.getItem(keySoon)){ const text = `${name} akan deadline pada ${due.toLocaleDateString()}.`; const shown = showBrowserNotification('Deadline Mendekat', text); if(!shown) showInPage(text); playSound('/sound/notif.mp3'); localStorage.setItem(keySoon,'1'); } }
        }); }

    const observer = new IntersectionObserver((entries)=>{ entries.forEach(e=>{ if(e.isIntersecting) e.target.classList.add('visible'); }); }, { threshold: .14 });
    qsa('.reveal').forEach(n=> observer.observe(n));

    // compute percent & update badge for a card
    function computeAndSetPercent(card){
        if(!card) return;
        const total = card.querySelectorAll('li.list-task-row').length;
        const done = card.querySelectorAll('li.list-task-row.task-done-item').length;
        const pct = total ? Math.round((done / total) * 100) : 0;
        const badge = card.querySelector('.badge-dark-percent');
        if(badge){
            const textEl = badge.querySelector('.percent-text');
            if(textEl) textEl.textContent = `${pct}%`;
            badge.setAttribute('data-percent-target', pct);
            badge.classList.add('update');
            setTimeout(()=> badge.classList.remove('update'), 260);
        }
        // also update collapse-button icon if present (so percent doesn't affect it, but keep for completeness)
    }

    function initAllPercents(){
        qsa('.card-min[data-list-id]').forEach(computeAndSetPercent);
    }

    // toggle expand/collapse per card: clicking header toggles body & meta, and collapse button toggles too
    function initCardToggles(){
        qsa('.card-min').forEach(card=>{
            const header = card.querySelector('.card-header-compact');
            const collapseBtn = card.querySelector('[data-toggle-collapse]');
            if(!header) return;
            if(header._bound) return;
            header._bound = true;

            // helper to set chevron icon and aria-expanded on both header and button
            const setExpandedState = (isExpanded) => {
                if(isExpanded){
                    card.classList.remove('collapsed');
                    header.setAttribute('aria-expanded','true');
                    const body = card.querySelector('.card-body');
                    if(body) body.setAttribute('aria-hidden','false');
                    if(collapseBtn) { collapseBtn.setAttribute('aria-expanded','true'); const i = collapseBtn.querySelector('i'); if(i){ i.classList.remove('fa-chevron-down'); i.classList.add('fa-chevron-up'); } }
                } else {
                    card.classList.add('collapsed');
                    header.setAttribute('aria-expanded','false');
                    const body = card.querySelector('.card-body');
                    if(body) body.setAttribute('aria-hidden','true');
                    if(collapseBtn) { collapseBtn.setAttribute('aria-expanded','false'); const i = collapseBtn.querySelector('i'); if(i){ i.classList.remove('fa-chevron-up'); i.classList.add('fa-chevron-down'); } }
                }
            };

            // initial icon sync
            const initiallyCollapsed = card.classList.contains('collapsed');
            setExpandedState(!initiallyCollapsed ? true : false); // if not collapsed -> expanded icon; else collapsed icon

            const toggle = (expand) => {
                const isCollapsedNow = card.classList.contains('collapsed');
                if(typeof expand === 'boolean') {
                    setExpandedState(expand);
                } else {
                    setExpandedState(isCollapsedNow); // if collapsed -> expand, else collapse
                }
                // after toggling, recompute percent and init forms if expanded
                if(!card.classList.contains('collapsed')){
                    computeAndSetPercent(card);
                    initMarkDoneFormsForCard(card);
                    setTimeout(()=> card.scrollIntoView({behavior:'smooth', block:'center'}), 80);
                }
            };

            // header click toggles, but ignore clicks on action controls (links/buttons) inside header
            header.addEventListener('click', (e)=>{
                if(e.target.closest('.header-actions') || e.target.tagName.toLowerCase() === 'a' || e.target.closest('button')) return;
                toggle();
            });

            // keyboard accessibility on header (Enter / Space)
            header.addEventListener('keydown', (e)=>{
                if(e.key === 'Enter' || e.key === ' '){
                    e.preventDefault();
                    toggle();
                }
            });

            // collapse button explicit handler
            if(collapseBtn){
                collapseBtn.addEventListener('click', (e)=>{
                    e.stopPropagation();
                    toggle();
                });
                collapseBtn.addEventListener('keydown', (e)=>{
                    if(e.key === 'Enter' || e.key === ' '){
                        e.preventDefault();
                        e.stopPropagation();
                        toggle();
                    }
                });
            }
        });
    }

    // init mark-done forms but scoped to a card to avoid rebinding global ones repeatedly
    function initMarkDoneFormsForCard(card){
        if(!card) return;
        const forms = [...card.querySelectorAll('.mark-done-form')];
        forms.forEach(form=>{
            if(form._bound) return;
            form._bound = true;
            form.addEventListener('submit', function(e){
                try{
                    const li = form.closest('li.list-task-row');
                    const taskName = li ? (li.querySelector('.task-name')?.textContent?.trim() || 'Tugas') : 'Tugas';
                    const text = `${taskName} ditandai selesai.`;
                    const shown = showBrowserNotification('Tugas Selesai', text);
                    if(!shown) showInPage(text);
                    if(li){
                        li.classList.add('task-done-item');
                        const nameEl = li.querySelector('.task-name');
                        if(nameEl) nameEl.classList.add('task-done');
                        const doneBtn = form.querySelector('button');
                        if(doneBtn) doneBtn.style.display = 'none';
                        computeAndSetPercent(card);
                    }
                }catch(err){}
                const sound = form.getAttribute('data-sound') || '/sound/selesai.mp3';
                e.preventDefault();
                try{ playSound(sound); }catch(e){}
                setTimeout(()=>{ form.submit(); }, 150);
            });
        });
    }

    // global versions (run on load)
    function initMarkDoneForms(){
        qsa('.mark-done-form').forEach(f => {
            if(!f._bound){
                f.addEventListener('submit', function(e){
                    try{
                        const li = f.closest('li.list-task-row');
                        const taskName = li ? (li.querySelector('.task-name')?.textContent?.trim() || 'Tugas') : 'Tugas';
                        const text = `${taskName} ditandai selesai.`;
                        const shown = showBrowserNotification('Tugas Selesai', text);
                        if(!shown) showInPage(text);
                        if(li){
                            li.classList.add('task-done-item');
                            const nameEl = li.querySelector('.task-name');
                            if(nameEl) nameEl.classList.add('task-done');
                            const doneBtn = f.querySelector('button');
                            if(doneBtn) doneBtn.style.display = 'none';
                            const card = li.closest('.card-min[data-list-id]');
                            computeAndSetPercent(card);
                        }
                    }catch(err){}
                    const sound = f.getAttribute('data-sound') || '/sound/selesai.mp3';
                    e.preventDefault();
                    try{ playSound(sound); }catch(e){}
                    setTimeout(()=>{ f.submit(); }, 150);
                });
                f._bound = true;
            }
        });
    }

    // small helpers
    function showBrowserNotification(title, body){
        if(!('Notification' in window)) return false;
        if(Notification.permission === 'granted'){ try{ new Notification(title, { body, icon: '/favicon.ico' }); return true }catch(e){ return false } }
        return false;
    }

    // init everything
    window.addEventListener('load', ()=>{
        checkDeadlines();
        setInterval(checkDeadlines, 60*1000);
        initAllPercents();
        initCardToggles();
        initMarkDoneForms(); // bind any visible forms
    });

    // expose for debug
    window.todoInitMarkDoneForms = initMarkDoneForms;
    window.todoRecomputePercents = initAllPercents;
    window.todoInitCardToggles = initCardToggles;

})();
</script>

@endsection
