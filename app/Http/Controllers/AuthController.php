<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('pages.auth.index');
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

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('flash.success', 'Anda telah berhasil keluar.');
    }
}
