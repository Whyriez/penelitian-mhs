<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileCompleted
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Cek hanya untuk user yang login dan role-nya 'user' (mahasiswa/peneliti)
        // Admin/Operator mungkin tidak wajib mengisi alamat
        if ($user && $user->role === 'user') {

            // Cek apakah alamat atau nomor_telepon masih kosong
            if (empty($user->alamat) || empty($user->nomor_telepon)) {

                // Jika sedang mengakses halaman profile atau update profile, biarkan lewat
                if ($request->routeIs('profile') || $request->routeIs('profile.*') || $request->is('logout')) {
                    return $next($request);
                }

                // Jika mengakses halaman lain (misal upload), lempar ke profile dengan pesan error
                return redirect()->route('profile')
                    ->with('warning', 'Halo ' . $user->name . ', sebelum melanjutkan, Anda WAJIB melengkapi Alamat dan Nomor Telepon terlebih dahulu untuk keperluan administrasi surat.');
            }
        }

        return $next($request);
    }
}
