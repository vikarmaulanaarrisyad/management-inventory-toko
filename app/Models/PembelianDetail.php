<?php

namespace App\Models;

class PembelianDetail extends Model
{
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function pembelian()
    {
        return $this->belongsTo(Pembelian::class);
    }
}
