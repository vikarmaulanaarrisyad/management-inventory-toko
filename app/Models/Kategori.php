<?php

namespace App\Models;

class Kategori extends Model
{
    public function produk()
    {
        return $this->hasMany(Produk::class);
    }
}
