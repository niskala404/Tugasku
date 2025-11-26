@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <!-- Judul Halaman -->
        <div class="mb-4">
            <h1 class="fw-bold" style="font-size: 30px;">
                <i class="fas fa-plus-circle text-primary"></i> Buat Daftar Tugas Baru
            </h1>
            <p class="text-muted mt-1">Atur aktivitas kamu dengan membuat daftar baru</p>
        </div>

        <!-- Card Form -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-4">

                <form action="{{ route('lists.store') }}" method="POST">
                    @csrf

                    <!-- Input Nama -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-tag text-primary"></i> Nama Daftar
                        </label>

                        <input 
                            type="text"
                            name="name"
                            class="form-control form-control-lg rounded-3"
                            placeholder="Contoh: Tugas Kampus, Belanja Bulanan, Target Tahun Ini"
                            value="{{ old('name') }}"
                            required
                            autofocus
                        >

                        @error('name')
                        <small class="text-danger d-block mt-2">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </small>
                        @enderror
                    </div>

                    <!-- Input Deadline -->
                    <div class="mb-4">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt text-primary"></i> Tanggal Deadline
                        </label>

                        <input 
                            type="date"
                            name="due_date"
                            class="form-control form-control-lg rounded-3"
                            value="{{ old('due_date') }}"
                            required
                        >

                        @error('due_date')
                        <small class="text-danger d-block mt-2">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </small>
                        @enderror
                    </div>

                    <!-- Tombol -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-4 rounded-3 shadow-sm">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                        <a href="{{ route('lists.index') }}" 
                           class="btn btn-outline-secondary btn-lg px-4 rounded-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>

                </form>
            </div>
        </div>

        <!-- Tips -->
        <div class="card mt-4 shadow-sm border-0 rounded-3" style="border-left: 4px solid #0d6efd;">
            <div class="card-body">
                <h5 class="fw-bold mb-3">
                    <i class="fas fa-lightbulb text-warning"></i> Tips
                </h5>

                <ul class="mb-0" style="line-height: 1.8;">
                    <li>Gunakan nama daftar yang singkat dan jelas</li>
                    <li>Deadline membantu kamu mengatur prioritas</li>
                    <li>Jadikan reminder untuk tugas yang penting</li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
