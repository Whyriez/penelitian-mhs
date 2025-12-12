<?php

namespace App\Exports;

use App\Models\Arsip;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DokumenMasukExport implements FromView, ShouldAutoSize, WithStyles
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        // FILTER: Hanya ambil data dengan status 'valid' (Disetujui)
        $query = Arsip::query()
            ->with('user')
            ->where('status', 'valid');

        // --- Filter Search (Nama atau Lembaga) ---
        if (isset($this->request['search']) && $this->request['search'] != null) {
            $search = $this->request['search'];
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('nama_lembaga', 'like', '%' . $search . '%');
            });
        }

        // HAPUS filter status dari request, karena kita memaksa hanya ambil yang 'valid'
        // if (isset($this->request['status']) && $this->request['status'] != null) {
        //     $query->where('status', $this->request['status']);
        // }

        // --- Filter Tanggal ---
        if (isset($this->request['date_from']) && $this->request['date_from'] != null) {
            $query->whereDate('created_at', '>=', $this->request['date_from']);
        }

        if (isset($this->request['date_to']) && $this->request['date_to'] != null) {
            $query->whereDate('created_at', '<=', $this->request['date_to']);
        }

        // Urutkan berdasarkan tanggal terbaru
        $query->orderBy('created_at', 'desc');

        // Menentukan Bulan dan Tahun untuk Judul Laporan
        $dateFrom = $this->request['date_from'] ?? date('Y-m-d');
        $carbonDate = Carbon::parse($dateFrom);

        return view('exports.laporan_peneliti', [
            'data'  => $query->get(),
            'bulan' => $carbonDate->translatedFormat('F'),
            'tahun' => $carbonDate->format('Y'),
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
