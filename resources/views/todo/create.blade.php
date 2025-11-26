@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">

        <!-- Judul Halaman -->
        <div class="mb-4">
            <h1 class="page-title fw-bold" style="font-size: 28px;">
                <i class="fas fa-plus-circle text-primary"></i> Buat Daftar Tugas Baru
            </h1>
            <p class="text-muted">Atur aktivitas kamu dengan membuat daftar baru</p>
        </div>

        <!-- Card Form -->
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('lists.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-tag text-primary"></i> Nama Daftar
                        </label>

                        <input 
                            type="text" 
                            name="name" 
                            class="form-control form-control-lg rounded-3" 
                            value="{{ old('name') }}"
                            placeholder="Contoh: Tugas Kampus, Belanja Bulanan, Target Tahun Ini"
                            required autofocus>

                        @error('name')
                        <small class="text-danger d-block mt-2">
                            <i class="fas fa-exclamation-circle"></i> {{ $message }}
                        </small>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary btn-lg px-4 rounded-3">
                            <i class="fas fa-save"></i> Simpan
                        </button>

                        <a href="{{ route('lists.index') }}" class="btn btn-outline-secondary btn-lg px-4 rounded-3">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tips -->
        <div class="card mt-4 shadow-sm border-0" style="border-left: 4px solid #0d6efd;">
            <div class="card-body">
                <h5 class="card-title fw-bold">
                    <i class="fas fa-lightbulb text-warning"></i> Tips
                </h5>

                <ul class="mb-0">
                    <li>Gunakan nama daftar yang jelas dan mudah diingat</li>
                    <li>Kamu bisa menambahkan tugas setelah daftar dibuat</li>
                    <li>Gabungkan tugas-tugas yang berkaitan dalam satu daftar</li>
                </ul>
            </div>
        </div>

    </div>
</div>
@endsection
