<?php

namespace App\Models;

class Penjualan extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function penjualanDetail()
    {
        return $this->hasMany(PenjualanDetail::class, 'penjualan_id', 'id');
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
