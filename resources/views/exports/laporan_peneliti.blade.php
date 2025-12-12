<table>
    {{-- KOP SURAT --}}
    <thead>
    <tr>
        {{-- Total Kolom ada 11 --}}
        <th colspan="11" style="text-align: center; font-family: Arial; font-size: 14px; font-weight: bold;">
            PEMERINTAH KABUPATEN BONE BOLANGO
        </th>
    </tr>
    <tr>
        <th colspan="11" style="text-align: center; font-family: Arial; font-size: 16px; font-weight: bold;">
            DINAS PENANAMAN MODAL PELAYANAN TERPADU SATU PINTU
        </th>
    </tr>
    <tr>
        <th colspan="11" style="text-align: center; font-family: Arial; font-size: 10px; font-style: italic;">
            Pusat Pemerintahan Jln. Prof. DR. Ing BJ. Habibie Desa Ulantha Kecamatan Suwawa
        </th>
    </tr>
    <tr>
        <th colspan="11" style="border-bottom: 3px double #000000;"></th>
    </tr>
    <tr>
        <th colspan="11"></th>
    </tr>

    <tr>
        <th colspan="11" style="text-align: center; font-family: Arial; font-size: 12px; font-weight: bold;">
            REKAPITULASI PENGAJUAN DOKUMEN IZIN PENELITIAN MAHASISWA
        </th>
    </tr>
    <tr>
        <th colspan="11" style="text-align: center; font-family: Arial; font-size: 12px; font-weight: bold;">
            BULAN {{ strtoupper($bulan) }} TAHUN {{ $tahun }}
        </th>
    </tr>
    <tr>
        <th colspan="11"></th>
    </tr>

    {{-- HEADER TABEL --}}
    <tr>
        <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NO</th>
        <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">TANGGAL MASUK</th>
        <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NAMA PEMOHON</th>
        <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">ALAMAT PEMOHON</th>

        {{-- Grouping Kolom Rekomendasi (Header Atas) --}}
        <th colspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">SURAT REKOMENDASI</th>

        <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NAMA LEMBAGA</th>

        {{-- Tempat Penelitian (Header Atas: TEMPAT) - Rowspan dihapus --}}
        <th style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">TEMPAT</th>

        <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NOMOR. BAP</th>
        <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">TANGGAL TERBIT</th>
        <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">NO. TELEPON</th>
        <th rowspan="2" style="border: 1px solid #000000; font-weight: bold; text-align: center; vertical-align: middle; background-color: #D9D9D9;">KET</th>
    </tr>
    <tr>
        {{-- Sub-Header Rekomendasi --}}
        <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #D9D9D9;">NOMOR</th>
        <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #D9D9D9;">TANGGAL</th>

        {{-- Sub-Header Tempat Penelitian (Header Bawah: PENELITIAN) --}}
        <th style="border: 1px solid #000000; font-weight: bold; text-align: center; background-color: #D9D9D9;">PENELITIAN</th>
    </tr>
    </thead>

    {{-- BODY TABEL --}}
    <tbody>
    @foreach($data as $index => $row)
        <tr>
            <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">{{ $index + 1 }}</td>

            {{-- Tanggal Masuk (Created At) --}}
            <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                {{ $row->created_at->format('d/m/Y') }}
            </td>

            {{-- Nama Pemohon (Relation User) --}}
            <td style="border: 1px solid #000000; vertical-align: top;">{{ $row->user->name ?? '-' }}</td>

            {{-- Alamat Pemohon (Relation User) --}}
            <td style="border: 1px solid #000000; vertical-align: top;">{{ $row->user->alamat ?? '-' }}</td>

            {{-- Nomor Surat (Dari Inputan User) --}}
            <td style="border: 1px solid #000000; vertical-align: top;">{{ $row->nomor_surat ?? '-' }}</td>

            {{-- Tanggal Surat (Dari Inputan User) --}}
            <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                {{ $row->tgl_surat ? \Carbon\Carbon::parse($row->tgl_surat)->format('d/m/Y') : '-' }}
            </td>

            {{-- Nama Lembaga --}}
            <td style="border: 1px solid #000000; vertical-align: top;">{{ $row->nama_lembaga ?? '-' }}</td>

            {{-- Tempat Penelitian --}}
            <td style="border: 1px solid #000000; vertical-align: top;">{{ $row->tempat_penelitian ?? '-' }}</td>

            {{-- Nomor Izin (Diisi Admin saat valid) --}}
            <td style="border: 1px solid #000000; vertical-align: top;">{{ $row->nomor_izin ?? '-' }}</td>

            {{-- Tanggal Terbit Izin (Diisi Admin saat valid) --}}
            <td style="border: 1px solid #000000; text-align: center; vertical-align: top;">
                {{ $row->tgl_terbit ? \Carbon\Carbon::parse($row->tgl_terbit)->format('d/m/Y') : 'Belum Terbit' }}
            </td>

            {{-- Nomor Telepon (Relation User) --}}
            <td style="border: 1px solid #000000; text-align: left; vertical-align: top;">
                {{ $row->user->nomor_telepon ?? '-' }}
            </td>
            <td style="border: 1px solid #000000; text-align: left; vertical-align: top;">

            </td>
        </tr>
    @endforeach
    </tbody>
</table>
