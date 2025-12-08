@extends('layouts.layout')
@section('title', 'Lengkapi Profil Saya')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Alert Khusus: Jika user diredirect paksa karena data belum lengkap --}}
            @if (session('warning'))
                <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded shadow-md mb-6 fade-in" role="alert">
                    <div class="flex">
                        <div class="py-1"><svg class="fill-current h-6 w-6 text-yellow-500 mr-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/></svg></div>
                        <div>
                            <p class="font-bold">Profil Belum Lengkap!</p>
                            <p class="text-sm">{{ session('warning') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if (session('success_profile'))
                <div class="info-banner rounded-lg p-4 fade-in bg-green-100 border border-green-300 text-green-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 mr-3" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm">
                            <span class="font-medium">Sukses!</span> {{ session('success_profile') }}
                        </p>
                    </div>
                </div>
            @endif

            {{-- Error Validation --}}
            @if ($errors->any() && !$errors->has('current_password') && !$errors->has('password'))
                <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg relative fade-in"
                     role="alert">
                    <strong class="font-bold">Harap periksa kembali inputan Anda:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            @if (!in_array($error, $errors->get('current_password') ?: []) && !in_array($error, $errors->get('password') ?: []))
                                <li>{{ $error }}</li>
                            @endif
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                <div class="p-6 border-b border-gray-100 bg-blue-50">
                    <h2 class="text-lg font-bold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Informasi Pribadi & Kontak
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Data ini akan digunakan untuk keperluan cetak surat dan export data.</p>
                </div>
                <div class="p-6">
                    <form id="profile-form" action="{{ route('profile.update') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Nama Lengkap --}}
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('name') border-red-500 @enderror" />
                            </div>

                            {{-- NIM (Readonly User) --}}
                            @if ($user->role === 'user')
                                <div>
                                    <label for="nim" class="block text-sm font-medium text-gray-700 mb-2">NIM <span class="text-red-500">*</span></label>
                                    <input type="text" id="nim" value="{{ $user->nim }}" readonly
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 text-sm cursor-not-allowed" />
                                </div>
                            @endif

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('email') border-red-500 @enderror" />
                            </div>

                            {{-- Nomor Telepon (BARU) --}}
                            @if ($user->role === 'user')
                                <div>
                                    <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon / WhatsApp <span class="text-red-500">*</span></label>
                                    <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon', $user->nomor_telepon) }}"
                                           required placeholder="Contoh: 08123456789"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('nomor_telepon') border-red-500 @enderror" />
                                </div>

                                {{-- Tampilkan Alamat HANYA jika role adalah 'user' --}}
                                <div class="md:col-span-2">
                                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap (Sesuai KTP/Domisili) <span class="text-red-500">*</span></label>
                                    <textarea id="alamat" name="alamat" rows="3" required placeholder="Jl. Contoh No. 123, Kelurahan A, Kecamatan B..."
                                              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-sky-500 focus:border-sky-500 text-sm @error('alamat') border-red-500 @enderror">{{ old('alamat', $user->alamat) }}</textarea>
                                </div>
                            @endif

                            {{-- Jabatan --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Role Akun <span class="text-red-500">*</span></label>
                                <input type="text" value="{{ ucfirst($user->role) }}" readonly
                                       class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 text-gray-600 text-sm" />
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                            <button type="submit" id="save-btn"
                                    class="px-6 py-2 bg-sky-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-sky-700 shadow-lg transform hover:-translate-y-0.5 transition-all">
                                Simpan Data Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Bagian Ubah Password (Tetap Sama) --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 fade-in">
                <div class="p-6 border-b border-gray-100">
                    <h2 class="text-lg font-medium text-gray-900">Keamanan Akun</h2>
                </div>
                <div class="p-6">
                    @if (session('success_password'))
                        <div class="info-banner rounded-lg p-4 mb-4 fade-in bg-green-100 border border-green-300 text-green-800">
                            <p class="text-sm font-medium">Sukses! {{ session('success_password') }}</p>
                        </div>
                    @endif

                    @if ($errors->has('current_password') || $errors->has('password'))
                        <div class="bg-red-100 border border-red-300 text-red-800 px-4 py-3 rounded-lg mb-4">
                            <strong class="font-bold">Gagal mengubah password!</strong>
                        </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">Kata Sandi</h3>
                            <p class="text-sm text-gray-500">Ganti kata sandi Anda secara berkala</p>
                        </div>
                        <button type="button" id="change-password-btn" data-modal-toggle="password-modal"
                                class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                            Ubah Kata Sandi
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Password (Tetap Sama) --}}
    <div id="modal-container" class="hidden">
        <div id="password-modal" class="dokumen-modal fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg shadow-xl w-11/12 md:w-1/2 lg:w-1/3">
                <form action="{{ route('profile.password.update') }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <div class="flex justify-between items-center p-4 border-b">
                        <h3 class="text-lg font-medium text-gray-900">Ubah Kata Sandi</h3>
                        <button type="button" data-modal-close="password-modal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Saat Ini</label>
                            <input type="password" name="current_password" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
                            <input type="password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-md" required>
                        </div>
                    </div>
                    <div class="px-6 py-4 bg-gray-50 border-t text-right space-x-3">
                        <button type="button" data-modal-close="password-modal" class="px-4 py-2 text-sm text-gray-700 bg-gray-100 rounded-md">Batal</button>
                        <button type="submit" class="px-4 py-2 bg-sky-600 text-white font-medium rounded-md hover:bg-sky-700">Simpan Kata Sandi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .fade-in { animation: fadeIn 0.3s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Script Modal tetap sama seperti sebelumnya
            const modalContainer = document.getElementById('modal-container');
            const modals = modalContainer.querySelectorAll('.dokumen-modal');
            modals.forEach(modal => document.body.appendChild(modal));
            modalContainer.remove();

            document.querySelectorAll('[data-modal-toggle]').forEach(btn => {
                btn.addEventListener('click', () => {
                    document.getElementById(btn.getAttribute('data-modal-toggle')).classList.remove('hidden');
                });
            });

            document.querySelectorAll('[data-modal-close]').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.getElementById(btn.getAttribute('data-modal-close')).classList.add('hidden');
                });
            });

            @if ($errors->has('current_password') || $errors->has('password'))
            document.getElementById('password-modal').classList.remove('hidden');
            @endif
        });
    </script>
@endsection
