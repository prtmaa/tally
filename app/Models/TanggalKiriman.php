<?php

namespace App\Models;

use App\Traits\OwnedByUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TanggalKiriman extends Model
{
    use HasFactory;

    use OwnedByUser;

    protected $table = 'tanggal_kirimans';


    protected $fillable = ['tanggal', 'jenis', 'user_id'];

    public function tujuans()
    {
        return $this->hasMany(Tujuan::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
