<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StokOpname extends Model
{
    protected $table = 'stok_opnames';
    protected $guarded = ['id'];

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
