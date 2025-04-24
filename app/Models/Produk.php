<?php

namespace App\Models;

class Produk extends Model
{
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }
}
