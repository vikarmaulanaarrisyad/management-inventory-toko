<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

class Pembelian extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pembelianDetail()
    {
        return $this->hasMany(PembelianDetail::class, 'pembelian_id', 'id');
    }

    public function statusColor()
    {
        $color = '';

        switch ($this->status) {
            case 'success':
                $color = 'success';
                break;
            case 'pending':
                $color = 'warning';
                break;
            case 'cancel':
                $color = 'danger';
                break;
            default:
                break;
        }

        return $color;
    }
}
