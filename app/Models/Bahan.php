<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bahan extends Model
{
    protected $fillable = [
        'nama',
        'kode',
    ];

    public function timbangans()
    {
        return $this->hasMany(TimbanganBahan::class);
    }
}
