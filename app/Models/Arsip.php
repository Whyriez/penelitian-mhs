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
        'deskripsi',
        'tgl_upload',
        'file',
        'status',
        'catatan_revisi',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
