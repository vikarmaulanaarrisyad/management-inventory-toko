<?php

namespace App\Models;

class Produk extends Model
{
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function pembelianDetails()
    {
        return $this->hasMany(PembelianDetail::class);
    }

    public function penjualanDetails()
    {
        return $this->hasMany(PenjualanDetail::class);
    }
}
