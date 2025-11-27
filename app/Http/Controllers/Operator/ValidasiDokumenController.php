<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\Arsip;
use Illuminate\Http\Request;

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
                  ->orWhereHas('user', function($u) use ($request) {
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
        $arsip->update([
            'status' => 'valid',
            'catatan_revisi' => null
        ]);

        return redirect()->back()
            ->with('success', 'Dokumen "' . $arsip->nama . '" berhasil divalidasi.');
    }

    public function revisi(Request $request, Arsip $arsip)
    {
        $request->validate([
            'catatan_revisi' => 'required|string|min:5',
        ], [
            'catatan_revisi.required' => 'Catatan revisi wajib diisi.',
            'catatan_revisi.min' => 'Catatan revisi terlalu pendek.',
        ]);

        $arsip->update([
            'status' => 'revisi',
            'catatan_revisi' => $request->catatan_revisi
        ]);

        return redirect()->back()
            ->with('success', 'Dokumen "' . $arsip->nama . '" telah dikembalikan untuk revisi.');
    }
}
