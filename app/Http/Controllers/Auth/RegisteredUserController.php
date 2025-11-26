<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman registrasi.
     */
    public function create(): View
    {
        return view('auth.register'); // form: name, email, password
    }

    /**
     * Tangani request registrasi user.
     * Data user belum disimpan, tapi disimpan sementara di session untuk step profile.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // simpan password dalam bentuk hash supaya aman
        $validated['password'] = Hash::make($validated['password']);

        // simpan data sementara di session
        $request->session()->put('registration', $validated);

        // redirect ke halaman step profile
        return redirect()->route('profile.create')->with('success', 'Registrasi berhasil. Lengkapi profil Anda.');
    }

    /**
     * Tampilkan halaman step profile (step 2)
     */
    public function showProfileForm(Request $request): View
    {
        // pastikan ada data registrasi di session
        if (!$request->session()->has('registration')) {
            return redirect()->route('register')->withErrors('Harap registrasi terlebih dahulu.');
        }

        return view('profile.create'); // blade untuk step profile
    }

    /**
     * Simpan profile + buat user + auto login
     */
    public function storeProfile(Request $request): RedirectResponse
    {
        // pastikan ada data registrasi di session
        if (!$request->session()->has('registration')) {
            return redirect()->route('register')->withErrors('Harap registrasi terlebih dahulu.');
        }

        $registration = $request->session()->get('registration');

        // validasi profile
        $request->validate([
            'status' => 'required|in:sekolah,kuliah,kerja',
            'age' => 'required|integer|min:1|max:120',
            'school_type' => 'nullable|in:SMA,SMK',
            'major' => 'nullable|string|max:255',
            'college_name' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
        ]);

        // buat user baru
        $user = User::create($registration);

        // buat profile
        $user->profile()->create($request->only([
            'status', 'age', 'school_type', 'major', 'college_name', 'company'
        ]));

        // login otomatis
        Auth::login($user);

        // hapus session registrasi
        $request->session()->forget('registration');

        return redirect()->route('dashboard')->with('status', 'Registrasi berhasil dan profil lengkap!');
    }
}
