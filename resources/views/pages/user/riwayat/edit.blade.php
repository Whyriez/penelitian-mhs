@extends('layouts.layout')
@section('title', 'Revisi Dokumen Penelitian')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto pb-20">
        <div class="max-w-7xl mx-auto space-y-6">

            {{-- Header Section --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Update / Revisi Dokumen</h1>
                    <p class="text-gray-600 mt-1">Perbaiki data atau lengkapi berkas persyaratan penelitian.</p>
                </div>
                <a href="{{ route('user.riwayat') }}" class="text-sm text-sky-600 hover:underline flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali ke Riwayat
                </a>
            </div>

            {{-- Error Validation --}}
            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 rounded shadow-sm">
                    <p class="font-bold">Gagal Menyimpan!</p>
                    <ul class="mt-1 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Catatan Revisi (Jika Ada) --}}
            @if ($arsip->status == 'revisi' && $arsip->catatan_revisi)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded shadow-sm fade-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-bold text-yellow-800">Permintaan Revisi dari Admin:</h3>
                            <div class="mt-1 text-sm text-yellow-700 bg-yellow-100 p-2 rounded">
                                {{ $arsip->catatan_revisi }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
                {{-- Pastikan route update sesuai dengan controller Anda --}}
                <form action="{{ route('user.dokumen.update', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="p-6 space-y-8">

                        {{-- Bagian 1: Informasi Surat --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2 pb-2 border-b">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">1</span>
                                    Informasi Surat
                                </h3>
                            </div>

                            {{-- Nama / Judul --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pengajuan / Judul Penelitian</label>
                                <input type="text" name="nama" value="{{ old('nama', $arsip->nama) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Nama Lembaga (FIELD BARU) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lembaga / Instansi</label>
                                <input type="text" name="nama_lembaga" value="{{ old('nama_lembaga', $arsip->nama_lembaga) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Contoh: Dinas Pendidikan">
                            </div>

                            {{-- Tempat Penelitian (FIELD BARU) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tempat Penelitian (Lokasi)</label>
                                <input type="text" name="tempat_penelitian"
                                       value="{{ old('tempat_penelitian', $arsip->tempat_penelitian) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                                       placeholder="Contoh: Kota Gorontalo">
                            </div>

                            {{-- Nomor Surat (FIELD BARU) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor Surat Rekomendasi</label>
                                <input type="text" name="nomor_surat"
                                       value="{{ old('nomor_surat', $arsip->nomor_surat) }}" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            {{-- Tanggal Surat (FIELD BARU) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Surat</label>
                                <input type="date" name="tgl_surat"
                                       value="{{ old('tgl_surat', $arsip->tgl_surat ? \Carbon\Carbon::parse($arsip->tgl_surat)->format('Y-m-d') : '') }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        {{-- Bagian 2: Kelengkapan Berkas --}}
                        <div>
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b pb-2 mb-6 gap-2">
                                <h3 class="text-lg font-bold text-gray-900 flex items-center">
                                    <span class="bg-blue-100 text-blue-600 w-8 h-8 rounded-full flex items-center justify-center mr-2 text-sm">2</span>
                                    Kelengkapan Berkas
                                </h3>
                                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                                    PDF (Max 5MB)
                                </span>
                            </div>

                            @php
                                // Array Persyaratan sesuai Controller
                                $syarat = [
                                    'rekomendasi' => 'Rekomendasi Penelitian Asli',
                                    'surat_pernyataan' => 'Surat Pernyataan',
                                    'ktp' => 'KTP (Kartu Tanda Penduduk)',
                                    'ktm' => 'KTM (Kartu Tanda Mahasiswa)',
                                    'izin_penelitian' => 'Surat Izin Penelitian',
                                ];

                                $currentFiles = is_array($arsip->file) ? $arsip->file : [];

                                // Ambil file yang perlu direvisi dari database (array)
                                $filesToRevise = $arsip->file_revisi ?? [];
                                $isRevisiStatus = $arsip->status == 'revisi';
                            @endphp

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($syarat as $key => $label)
                                    @php
                                        // Logika: Jika status 'pending' atau 'ditolak', user mungkin ingin edit semua.
                                        // Jika status 'revisi', user HANYA BISA edit yg ada di $filesToRevise.
                                        // Jika $filesToRevise kosong saat status revisi, kita buka semua (fallback).

                                        $canEdit = !$isRevisiStatus || (empty($filesToRevise)) || ($isRevisiStatus && in_array($key, $filesToRevise));
                                    @endphp

                                    <div class="border rounded-lg p-4 transition-all {{ $canEdit ? ($errors->has("dokumen.$key") ? 'bg-red-50 border-red-300' : 'bg-white border-gray-200 hover:border-blue-300') : 'bg-gray-100 border-gray-200 opacity-75' }}">

                                        <div class="flex justify-between items-start mb-2">
                                            <label class="block text-sm font-bold text-gray-700">
                                                {{ $label }}
                                            </label>

                                            {{-- Label Status Per File --}}
                                            @if ($isRevisiStatus && !empty($filesToRevise))
                                                @if ($canEdit)
                                                    <span class="px-2 py-1 bg-red-100 text-red-700 text-[10px] font-bold uppercase rounded tracking-wide border border-red-200">
                                                        Perlu Revisi
                                                    </span>
                                                @else
                                                    <span class="px-2 py-1 bg-green-100 text-green-700 text-[10px] font-bold uppercase rounded tracking-wide border border-green-200">
                                                        Sudah Benar
                                                    </span>
                                                @endif
                                            @else
                                                @if($canEdit)
                                                    <span class="text-blue-500 text-xs font-semibold cursor-pointer">Edit</span>
                                                @endif
                                            @endif
                                        </div>

                                        {{-- Status File Saat Ini (Link Download) --}}
                                        <div class="mb-3 flex items-center justify-between text-sm">
                                            @if (isset($currentFiles[$key]))
                                                <div class="flex items-center text-blue-600 font-medium overflow-hidden">
                                                    <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none"
                                                         stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                              stroke-width="2"
                                                              d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13">
                                                        </path>
                                                    </svg>
                                                    <a href="{{ asset('storage/' . $currentFiles[$key]) }}" target="_blank"
                                                       class="hover:underline truncate text-xs">
                                                        Lihat File Lama
                                                    </a>
                                                </div>
                                            @else
                                                <span class="text-gray-400 text-xs italic">Belum ada file.</span>
                                            @endif
                                        </div>

                                        {{-- Input File: Hanya muncul jika $canEdit bernilai TRUE --}}
                                        @if ($canEdit)
                                            <input type="file" name="dokumen[{{ $key }}]" accept=".pdf"
                                                   class="block w-full text-xs text-gray-500
                                                file:mr-4 file:py-2 file:px-4
                                                file:rounded-full file:border-0
                                                file:text-xs file:font-semibold
                                                file:bg-blue-50 file:text-blue-700
                                                hover:file:bg-blue-100 cursor-pointer border rounded-full bg-white shadow-sm">

                                            <p class="text-[10px] text-gray-500 mt-2 ml-1">
                                                *Upload file baru untuk mengganti (PDF).
                                            </p>

                                            @error("dokumen.$key")
                                            <p class="text-red-600 text-xs mt-1 font-medium">{{ $message }}</p>
                                            @enderror
                                        @else
                                            {{-- Jika dikunci (Tidak perlu revisi) --}}
                                            <div class="flex items-center p-3 bg-white rounded border border-gray-200 text-gray-500 text-xs italic shadow-sm">
                                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none"
                                                     stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                          d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Berkas ini sudah valid.
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex justify-end gap-3 rounded-b-xl">
                        <a href="{{ route('user.riwayat') }}"
                           class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="px-5 py-2.5 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-md transform hover:-translate-y-0.5 transition-all">
                            Simpan Perubahan & Ajukan Ulang
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <style>
        .fade-in {
            animation: fadeIn 0.4s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
