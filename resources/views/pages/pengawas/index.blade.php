@extends('layouts.layout')
@section('title', 'Rekapan Penelitian Valid')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto min-h-screen pb-20">
        <div class="max-w-7xl mx-auto space-y-8">

            {{-- HEADER & STATS --}}
            <div class="flex flex-col md:flex-row gap-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 flex-1">
                    {{-- Total Valid --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in flex items-center">
                        <div class="p-3 rounded-lg bg-green-100 text-green-600 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total Valid</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['total_valid'] }}</p>
                        </div>
                    </div>
                    {{-- Bulan Ini --}}
                    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in flex items-center">
                        <div class="p-3 rounded-lg bg-sky-100 text-sky-600 mr-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Bulan Ini</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['bulan_ini'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- FILTER SECTION --}}
            <form method="GET" action="{{ url()->current() }}" id="filter-form">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-4 sm:p-6 fade-in">
                    <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">

                        {{-- Search --}}
                        <div class="md:col-span-5">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Cari Data</label>
                            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}"
                                   placeholder="Judul, Lembaga, atau Peneliti..."
                                   class="block w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-sky-500 focus:border-sky-500 sm:text-sm" />
                        </div>

                        {{-- Tanggal --}}
                        <div class="md:col-span-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Masuk</label>
                            <div class="flex gap-2">
                                <input type="date" name="date_from" value="{{ $filters['date_from'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"/>
                                <input type="date" name="date_to" value="{{ $filters['date_to'] ?? '' }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"/>
                            </div>
                        </div>

                        {{-- Action Buttons --}}
                        <div class="md:col-span-3 flex gap-2">
                            <button type="submit" class="flex-1 bg-sky-600 text-white px-4 py-2 rounded-lg hover:bg-sky-700 text-sm font-medium">Filter</button>
                            <a href="{{ route('pengawas.export', request()->all()) }}" target="_blank" class="flex-1 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 text-sm font-medium text-center flex items-center justify-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg> Excel
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            {{-- TABLE SECTION --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in overflow-hidden">
                <div class="p-6 border-b border-gray-200 flex justify-between items-center">
                    <h2 class="text-xl font-semibold text-gray-900">Data Penelitian Valid</h2>
                    <select name="sort_by" form="filter-form" onchange="this.form.submit()" class="text-sm border-gray-300 rounded-lg">
                        <option value="newest" {{ ($filters['sort_by'] ?? '') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ ($filters['sort_by'] ?? '') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="name" {{ ($filters['sort_by'] ?? '') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Judul & Lembaga</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Tempat Penelitian</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Peneliti</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Status Izin</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase">Aksi</th>
                        </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($dokumen as $doc)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $doc->nama }}</div>
                                    <div class="text-xs text-gray-500 mt-1">Lembaga: {{ $doc->nama_lembaga }}</div>
                                    <div class="text-xs text-gray-400 mt-1">Surat: {{ $doc->nomor_surat }} ({{ \Carbon\Carbon::parse($doc->tgl_surat)->format('d M Y') }})</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ $doc->tempat_penelitian }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $doc->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-gray-500">{{ $doc->user->email }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    {{-- Tampilkan Nomor Izin jika ada --}}
                                    @if($doc->nomor_izin)
                                        <div class="px-2 inline-flex flex-col text-xs leading-5 font-semibold rounded bg-green-100 text-green-800 p-1">
                                            <span>Terbit No:</span>
                                            <span>{{ $doc->nomor_izin }}</span>
                                            <span class="font-normal mt-1">{{ \Carbon\Carbon::parse($doc->tgl_terbit)->format('d M Y') }}</span>
                                        </div>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Valid (Menunggu No. Izin)
                                            </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <button data-modal-toggle="detail-modal-{{ $doc->id }}" class="text-sky-600 hover:text-sky-900 bg-sky-50 px-3 py-1 rounded text-xs font-medium">
                                        Detail
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Tidak ada data valid ditemukan.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-200">{{ $dokumen->links() }}</div>
            </div>
        </div>
    </main>

    {{-- MODAL DETAIL (READ ONLY) --}}
    <div id="modal-container">
        @foreach ($dokumen as $doc)
            <div id="detail-modal-{{ $doc->id }}" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 backdrop-blur-sm">
                <div class="bg-white w-full max-w-5xl rounded-xl shadow-2xl h-[85vh] flex flex-col">

                    {{-- Header Modal --}}
                    <div class="p-4 border-b flex justify-between items-center bg-gray-50 rounded-t-xl">
                        <div>
                            <h3 class="font-bold text-gray-900 text-lg">{{ $doc->nama }}</h3>
                            <p class="text-xs text-gray-500 mt-1">Lembaga: {{ $doc->nama_lembaga }} | Peneliti: {{ $doc->user->name }}</p>
                        </div>
                        <button data-modal-close="detail-modal-{{ $doc->id }}" class="text-gray-400 hover:text-red-500"><svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg></button>
                    </div>

                    {{-- Content Modal --}}
                    <div class="flex-1 overflow-hidden flex flex-col md:flex-row">
                        {{-- Info Kiri --}}
                        <div class="w-full md:w-1/3 border-r bg-white overflow-y-auto p-4 space-y-4">

                            {{-- Detail Singkat --}}
                            <div class="bg-sky-50 p-3 rounded-lg border border-sky-100">
                                <h4 class="text-xs font-bold text-sky-700 uppercase mb-2">Detail Permohonan</h4>
                                <div class="text-xs space-y-2 text-gray-700">
                                    <p><span class="font-semibold block">Tempat Penelitian:</span> {{ $doc->tempat_penelitian }}</p>
                                    <p><span class="font-semibold block">Nomor Surat Pengantar:</span> {{ $doc->nomor_surat }}</p>
                                    <p><span class="font-semibold block">Tanggal Surat:</span> {{ \Carbon\Carbon::parse($doc->tgl_surat)->format('d F Y') }}</p>
                                </div>
                            </div>

                            {{-- Status Izin --}}
                            <div class="bg-green-50 p-3 rounded-lg border border-green-100">
                                <h4 class="text-xs font-bold text-green-700 uppercase mb-2">Status Izin</h4>
                                <div class="text-xs space-y-2 text-gray-700">
                                    <p><span class="font-semibold">Status:</span> Valid / Disetujui</p>
                                    @if($doc->nomor_izin)
                                        <p><span class="font-semibold block">Nomor Izin Terbit:</span> {{ $doc->nomor_izin }}</p>
                                        <p><span class="font-semibold block">Tanggal Terbit:</span> {{ \Carbon\Carbon::parse($doc->tgl_terbit)->format('d F Y') }}</p>
                                    @else
                                        <p class="italic text-gray-500">Nomor izin belum diinput oleh admin.</p>
                                    @endif
                                </div>
                            </div>

                            {{-- List File --}}
                            <div>
                                <h4 class="text-xs font-bold text-gray-500 uppercase mb-3">Berkas Lampiran</h4>
                                @if(is_array($doc->file))
                                    @foreach($doc->file as $key => $path)
                                        <button onclick="changePreview('{{ $doc->id }}', '{{ asset('storage/' . $path) }}')" class="w-full text-left px-3 py-2 mb-2 rounded border hover:bg-sky-50 text-sm flex items-center transition-colors group">
                                            <span class="bg-sky-100 text-sky-600 p-1.5 rounded mr-2 group-hover:bg-sky-200">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </span>
                                            <span class="truncate">{{ ucwords(str_replace('_', ' ', $key)) }}</span>
                                        </button>
                                    @endforeach
                                @endif
                            </div>
                        </div>

                        {{-- Preview Kanan --}}
                        <div class="w-full md:w-2/3 bg-gray-100 p-4 flex items-center justify-center relative">
                            <iframe id="preview-frame-{{ $doc->id }}" src="about:blank" class="w-full h-full bg-white rounded shadow hidden border border-gray-200"></iframe>
                            <div id="placeholder-{{ $doc->id }}" class="text-gray-400 text-sm flex flex-col items-center">
                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Pilih berkas untuk melihat preview
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Script Modal & Preview --}}
    <script>
        function changePreview(id, url) {
            document.getElementById('placeholder-' + id).classList.add('hidden');
            const iframe = document.getElementById('preview-frame-' + id);
            iframe.src = `{{ asset('pdfjs/web/viewer.html') }}?file=${encodeURIComponent(url)}`;
            iframe.classList.remove('hidden');
        }
        document.querySelectorAll('[data-modal-toggle]').forEach(b => b.onclick = () => document.getElementById(b.dataset.modalToggle).classList.remove('hidden'));
        document.querySelectorAll('[data-modal-close]').forEach(b => b.onclick = () => document.getElementById(b.dataset.modalClose).classList.add('hidden'));
    </script>
@endsection
