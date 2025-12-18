@extends('layouts.layout')
@section('title', 'Riwayat Dokumen')

@section('content')
    <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-auto min-h-screen pb-20">
        <div class="max-w-7xl mx-auto space-y-6">
            {{-- Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Riwayat Dokumen</h1>
                    <p class="text-gray-600 mt-1">Kelola dan pantau status validasi berkas Anda</p>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-r shadow-sm relative fade-in"
                     role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-r shadow-sm relative fade-in"
                     role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Filter Section --}}
            <form method="GET" action="{{ route('user.riwayat') }}" id="filter-form">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 fade-in">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-2">
                            <label for="search"
                                   class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Pencarian</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                                <input type="text" id="search" name="search" placeholder="Cari nama dokumen..."
                                       value="{{ $filters['search'] ?? '' }}"
                                       class="pl-10 w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm transition-shadow" />
                            </div>
                        </div>
                        <div>
                            <label for="status-filter"
                                   class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Status</label>
                            <select id="status-filter" name="status"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm cursor-pointer bg-white">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ ($filters['status'] ?? '') == 'pending' ? 'selected' : '' }}>⏳
                                    Menunggu</option>
                                <option value="valid" {{ ($filters['status'] ?? '') == 'valid' ? 'selected' : '' }}>✅ Valid
                                </option>
                                <option value="revisi" {{ ($filters['status'] ?? '') == 'revisi' ? 'selected' : '' }}>⚠️
                                    Perlu Revisi</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <a href="{{ route('user.riwayat') }}"
                               class="w-full flex justify-center items-center px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors text-sm border border-gray-300">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                    </path>
                                </svg>
                                Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>

            {{-- Tabel Dokumen --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 fade-in overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full hidden md:table">
                        <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Nama Pengajuan</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Tanggal Masuk</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                File Berkas</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                        </thead>
                        <tbody id="document-table" class="bg-white divide-y divide-gray-200">
                        @forelse ($dokumen as $doc)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $doc->nama }}</div>

                                    @if ($doc->status == 'revisi' && $doc->catatan_revisi)
                                        <div class="mt-2 text-xs text-red-600 bg-red-50 p-2 rounded border border-red-100">
                                            <strong>Catatan:</strong> {{ $doc->catatan_revisi }}
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    {{ $doc->created_at ? \Carbon\Carbon::parse($doc->tgl_upload)->translatedFormat('d M Y') : '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if (is_array($doc->file))
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700 border border-blue-100">
                                                {{ count($doc->file) }} Berkas
                                            </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                Single File
                                            </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusMap = [
                                            'pending' => ['text' => 'Menunggu Validasi', 'class' => 'bg-yellow-100 text-yellow-800 border-yellow-200'],
                                            'valid' => ['text' => 'Valid / Diterima', 'class' => 'bg-green-100 text-green-800 border-green-200'],
                                            'revisi' => ['text' => 'Perlu Revisi', 'class' => 'bg-red-100 text-red-800 border-red-200'],
                                        ];
                                        $s = $statusMap[$doc->status] ?? ['text' => $doc->status, 'class' => 'bg-gray-100 text-gray-800'];
                                    @endphp
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full border {{ $s['class'] }}">
                                            {{ $s['text'] }}
                                        </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    @if (in_array($doc->status, ['pending', 'revisi', 'ditolak']))
                                        <a href="{{ route('user.dokumen.edit', $doc) }}"
                                           class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded transition-colors inline-flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            Update / Revisi
                                        </a>
                                    @else
                                        {{-- MODIFIKASI: Ubah "Terkunci" menjadi "Lihat Detail" --}}
                                        <button data-modal-toggle="detail-modal-{{ $doc->id }}"
                                                class="text-blue-600 hover:text-blue-900 bg-blue-50 px-3 py-1 rounded hover:bg-blue-100 transition-colors flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-.001.03-.002.06-.002.09a.097.097 0 01-.096.095 4.5 4.5 0 01-8.9 0 .097.097 0 01-.096-.095c0-.03.001-.06.002-.09z"></path>
                                            </svg>
                                            Lihat Detail
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" viewBox="0 0 24 24"
                                         stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                                    </svg>
                                    <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada dokumen</h3>
                                    <p class="mt-1 text-sm text-gray-500">Mulai dengan mengupload dokumen baru.</p>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    {{-- MOBILE VIEW --}}
                    <div id="document-mobile" class="md:hidden divide-y divide-gray-200">
                        @forelse ($dokumen as $doc)
                            <div class="p-4 space-y-3 bg-white">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h3 class="text-sm font-bold text-gray-900">{{ $doc->nama }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ \Carbon\Carbon::parse($doc->tgl_upload)->translatedFormat('d M Y') }}</p>
                                    </div>
                                    @php
                                        $s = $statusMap[$doc->status] ?? ['text' => $doc->status, 'class' => 'bg-gray-100'];
                                    @endphp
                                    <span class="px-2 py-1 text-[10px] font-semibold rounded-full border {{ $s['class'] }}">{{ $s['text'] }}</span>
                                </div>
                                <p class="text-sm text-gray-600">{{ $doc->deskripsi }}</p>

                                @if ($doc->status == 'revisi' && $doc->catatan_revisi)
                                    <div class="text-xs text-red-600 bg-red-50 p-2 rounded border border-red-100">
                                        <strong>Revisi:</strong> {{ $doc->catatan_revisi }}
                                    </div>
                                @endif

                                @if (in_array($doc->status, ['pending', 'revisi', 'ditolak']))
                                    <a href="{{ route('user.dokumen.edit', $doc) }}"
                                       class="block w-full text-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded hover:bg-indigo-700 transition">Update
                                        Dokumen</a>
                                @else
                                    {{-- MODIFIKASI MOBILE: Tombol Lihat Detail --}}
                                    <button data-modal-toggle="detail-modal-{{ $doc->id }}"
                                            class="block w-full text-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded hover:bg-blue-700 transition">
                                        Lihat & Download
                                    </button>
                                @endif
                            </div>
                        @empty
                            <div class="p-8 text-center text-gray-500">Belum ada dokumen</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    {{ $dokumen->links() }}
                </div>
            </div>

            {{-- MODALS CONTAINER (Gaya Admin) --}}
            <div id="modal-container">
                @foreach ($dokumen as $doc)
                    <div id="detail-modal-{{ $doc->id }}"
                         class="dokumen-modal fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-70 hidden backdrop-blur-sm">
                        <div class="relative w-full max-w-6xl bg-white rounded-xl shadow-2xl flex flex-col h-[90vh]">

                            {{-- Modal Header --}}
                            <div class="flex justify-between items-center p-4 border-b bg-gray-50 rounded-t-xl">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $doc->nama }}</h3>
                                    <p class="text-xs text-gray-500">
                                        Status: <span class="uppercase font-semibold">{{ $doc->status }}</span> |
                                        {{ $doc->created_at->format('d M Y H:i') }}
                                    </p>
                                </div>
                                <button data-modal-close="detail-modal-{{ $doc->id }}"
                                        class="text-gray-400 hover:text-red-500 p-2 hover:bg-gray-100 rounded-full transition-colors">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewbox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            {{-- Modal Body: Split Layout --}}
                            <div class="flex-1 overflow-hidden flex flex-col md:flex-row">

                                {{-- KIRI: List File --}}
                                <div class="w-full md:w-1/3 border-r border-gray-200 flex flex-col bg-white overflow-y-auto">
                                    <div class="p-4 border-b border-gray-100">
                                        <h4 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Daftar Berkas</h4>
                                        <div class="space-y-2">
                                            @if (is_array($doc->file))
                                                @foreach ($doc->file as $key => $path)
                                                    <button
                                                        onclick="changePreview('{{ $doc->id }}', '{{ asset('storage/' . $path) }}', '{{ $path }}')"
                                                        class="w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition-all group focus:ring-2 focus:ring-blue-500">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center">
                                                                <span
                                                                    class="bg-blue-100 text-blue-600 p-2 rounded-md mr-3 group-hover:bg-blue-200">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                         viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                              stroke-width="2"
                                                                              d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                                        </path>
                                                                    </svg>
                                                                </span>
                                                                <div>
                                                                    <p class="text-sm font-semibold text-gray-700 group-hover:text-blue-800">
                                                                        {{ ucwords(str_replace('_', ' ', $key)) }}</p>
                                                                    <p class="text-[10px] text-gray-400">Klik untuk preview</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </button>
                                                @endforeach
                                            @else
                                                {{-- Fallback jika file tunggal --}}
                                                <button
                                                    onclick="changePreview('{{ $doc->id }}', '{{ asset('storage/' . $doc->file) }}', '{{ $doc->file }}')"
                                                    class="w-full text-left px-4 py-3 rounded-lg border border-gray-200 hover:border-blue-500 hover:bg-blue-50 transition-all group">
                                                    Lihat File Utama
                                                </button>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Info Tambahan --}}
                                    <div class="p-4 mt-auto bg-gray-50 text-xs text-gray-500">
                                        <p>Nomor Surat: {{ $doc->nomor_surat ?? '-' }}</p>
                                        <p>Tempat: {{ $doc->tempat_penelitian ?? '-' }}</p>
                                    </div>
                                </div>

                                {{-- KANAN: Preview Area --}}
                                <div class="w-full md:w-2/3 bg-gray-100 flex flex-col relative">
                                    <div class="flex-1 relative overflow-hidden flex items-center justify-center p-4">
                                        {{-- Loading State --}}
                                        <div id="loader-{{ $doc->id }}"
                                             class="absolute inset-0 flex items-center justify-center bg-gray-100 z-10 hidden">
                                            <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg"
                                                 fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                                        stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                      d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                                </path>
                                            </svg>
                                        </div>

                                        {{-- Iframe Preview --}}
                                        <iframe id="preview-frame-{{ $doc->id }}" src="about:blank"
                                                class="w-full h-full rounded-lg shadow-sm bg-white border border-gray-200 hidden"></iframe>

                                        {{-- Placeholder State (Initial) --}}
                                        <div id="placeholder-{{ $doc->id }}" class="text-center text-gray-400">
                                            <svg class="w-16 h-16 mx-auto mb-2 opacity-50" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                            <p class="text-sm">Pilih berkas di sebelah kiri untuk melihat preview</p>
                                        </div>

                                        {{-- Image Preview Tag (Alternative if not PDF) --}}
                                        <img id="preview-img-{{ $doc->id }}" src=""
                                             class="max-w-full max-h-full object-contain hidden rounded-lg shadow-sm" />
                                    </div>

                                    {{-- Action Bar (Download Button) --}}
                                    <div class="p-3 bg-white border-t border-gray-200 flex justify-between items-center">
                                        <span id="filename-display-{{ $doc->id }}"
                                              class="text-xs text-gray-500 italic">Belum ada file dipilih</span>
                                        <a id="download-btn-{{ $doc->id }}" href="#" target="_blank"
                                           class="text-blue-600 hover:text-blue-800 text-sm font-medium hidden flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                            Download File Asli
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Modal Footer --}}
                            <div class="p-4 border-t bg-gray-50 flex justify-end space-x-3 rounded-b-xl">
                                <button data-modal-close="detail-modal-{{ $doc->id }}"
                                        class="px-5 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 shadow-sm">
                                    Tutup
                                </button>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </main>

    <style>
        .fade-in {
            animation: fadeIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
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
        /* Hide scrollbar for list */
        .overflow-y-auto::-webkit-scrollbar {
            width: 5px;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background-color: #cbd5e1;
            border-radius: 4px;
        }
    </style>

    <script>
        // Logic Ganti Preview di Modal Detail (Sama seperti Admin)
        function changePreview(docId, fullUrl, filePath) {
            const loader = document.getElementById('loader-' + docId);
            const iframe = document.getElementById('preview-frame-' + docId);
            const img = document.getElementById('preview-img-' + docId);
            const placeholder = document.getElementById('placeholder-' + docId);
            const downloadBtn = document.getElementById('download-btn-' + docId);
            const filenameDisp = document.getElementById('filename-display-' + docId);

            // Reset UI
            placeholder.classList.add('hidden');
            loader.classList.remove('hidden');
            iframe.classList.add('hidden');
            img.classList.add('hidden');
            downloadBtn.classList.remove('hidden');

            // Set Download Link
            downloadBtn.href = fullUrl;
            filenameDisp.textContent = filePath.split('/').pop(); // Show filename

            const ext = filePath.split('.').pop().toLowerCase();

            if (['jpg', 'jpeg', 'png'].includes(ext)) {
                // Image Mode
                img.src = fullUrl;
                img.onload = () => {
                    loader.classList.add('hidden');
                    img.classList.remove('hidden');
                };
            } else {
                // PDF Mode (via PDFJS)
                const viewerUrl = `{{ asset('pdfjs/web/viewer.html') }}?file=${encodeURIComponent(fullUrl)}`;
                iframe.src = viewerUrl;

                iframe.onload = () => {
                    loader.classList.add('hidden');
                    iframe.classList.remove('hidden');
                };
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Modal Logic (Open/Close)
            const modalToggles = document.querySelectorAll('[data-modal-toggle]');
            const modalCloses = document.querySelectorAll('[data-modal-close]');
            const statusFilter = document.getElementById('status-filter');

            modalToggles.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-modal-toggle');
                    document.getElementById(id).classList.remove('hidden');
                });
            });

            modalCloses.forEach(btn => {
                btn.addEventListener('click', () => {
                    const id = btn.getAttribute('data-modal-close');
                    document.getElementById(id).classList.add('hidden');
                    // Reset Iframe untuk hemat memori
                    const iframe = document.querySelector(`#${id} iframe`);
                    if (iframe) iframe.src = 'about:blank';
                });
            });

            // Submit filter on change
            if (statusFilter) {
                statusFilter.addEventListener('change', () => {
                    document.getElementById('filter-form').submit();
                });
            }
        });
    </script>
@endsection
