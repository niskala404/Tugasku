@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto p-6">

    <!-- Header -->
    <div class="mb-6 text-center">
        <h1 class="text-3xl fw-bold text-primary">
            <i class="fas fa-user-circle me-2"></i> Profil Pengguna
        </h1>
        <p class="text-muted">Informasi akun dan data profil Anda</p>
    </div>

    <!-- Card Profil -->
    <div class="p-5 rounded-4 shadow-sm border"
         style="background: #f8fbff; border-color:#d2e6ff;">
         
        <div class="d-flex align-items-center gap-3 mb-4">
            <div class="rounded-circle d-flex justify-content-center align-items-center"
                style="width:70px; height:70px; background:#e6f1ff; border:2px solid #b2d3ff;">
                <i class="fas fa-user fa-2x text-primary"></i>
            </div>

            <div>
                <h2 class="mb-1 fw-bold" style="color:#0056c7;">{{ $user->name }}</h2>
                <span class="badge bg-primary px-3 py-2" style="font-size: 0.9rem;">
                    {{ $profile->status ? ucfirst($profile->status) : 'Status belum diisi' }}
                </span>
            </div>
        </div>

        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <strong>Email:</strong> {{ $user->email }}
            </li>
            <li class="list-group-item">
                <strong>Umur:</strong> {{ $profile->age ?? 'Belum diisi' }}
            </li>

            @if(!empty($profile))
                @switch($profile->status)

                    @case('sekolah')
                        <li class="list-group-item">
                            <strong>Tipe Sekolah:</strong> 
                            <span class="badge bg-info">{{ $profile->school_type ?? '-' }}</span>
                        </li>

                        @if($profile->school_type === 'SMK')
                            <li class="list-group-item">
                                <strong>Jurusan:</strong> {{ $profile->major ?? '-' }}
                            </li>
                        @endif
                        @break

                    @case('kuliah')
                        <li class="list-group-item">
                            <strong>Nama Kampus:</strong> {{ $profile->college_name ?? '-' }}
                        </li>
                        <li class="list-group-item">
                            <strong>Jurusan:</strong> {{ $profile->major ?? '-' }}
                        </li>
                        @break

                    @case('kerja')
                        <li class="list-group-item">
                            <strong>Perusahaan:</strong> {{ $profile->company ?? '-' }}
                        </li>
                        @break
                @endswitch
            @endif
        </ul>
    </div>

    <!-- Tombol edit -->
    <div class="text-center mt-4">
        <a href="{{ route('profile.edit') }}" class="btn btn-primary px-4 py-2 rounded-3">
            <i class="fas fa-edit me-1"></i> Edit Profil
        </a>
    </div>

</div>
@endsection
