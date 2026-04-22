<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimbanganBahan extends Model
{
    protected $fillable = [
        'tanggal_bahan_id',
        'bahan_id',
        'pcs',
        'berat',
        'urutan',
    ];

    public function tanggal()
    {
        return $this->belongsTo(TanggalBahan::class, 'tanggal_bahan_id');
    }

    public function bahan()
    {
        return $this->belongsTo(Bahan::class);
    }
}
