<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OwnedByUser;

class Timbangan extends Model
{
    use HasFactory;
    use OwnedByUser;

    protected $fillable = [
        'tujuan_produk_id',
        'pcs',
        'seri',
        'berat',
        'urutan',
        'warna',
    ];

    // public function tujuan()
    // {
    //     return $this->belongsTo(Tujuan::class);
    // }

    // public function produk()
    // {
    //     return $this->belongsTo(Produk::class);
    // }

    public function tujuanProduk()
    {
        return $this->belongsTo(TujuanProduk::class);
    }
}
