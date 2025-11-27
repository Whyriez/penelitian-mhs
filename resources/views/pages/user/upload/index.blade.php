@extends('layouts.layout')
@section('title', 'Upload Dokumen')

@section('content')
<main class="flex-1 p-4 sm:p-6 lg:p-8 bg-gray-50 overflow-x-auto">
    <div class="max-w-7xl mx-auto space-y-8">
        {{-- Alert Session (Success/Error) tetap sama seperti kode Anda --}}
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-3 text-blue-600" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg> 
                    Upload Berkas Penelitian
                </h2>
            </div>

            <form action="{{ route('user.upload.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                @csrf

                {{-- Error Validation Summary --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg">
                        <strong class="font-bold">Perhatian!</strong> Ada beberapa kesalahan input:
                        <ul class="list-disc list-inside mt-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Informasi Dasar --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengajuan / Judul</label>
                        <input type="text" name="nama-dokumen" value="{{ old('nama-dokumen') }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Upload</label>
                        <input type="date" name="tanggal-upload" value="{{ old('tanggal-upload', date('Y-m-d')) }}" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Singkat</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi') }}</textarea>
                </div>

                <hr class="border-gray-200">

                {{-- Dynamic File Upload Section --}}
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Berkas Persyaratan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php
                            // List persyaratan dalam Array agar mudah dimanage
                            $syarat = [
                                'rekomendasi' => 'Rekomendasi Penelitian Asli',
                                'surat_pernyataan' => 'Surat Pernyataan',
                                'ktp' => 'KTP (Kartu Tanda Penduduk)',
                                'ktm' => 'KTM (Kartu Tanda Mahasiswa)',
                                'izin_penelitian' => 'Surat Izin Penelitian'
                            ];
                        @endphp

                        @foreach($syarat as $key => $label)
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                {{ $label }} <span class="text-red-500">*</span>
                            </label>
                            
                            {{-- Input File --}}
                            <input type="file" 
                                   name="dokumen[{{ $key }}]" 
                                   accept=".pdf,.doc,.docx,.jpg,.png"
                                   class="block w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-full file:border-0
                                          file:text-sm file:font-semibold
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100 transition-all cursor-pointer">
                            
                            {{-- Error message per file --}}
                            @error("dokumen.$key")
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex justify-end pt-4">
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-200 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Simpan & Ajukan
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection