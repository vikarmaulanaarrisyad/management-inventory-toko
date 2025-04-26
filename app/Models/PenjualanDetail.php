<?php

namespace App\Models;

class PenjualanDetail extends Model
{
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'penjualan_id', 'id');
    }
}
