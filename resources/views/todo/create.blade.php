@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <!-- Header Section -->
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary rounded-3 p-3 me-3">
                    <i class="fas fa-plus-circle fa-lg text-white"></i>
                </div>
                <div>
                    <h1 class="h3 fw-bold mb-1">Buat Daftar Tugas Baru</h1>
                    <p class="text-muted mb-0">Atur aktivitas kamu dengan membuat daftar baru</p>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="card shadow-lg border-0 mb-4">
                <div class="card-body p-4">
                    <form action="{{ route('lists.store') }}" method="POST">
                        @csrf
                        
                        <!-- Form Inputs -->
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-tag text-primary me-1"></i> Nama Daftar
                                </label>
                                <input type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror"
                                    placeholder="Contoh: Tugas Kampus, Belanja Bulanan" value="{{ old('name') }}" required autofocus>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-12">
                                <label class="form-label fw-semibold text-dark">
                                    <i class="fas fa-calendar-alt text-primary me-1"></i> Tanggal Deadline
                                </label>
                                <input type="date" name="due_date" class="form-control form-control-lg @error('due_date') is-invalid @enderror"
                                    value="{{ old('due_date') }}" required>
                                @error('due_date')
                                    <div class="invalid-feedback d-block">
                                        <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg px-4 shadow-sm">
                                <i class="fas fa-save me-1"></i>Simpan
                            </button>
                            <a href="{{ route('lists.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                <i class="fas fa-arrow-left me-1"></i>Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips Card -->
            <div class="card bg-light border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <i class="fas fa-lightbulb text-warning fa-lg me-2"></i>
                       
                    </div>
                   
                </div>
            </div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .form-control-lg:focus {
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.15);
        border-color: #0d6efd;
    }
    .card {
        transition: transform 0.2s ease;
    }
    .card:hover {
        transform: translateY(-2px);
    }
</style>
@endpush