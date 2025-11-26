<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user();
        $profile = $user->profile;

        return view('profile.edit', compact('user','profile'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($request->hasAny(['name','email'])) {
            $validatedUser = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,'.$user->id,
            ]);

            $user->fill($validatedUser);
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            $user->save();
        }

        if ($request->has('status')) {
            $rules = [
                'status'=>'required|in:sekolah,kuliah,kerja',
                'age'=>'required|integer|min:1|max:120',
            ];

            $validator = Validator::make($request->all(), $rules);
            $validator->sometimes('school_type','required|in:SMA,SMK', fn($input)=> $input->status==='sekolah');
            $validator->sometimes('major','required|string|max:255', fn($input)=> ($input->status==='sekolah' && $input->school_type==='SMK') || $input->status==='kuliah');
            $validator->sometimes('college_name','required|string|max:255', fn($input)=> $input->status==='kuliah');
            $validator->sometimes('company','required|string|max:255', fn($input)=> $input->status==='kerja');
            $validator->validate();

            $user->profile()->updateOrCreate(['user_id'=>$user->id], $validator->validated());
        }

        return redirect()->route('profile.edit')->with('status','Profil diperbarui!');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password'=>['required','current_password'],
        ]);

        $user = $request->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
