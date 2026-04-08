<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;

trait OwnedByUser
{
    public function isOwner()
    {
        return $this->getOwnerId() === Auth::id();
    }

    public function getOwnerId()
    {
        if (isset($this->user_id)) {
            return $this->user_id;
        }

        if ($this->relationLoaded('tanggal') || method_exists($this, 'tanggal')) {
            return $this->tanggal->user_id ?? null;
        }

        if ($this->relationLoaded('tujuan') || method_exists($this, 'tujuan')) {
            return $this->tujuan->tanggal->user_id ?? null;
        }

        if ($this->relationLoaded('tujuanProduk') || method_exists($this, 'tujuanProduk')) {
            return $this->tujuanProduk->tujuan->tanggal->user_id ?? null;
        }

        return null;
    }
}
