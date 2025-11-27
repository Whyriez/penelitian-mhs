@extends('layouts.layout')
@section('title', 'Update Dokumen')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto pb-20">
        <div class="max-w-7xl mx-auto space-y-6">
            {{-- Header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Update Dokumen</h1>
                    <p class="text-gray-600 mt-1">Perbaiki atau lengkapi berkas persyaratan Anda</p>
                </div>
                <a href="{{ route('user.riwayat') }}" class="text-sm text-sky-600 hover:text-sky-700 hover:underline flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali ke Riwayat
                </a>
            </div>

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm" role="alert">
                    <strong class="font-bold">Gagal Menyimpan!</strong>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Catatan Revisi (Penting ditampilkan paling atas) --}}
            @if ($arsip->status == 'revisi' && $arsip->catatan_revisi)
                 <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r shadow-sm relative fade-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm leading-5 font-medium text-yellow-800">
                                Permintaan Revisi dari Admin
                            </h3>
                            <div class="mt-2 text-sm leading-5 text-yellow-700">
                                <p>{{ $arsip->catatan_revisi }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
                <form action="{{ route('user.dokumen.update', $arsip) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    
                    <div class="p-6 space-y-8">
                        {{-- Bagian 1: Informasi Umum --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informasi Pengajuan</h3>
                            </div>
                            
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Judul Dokumen</label>
                                <input type="text" id="nama" name="nama" value="{{ old('nama', $arsip->nama) }}"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    required>
                            </div>

                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                <textarea id="deskripsi" name="deskripsi" rows="3"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" required>{{ old('deskripsi', $arsip->deskripsi) }}</textarea>
                            </div>
                        </div>

                        {{-- Bagian 2: Kelola File --}}
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-6 flex items-center justify-between">
                                <span>Kelola Berkas</span>
                                <span class="text-xs font-normal text-gray-500 bg-gray-100 px-2 py-1 rounded">Format: PDF, DOCX, JPG (Max 5MB)</span>
                            </h3>

                            @php
                                // Definisi ulang list dokumen agar konsisten
                                $syarat = [
                                    'rekomendasi' => 'Rekomendasi Penelitian Asli',
                                    'surat_pernyataan' => 'Surat Pernyataan',
                                    'ktp' => 'KTP (Kartu Tanda Penduduk)',
                                    'ktm' => 'KTM (Kartu Tanda Mahasiswa)',
                                    'izin_penelitian' => 'Surat Izin Penelitian'
                                ];
                                
                                // Pastikan data file berbentuk array
                                $currentFiles = is_array($arsip->file) ? $arsip->file : [];
                            @endphp

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($syarat as $key => $label)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition-all {{ $errors->has("dokumen.$key") ? 'bg-red-50 border-red-300' : 'bg-gray-50' }}">
                                        <label class="block text-sm font-bold text-gray-700 mb-2">
                                            {{ $label }}
                                        </label>

                                        {{-- Status File Saat Ini --}}
                                        <div class="mb-3 flex items-center justify-between text-sm">
                                            <span class="text-gray-500">Status:</span>
                                            @if(isset($currentFiles[$key]))
                                                <div class="flex items-center text-green-600 font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    <a href="{{ asset('storage/' . $currentFiles[$key]) }}" target="_blank" class="hover:underline">
                                                        Sudah diupload (Lihat)
                                                    </a>
                                                </div>
                                            @else
                                                <div class="flex items-center text-red-500 font-medium">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                                    Belum ada
                                                </div>
                                            @endif
                                        </div>

                                        {{-- Input Update --}}
                                        <input type="file" name="dokumen[{{ $key }}]" 
                                            class="block w-full text-xs text-gray-500
                                            file:mr-4 file:py-2 file:px-4
                                            file:rounded-full file:border-0
                                            file:text-xs file:font-semibold
                                            file:bg-white file:text-blue-700
                                            hover:file:bg-blue-50 cursor-pointer border rounded-full bg-white">
                                        
                                        <p class="text-[10px] text-gray-500 mt-2 ml-1">
                                            *Upload file baru jika ingin mengganti yang lama.
                                        </p>

                                        @error("dokumen.$key")
                                            <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-3 rounded-b-xl">
                         <a href="{{ route('user.riwayat') }}" class="px-5 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                            Batal
                        </a>
                        <button type="submit" class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            Simpan Perubahan & Ajukan Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <style>
        .fade-in { animation: fadeIn 0.4s ease-out; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
@endsection