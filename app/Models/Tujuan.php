<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OwnedByUser;

class Tujuan extends Model
{
    use HasFactory;
    use OwnedByUser;

    protected $fillable = [
        'tanggal_kiriman_id',
        'nama_tujuan',
        'prod_date_1',
        'prod_date_2',
        'prod_date_3',
    ];

    public function tanggal()
    {
        return $this->belongsTo(TanggalKiriman::class, 'tanggal_kiriman_id');
    }

    public function produk()
    {
        return $this->hasMany(TujuanProduk::class);
    }
}
