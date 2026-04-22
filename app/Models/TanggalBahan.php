<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggalBahan extends Model
{
    protected $fillable = [
        'tanggal',
        'user_id',
    ];

    public function timbangans()
    {
        return $this->hasMany(TimbanganBahan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
