<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Arsip extends Model
{
    use HasFactory;
    protected $table = 'arsip';
    protected $fillable = [
        'id',
        'user_id',
        'nama',
        'nama_lembaga',
        'tempat_penelitian',
        'nomor_surat',
        'tgl_surat',
        'nomor_izin',
        'tgl_terbit',
        'file',
        'status',
        'catatan_revisi',
        'file_revisi',
    ];

    protected $casts = [
        'file' => 'array',
        'tgl_upload' => 'date',
        'file_revisi' => 'array',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
