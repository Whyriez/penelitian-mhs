<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DokumenController extends Controller
{
    private $requiredDocs = [
        'rekomendasi',
        'surat_pernyataan',
        'ktp',
        'ktm',
        'izin_penelitian'
    ];

    public function storeUpload(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama-dokumen' => 'required|string|max:255',
            'tanggal-upload' => 'required|date',
            'deskripsi' => 'required|string',
            // Validasi Array Dokumen
            'dokumen' => 'required|array',
            'dokumen.*' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // Max 5MB per file
        ], [
            'dokumen.*.required' => 'File ini wajib diunggah.',
            'dokumen.*.mimes' => 'Format file harus PDF, DOC, atau Gambar.',
        ]);

        // Cek kelengkapan (Hard validation optional, karena sudah ada 'required' di array validation)
        foreach ($this->requiredDocs as $key) {
            if (!$request->hasFile("dokumen.$key")) {
                return back()->withInput()->withErrors(["dokumen.$key" => "Dokumen ini wajib diisi!"]);
            }
        }

        try {
            $filePaths = [];

            // 2. Loop dan Upload setiap file
            foreach ($request->file('dokumen') as $key => $file) {
                // Nama file unik: time_jenis_originalName
                $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();

                // Store file
                $path = $file->storeAs('dokumen_arsip/' . Auth::id(), $filename, 'public');

                // Simpan path ke array temporary
                $filePaths[$key] = $path;
            }

            // 3. Simpan ke Database (Kolom file akan otomatis jadi JSON karena Casting di Model)
            Arsip::create([
                'user_id' => Auth::id(),
                'nama' => $request->input('nama-dokumen'),
                'deskripsi' => $request->input('deskripsi'),
                'tgl_upload' => $request->input('tanggal-upload'),
                'file' => $filePaths, // Array ini otomatis di-json_encode oleh Laravel
                'status' => 'pending',
            ]);

            return redirect()->route('user.riwayat')->with('success', 'Semua berkas berhasil diunggah!');
        } catch (\Exception $e) {
            Log::error('Upload Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data.');
        }
    }

    public function edit(Arsip $arsip)
    {
        if ($arsip->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengedit dokumen ini.');
        }

        return view('pages.user.riwayat.edit', [
            'arsip' => $arsip
        ]);
    }

    public function update(Request $request, Arsip $arsip)
    {
        if ($arsip->user_id !== Auth::id()) {
            abort(403, 'Anda tidak diizinkan mengupdate dokumen ini.');
        }

        if (!in_array($arsip->status, ['pending', 'revisi', 'ditolak'])) {
            return redirect()->route('user.riwayat')
                ->with('error', 'Dokumen yang sudah divalidasi tidak dapat diubah.');
        }

        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'dokumen.*' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        try {
            $arsip->nama = $request->nama;
            $arsip->deskripsi = $request->deskripsi;

            // Ambil data file lama (Array)
            $currentFiles = $arsip->file ?? []; 

            // Cek jika ada file baru yang diupload untuk mengganti yang lama
            if ($request->has('dokumen')) {
                foreach ($request->file('dokumen') as $key => $file) {
                    
                    // Hapus file lama fisik jika ada update pada key tersebut
                    if (isset($currentFiles[$key]) && Storage::disk('public')->exists($currentFiles[$key])) {
                        Storage::disk('public')->delete($currentFiles[$key]);
                    }

                    // Upload file baru
                    $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen_arsip/' . Auth::id(), $filename, 'public');

                    // Update array path
                    $currentFiles[$key] = $path;
                }
            }

            $arsip->file = $currentFiles; // Update kolom JSON
            $arsip->status = 'pending'; // Reset status jadi pending setelah revisi
            $arsip->catatan_revisi = null; // Clear catatan revisi
            $arsip->save();

            return redirect()->route('user.riwayat')->with('success', 'Dokumen revisi berhasil dikirim.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
}
