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
        $request->validate([
            'nama-dokumen' => 'required|string|max:255',
            'nama-lembaga'      => 'required|string|max:255',
            'tempat-penelitian' => 'required|string|max:255',
            'nomor-surat'       => 'required|string|max:100',
            'tgl-surat'         => 'required|date',
            'dokumen' => 'required|array',
            'dokumen.*' => 'required|file|mimes:pdf|max:5120',
        ], [
            'nama-dokumen.required' => 'Pengajuan wajib diisi.',
            'nama-dokumen.string'   => 'Pengajuan harus berupa teks.',
            'nama-dokumen.max'      => 'Pengajuan tidak boleh lebih dari 255 karakter.',
            'nama-lembaga.required'      => 'Nama lembaga wajib diisi.',
            'tempat-penelitian.required' => 'Tempat penelitian wajib diisi.',
            'nomor-surat.required'       => 'Nomor surat wajib diisi.',
            'tgl-surat.required'         => 'Tanggal surat wajib diisi.',
            'dokumen.required' => 'Minimal harus ada satu file dokumen yang diunggah.',
            'dokumen.array'    => 'Format dokumen tidak valid.',
            'dokumen.*.required' => 'File ini wajib diunggah.',
            'dokumen.*.file'     => 'File ini tidak valid.',
            'dokumen.*.mimes'    => 'Format file harus PDF',
            'dokumen.*.max'      => 'Ukuran file tidak boleh lebih dari 5 MB.',
        ]);

        // Cek kelengkapan (Optional validation)
        foreach ($this->requiredDocs as $key) {
            if (!$request->hasFile("dokumen.$key")) {
                return back()->withInput()->withErrors(["dokumen.$key" => "Dokumen ini wajib diisi!"]);
            }
        }

        try {
            $filePaths = [];

            // 2. Loop dan Upload setiap file
            foreach ($request->file('dokumen') as $key => $file) {
                $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('dokumen_arsip/' . Auth::id(), $filename, 'public');
                $filePaths[$key] = $path;
            }

            // 3. Simpan ke Database
            Arsip::create([
                'user_id'           => Auth::id(),
                'nama'              => $request->input('nama-dokumen'),
                'nama_lembaga'      => $request->input('nama-lembaga'),
                'tempat_penelitian' => $request->input('tempat-penelitian'),
                'nomor_surat'       => $request->input('nomor-surat'),
                'tgl_surat'         => $request->input('tgl-surat'),

                'tgl_upload'        => $request->input('tanggal-upload'),
                'file'              => $filePaths,
                'status'            => 'pending',
            ]);

            return redirect()->route('user.riwayat')->with('success', 'Semua berkas berhasil diunggah!');
        } catch (\Exception $e) {
            Log::error('Upload Error: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
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
            return redirect()->route('user.riwayat')->with('error', 'Dokumen valid tidak dapat diubah.');
        }

        $request->validate([
            'nama'              => 'required|string|max:255',
            'nama_lembaga'      => 'required|string|max:255',
            'tempat_penelitian' => 'required|string|max:255',
            'nomor_surat'       => 'required|string|max:100', // Validasi update
            'tgl_surat'         => 'required|date',           // Validasi update
            'dokumen.*'         => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:5120',
        ]);

        try {
            $arsip->nama = $request->nama;
            $arsip->nama_lembaga      = $request->nama_lembaga;
            $arsip->tempat_penelitian = $request->tempat_penelitian;
            $arsip->nomor_surat       = $request->nomor_surat;
            $arsip->tgl_surat         = $request->tgl_surat;

            $currentFiles = $arsip->file ?? [];

            if ($request->has('dokumen')) {
                foreach ($request->file('dokumen') as $key => $file) {
                    if (isset($currentFiles[$key]) && Storage::disk('public')->exists($currentFiles[$key])) {
                        Storage::disk('public')->delete($currentFiles[$key]);
                    }
                    $filename = time() . '_' . $key . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('dokumen_arsip/' . Auth::id(), $filename, 'public');
                    $currentFiles[$key] = $path;
                }
            }

            $arsip->file = $currentFiles;
            $arsip->status = 'pending';
            $arsip->catatan_revisi = null;
            $arsip->save();

            return redirect()->route('user.riwayat')->with('success', 'Dokumen revisi berhasil dikirim.');

        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update: ' . $e->getMessage());
        }
    }
}
