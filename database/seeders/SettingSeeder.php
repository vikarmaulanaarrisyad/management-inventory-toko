<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->updateOrInsert(
            ['id' => 1], // <-- kondisi pencarian, biasanya ID 1 untuk setting utama
            [
                'nama_toko'  => 'Inventory App',
                'nama'       => 'Inventory Admin',
                'nomor'      => '081234567890',
                'tentang'    => 'Aplikasi untuk mengelola inventory, pembelian, dan penjualan.',
                'deskripsi'  => 'Inventory App membantu Anda mengatur stok barang dengan mudah dan cepat.',
                'logo'       => 'logo.jpg',
                'logo_login' => 'logo_login.jpg',
                'favicon'    => 'favicon.jpg',
            ]
        );
    }
}
