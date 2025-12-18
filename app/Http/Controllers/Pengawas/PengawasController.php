<?php

namespace App\Http\Controllers\Pengawas;

use App\Http\Controllers\Controller;
use App\Models\Arsip; // Model yang benar (Penelitian Mahasiswa)
use App\Exports\DokumenMasukExport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class PengawasController extends Controller
{
    public function index(Request $request)
    {
        // KUNCI UTAMA: Hanya ambil data yang statusnya 'valid'
        $query = Arsip::query()->where('status', 'valid');

        // 1. Filter Search (Sesuai kolom di tabel 'arsip')
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nama_lembaga', 'like', '%' . $search . '%')      // Kolom khusus arsip
                    ->orWhere('tempat_penelitian', 'like', '%' . $search . '%') // Kolom khusus arsip
                    ->orWhere('nomor_izin', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function($u) use ($search) {
                        $u->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        // 2. Filter Tanggal (Berdasarkan tgl_terbit atau created_at)
        if ($request->filled('date_from')) {
            // Kita filter berdasarkan tanggal upload/masuk
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 3. Sorting
        $sortBy = $request->input('sort_by', 'newest');
        switch ($sortBy) {
            case 'oldest': $query->orderBy('created_at', 'asc'); break;
            case 'name': $query->orderBy('nama', 'asc'); break;
            case 'newest': default: $query->orderBy('created_at', 'desc'); break;
        }

        $dokumenPaginator = $query->with('user')->paginate(10)->withQueryString();

        // Statistik Sederhana
        $stats = [
            'total_valid' => Arsip::where('status', 'valid')->count(),
            'bulan_ini' => Arsip::where('status', 'valid')
                ->whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
        ];

        return view('pages.pengawas.index', [
            'dokumen' => $dokumenPaginator,
            'stats' => $stats,
            'filters' => $request->all()
        ]);
    }

    public function export(Request $request)
    {
        $timestamp = date('d-m-Y_H-i');
        // Pastikan Export class Anda support filter tabel 'arsip'
        return Excel::download(new DokumenMasukExport($request->all()), "Rekapan_Penelitian_Valid_$timestamp.xlsx");
    }
}
