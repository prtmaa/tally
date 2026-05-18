<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\OwnedByUser;



class TanggalBahan extends Model
{
    use OwnedByUser;

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
