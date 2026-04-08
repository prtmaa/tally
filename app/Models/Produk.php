<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $fillable = ['nama_produk', 'kode'];

    // public function timbangans()
    // {
    //     return $this->hasMany(Timbangan::class);
    // }

    public function tujuanProduks()
    {
        return $this->hasMany(TujuanProduk::class);
    }
}
