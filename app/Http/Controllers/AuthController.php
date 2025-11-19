<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    public function index()
    {
        return view('pages.auth.index');
    }

    public function register()
    {
        return view('pages.auth.register');
    }

    public function login_process(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            $request->session()->regenerate();

            switch ($user->role) {
                case 'admin':
                    return redirect()->route('admin.index');
                case 'operator':
                    return redirect()->route('operator.index');
                case 'user':
                    return redirect()->route('user.index');
                default:
                    Auth::logout();
                    return redirect('/')->with('flash.error', 'Peran tidak valid.');
            }
        }

        return back()->with('flash.error', ' email atau kata sandi salah.');
    }

    public function register_process(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            Auth::login($user);

            // 5. Regenerate session
            $request->session()->regenerate();

            return redirect()->route('user.index')
                ->with('flash.success', 'Registrasi berhasil! Selamat datang di sistem.');
        } catch (\Exception $e) {
            return back()->with('flash.error', 'Terjadi kesalahan saat mendaftarkan akun. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('flash.success', 'Anda telah berhasil keluar.');
    }
}
