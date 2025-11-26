{{-- resources/views/profile/create.blade.php --}}
<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Buat Profile — Agenda Kerja</title>

  {{-- Tailwind CDN (quick dev) --}}
  <script src="https://cdn.tailwindcss.com"></script>

  {{-- Alpine --}}
  <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

  <style>
    [x-cloak] { display: none !important; }
  </style>
</head>
<body class="bg-blue-50 min-h-screen">

  <!-- Simple header (guest-safe) -->
  <header class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
      <div class="flex items-center gap-4">
        <img src="{{ asset('AGENDA KERJA.png') }}" alt="Logo" class="h-14 w-auto">
        <a href="{{ url('/') }}" class="text-black font-semibold">AGENDA KERJA</a>
      </div>

      <nav class="flex items-center gap-3">
        <a href="{{ route('login') }}" class="text-black/80 hover:text-black text-sm">Login</a>
        <a href="{{ route('register') }}" class="text-black/80 hover:text-black text-sm">Register</a>
      </nav>
    </div>
  </header>

  <!-- Page content -->
  <main class="py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
      <div class="bg-white shadow rounded-2xl overflow-hidden grid grid-cols-1 lg:grid-cols-2">
        <!-- Left: Form -->
        <section class="p-8 lg:p-10">
          <h1 class="text-2xl font-semibold text-black">Buat Profile</h1>
          <p class="mt-1 text-sm text-black/70">Lengkapi data supaya pengalamanmu di app lebih personal. (Langkah terakhir setelah registrasi)</p>

          <div x-data="profileForm()" x-init="init()" x-cloak class="mt-6 space-y-6">
            <!-- Progress -->
            <div class="flex items-center gap-4">
              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-semibold"
                     :class="step === 1 ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700'">1</div>
                <div class="text-sm">
                  <div class="font-medium text-black">Pilih Status</div>
                  <div class="text-xs text-black/50">Sekolah / Kuliah / Kerja</div>
                </div>
              </div>

              <div class="h-px flex-1 bg-black/10"></div>

              <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-semibold"
                     :class="step === 2 ? 'bg-blue-600 text-white' : 'bg-blue-100 text-blue-700'">2</div>
                <div class="text-sm">
                  <div class="font-medium text-black">Detail & Umur</div>
                  <div class="text-xs text-black/50">Isi jurusan / perusahaan / kampus</div>
                </div>
              </div>
            </div>

            <!-- Step 1 -->
            <div x-show="step === 1" x-transition>
              <label class="block text-sm font-medium text-black">Pilih Status</label>
              <select x-model="status" class="mt-2 block w-full rounded-lg border border-black/20 p-3 focus:ring-2 focus:ring-blue-200">
                <option value="">-- Pilih --</option>
                <option value="sekolah">Sekolah</option>
                <option value="kuliah">Kuliah</option>
                <option value="kerja">Kerja</option>
              </select>
              <p x-show="errors.status" class="mt-1 text-xs text-red-600" x-text="errors.status"></p>

              <div class="flex justify-end mt-6">
                <button type="button" @click="goNext()" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg shadow">
                  Next
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
              </div>
            </div>

            <!-- Step 2 -->
            <div x-show="step === 2" x-transition class="space-y-4">
              <!-- Sekolah -->
              <template x-if="status === 'sekolah'">
                <div class="space-y-4">
                  <label class="block text-sm font-medium text-black">Jenis Sekolah</label>
                  <select x-model="school_type" class="block w-full rounded-lg border border-black/20 p-3 focus:ring-2 focus:ring-blue-200">
                    <option value="">-- Pilih --</option>
                    <option value="SMA">SMA</option>
                    <option value="SMK">SMK</option>
                  </select>
                  <p x-show="errors.school_type" class="mt-1 text-xs text-red-600" x-text="errors.school_type"></p>

                  <div x-show="school_type === 'SMK'">
                    <label class="block text-sm font-medium text-black">Jurusan (SMK)</label>
                    <input x-model="major" type="text" placeholder="Contoh: Teknik Komputer Jaringan" class="block w-full rounded-lg border border-black/20 p-3 focus:ring-2 focus:ring-blue-200">
                    <p x-show="errors.major" class="mt-1 text-xs text-red-600" x-text="errors.major"></p>
                  </div>
                </div>
              </template>

              <!-- Kuliah -->
              <template x-if="status === 'kuliah'">
                <div class="space-y-4">
                  <label class="block text-sm font-medium text-black">Nama Kampus</label>
                  <input x-model="college_name" type="text" placeholder="Contoh: Universitas XYZ" class="block w-full rounded-lg border border-black/20 p-3 focus:ring-2 focus:ring-blue-200">
                  <p x-show="errors.college_name" class="mt-1 text-xs text-red-600" x-text="errors.college_name"></p>

                  <label class="block text-sm font-medium text-black">Jurusan / Program Studi</label>
                  <input x-model="major" type="text" placeholder="Contoh: Teknik Informatika" class="block w-full rounded-lg border border-black/20 p-3 focus:ring-2 focus:ring-blue-200">
                  <p x-show="errors.major" class="mt-1 text-xs text-red-600" x-text="errors.major"></p>
                </div>
              </template>

              <!-- Kerja -->
              <template x-if="status === 'kerja'">
                <div class="space-y-4">
                  <label class="block text-sm font-medium text-black">Nama Perusahaan</label>
                  <input x-model="company" type="text" placeholder="Contoh: PT. Contoh" class="block w-full rounded-lg border border-black/20 p-3 focus:ring-2 focus:ring-blue-200">
                  <p x-show="errors.company" class="mt-1 text-xs text-red-600" x-text="errors.company"></p>
                </div>
              </template>

              <!-- Umur -->
              <div>
                <label class="block text-sm font-medium text-black">Umur</label>
                <input x-model.number="age" type="number" min="1" max="120" placeholder="18" class="block w-32 rounded-lg border border-black/20 p-3 focus:ring-2 focus:ring-blue-200">
                <p x-show="errors.age" class="mt-1 text-xs text-red-600" x-text="errors.age"></p>
              </div>

              <div class="flex justify-between mt-6">
                <button type="button" @click="step = 1" class="px-4 py-2 rounded-lg border border-black/20">Back</button>

                <button type="button" @click="submitProfile()" class="px-4 py-2 bg-green-600 text-white rounded-lg shadow" :class="loading ? 'opacity-70 cursor-wait' : ''" :disabled="loading">
                  <span x-text="loading ? 'Menyimpan...' : 'Submit'"></span>
                </button>
              </div>

              <p x-show="globalError" class="mt-2 text-sm text-red-600" x-text="globalError"></p>
            </div>
          </div>
        </section>

        <!-- Right: Preview + tips -->
        <aside class="bg-blue-600 text-white p-8 lg:p-10">
          <h3 class="text-lg font-semibold">Preview Profile</h3>
          <p class="text-sm text-white/90 mt-1">Ringkasan data yang akan tersimpan.</p>

          <div class="mt-6 space-y-3 bg-white/10 p-4 rounded-lg">
            <div class="text-xs text-white/80">Status</div>
            <div class="text-lg font-medium text-white" x-text="statusDisplay() || '— Belum dipilih —'"></div>

            <div class="text-xs text-white/80 mt-3">Detail</div>
            <div class="space-y-1 text-white">
              <div x-show="status === 'sekolah'" x-text="school_type ? school_type + (major ? ' — ' + major : '') : '—'"></div>
              <div x-show="status === 'kuliah'" x-text="college_name ? college_name + (major ? ' — ' + major : '') : '—'"></div>
              <div x-show="status === 'kerja'" x-text="company ? company : '—'"></div>
            </div>

            <div class="text-xs text-white/80 mt-3">Umur</div>
            <div class="text-lg font-semibold text-white" x-text="age ? age + ' tahun' : '—'"></div>

            <hr class="my-5 border-white/20">

            <div class="text-sm text-white/90">Tips</div>
            <ul class="mt-3 text-sm space-y-2 list-disc list-inside text-white/80">
              <li>Pastikan data sesuai KTP / kartu pelajar.</li>
              <li>Nama kampus/perusahaan sebaiknya lengkap.</li>
              <li>Umur hanya dipakai untuk personalisasi.</li>
            </ul>
          </div>
        </aside>
      </div>
    </div>
  </main>

  {{-- Alpine data & fetch logic --}}
  <script>
  function profileForm() {
    return {
      step: 1,
      status: '',
      school_type: '',
      major: '',
      college_name: '',
      company: '',
      age: '',
      errors: {},
      globalError: '',
      loading: false,

      init() {
        if (this.status) this.step = 2;
      },

      goNext() {
        this.errors = {};
        this.globalError = '';
        if (!this.status) { this.errors.status = 'Pilih status dulu.'; return; }
        this.step = 2;
        window.scrollTo({ top: 0, behavior: 'smooth' });
      },

      statusDisplay() {
        if (!this.status) return '';
        return this.status.charAt(0).toUpperCase() + this.status.slice(1);
      },

      async submitProfile() {
        this.loading = true;
        this.errors = {};
        this.globalError = '';

        const payload = {
          status: this.status || null,
          school_type: this.school_type || null,
          major: this.major || null,
          college_name: this.college_name || null,
          company: this.company || null,
          age: this.age || null,
        };

        try {
          const res = await fetch("{{ route('profile.store') }}", {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Accept': 'application/json'
            },
            body: JSON.stringify(payload)
          });

          this.loading = false;

          if (res.status === 422) {
            const data = await res.json();
            if (data.errors) {
              this.errors = Object.fromEntries(Object.entries(data.errors).map(([k,v]) => [k, v[0]]));
            } else if (data.message) {
              this.globalError = data.message;
            }
            return;
          }

          if (!res.ok) {
            const data = await res.json().catch(() => ({}));
            this.globalError = data.message || 'Gagal menyimpan profile.';
            return;
          }

          // success -> redirect
          window.location.href = "{{ route('dashboard') }}";
        } catch(e) {
          this.loading = false;
          console.error(e);
          this.globalError = 'Terjadi kesalahan jaringan. Coba lagi.';
        }
      }
    }
  }
  </script>
</body>
</html>
