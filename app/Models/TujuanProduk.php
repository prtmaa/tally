<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OwnedByUser;

class TujuanProduk extends Model
{
    protected $guarded = [];
    use OwnedByUser;

    public function tujuan()
    {
        return $this->belongsTo(Tujuan::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function timbangans()
    {
        return $this->hasMany(Timbangan::class);
    }
}
