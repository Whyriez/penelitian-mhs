<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ValidasiDokumenController extends Controller
{
    public function index(Request $request)
    {
        // Query utama: Hanya ambil status pending
        $query = Arsip::query()->where('status', 'pending');

        // Filter Pencarian
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                    ->orWhere('deskripsi', 'like', '%' . $request->search . '%')
                    ->orWhereHas('user', function ($u) use ($request) {
                        $u->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Filter Tanggal
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sorting
        $sortBy = $request->input('sort_by', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'name':
                $query->orderBy('nama', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $dokumenPaginator = $query->with('user:id,name,email')
            ->paginate(10)
            ->withQueryString();

        // Statistik (Opsional, untuk header dashboard)
        $stats = [
            'total' => Arsip::count(),
            'pending' => Arsip::where('status', 'pending')->count(),
            'valid' => Arsip::where('status', 'valid')->count(),
            'revisi' => Arsip::where('status', 'revisi')->count(),
        ];

        return view('pages.operator.validasi_dokumen.index', [
            'dokumen' => $dokumenPaginator,
            'stats' => $stats,
            'filters' => $request->all()
        ]);
    }

    public function validasi(Arsip $arsip)
    {
        // Gunakan Transaction untuk mencegah duplikat nomor jika ada 2 admin klik bersamaan
        DB::transaction(function () use ($arsip) {

            // 1. Generate Nomor Izin Otomatis
            $nomorIzinBaru = $this->generateNomorIzin();

            // 2. Update Data
            $arsip->update([
                'status'         => 'valid',
                'nomor_izin'     => $nomorIzinBaru,
                'tgl_terbit'     => now(), // Tanggal terbit otomatis hari ini
                'catatan_revisi' => null,
                'file_revisi'    => null,
            ]);
        });

        return redirect()->back()
            ->with('success', 'Dokumen berhasil divalidasi. Nomor Izin diterbitkan otomatis.');
    }

    public function revisi(Request $request, Arsip $arsip)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|min:5',
            'file_revisi' => 'required|array',
            'file_revisi.*' => 'string',
        ], [
            'file_revisi.required' => 'Harap centang minimal satu file yang salah.',
        ]);

        $arsip->update([
            'status' => 'revisi',
            'catatan_revisi' => $request->catatan_revisi,
            'file_revisi' => $request->file_revisi
        ]);

        return redirect()->back()
            ->with('success', 'Dokumen dikembalikan untuk revisi.');
    }

    private function generateNomorIzin()
    {
        $now = Carbon::now();
        $tahun = $now->format('Y');
        $bulan = $now->format('n'); // 1-12
        $bulanRomawi = $this->getRomawi($bulan);

        // Cari nomor terakhir yang terbit DI TAHUN INI
        // Format di DB: 503/DPMPTSP-BB/IPM/xxxx/ROMAWI/TAHUN
        $lastArsip = Arsip::whereYear('tgl_terbit', $tahun)
            ->whereNotNull('nomor_izin')
            ->where('status', 'valid')
            ->orderBy('id', 'desc')
            ->first();

        $nextNo = 1; // Default jika belum ada data tahun ini

        if ($lastArsip) {
            // Pecah string berdasarkan '/'
            $parts = explode('/', $lastArsip->nomor_izin);

            // Asumsi urutan: [0]503, [1]Agency, [2]IPM, [3]NOMOR, [4]Romawi, [5]Tahun
            if (isset($parts[3]) && is_numeric($parts[3])) {
                $nextNo = (int) $parts[3] + 1;
            }
        }

        // Format 4 digit (misal: 1 jadi 0001)
        $noUrut = str_pad($nextNo, 4, '0', STR_PAD_LEFT);

        // Gabungkan string
        return "503/DPMPTSP-BB/IPM/{$noUrut}/{$bulanRomawi}/{$tahun}";
    }

    /**
     * Helper: Konversi Angka ke Romawi
     */
    private function getRomawi($bulan)
    {
        $map = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII'
        ];
        return $map[$bulan] ?? '';
    }
}
