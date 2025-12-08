<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return view('pages.profile.index', [
            'user' => $user
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
        ];

        // 2. Jika role adalah 'user', Alamat & Telepon WAJIB
        // Jika Admin/Operator, buat NULLABLE (boleh kosong)
        if ($user->role === 'user') {
            $rules['alamat'] = 'required|string|max:500';
            $rules['nomor_telepon'] = 'required|string|max:15';
        } else {
            $rules['alamat'] = 'nullable|string|max:500';
            $rules['nomor_telepon'] = 'nullable|string|max:15';
        }

        // Jalankan Validasi
        $validated = $request->validate($rules, [
            'alamat.required' => 'Alamat wajib diisi untuk keperluan surat menyurat.',
            'nomor_telepon.required' => 'Nomor Telepon/WA wajib diisi agar mudah dihubungi.'
        ]);

        // 3. Update data user
        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Update alamat & telepon hanya jika input dikirim (ada di request)
        // Atau khusus user biasa
        if ($user->role === 'user') {
            $user->alamat = $validated['alamat'];
            $user->nomor_telepon = $validated['nomor_telepon'];
        }
        // Opsional: Jika Admin ingin menghapus data lamanya saat update (reset jadi null), uncomment baris di bawah:
        // else {
        //     $user->alamat = null;
        //     $user->nomor_telepon = null;
        // }

        $user->save();

        return redirect()->route('profile')
            ->with('success_profile', 'Informasi profil berhasil diperbarui.');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.current_password' => 'Kata sandi saat ini tidak cocok.'
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()->route('profile')
                         ->with('success_password', 'Kata sandi berhasil diubah.');
    }
}
