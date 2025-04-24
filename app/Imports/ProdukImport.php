<?php

namespace App\Imports;

use App\Models\Produk;
use App\Models\Kategori;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProdukImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // Validasi dasar
        $validator = Validator::make($row, [
            'kode_produk' => 'required|string',
            'nama_produk' => 'required|string',
            'harga' => 'required|numeric',
            'stok' => 'required|integer',
            'kategori' => 'required|string',
        ]);

        if ($validator->fails()) {
            return null;
        }

        // Cari kategori berdasarkan nama
        $kategori = Kategori::where('nama', $row['kategori'])->first();

        if (!$kategori) {
            $kategori = Kategori::firstOrCreate(['nama' => $row['kategori']]);

            // return null; // bisa juga buat kategori baru di sini kalau mau
        }

        // Cek duplikat berdasarkan kode_produk
        $produk = Produk::where('kode_produk', $row['kode_produk'])->first();

        if ($produk) {
            $produk->update([
                'nama_produk' => $row['nama_produk'],
                'harga' => $row['harga'],
                'stok' => $row['stok'],
                'kategori_id' => $kategori->id,
            ]);
            return null;
        }

        // Insert baru
        return new Produk([
            'kode_produk' => $row['kode_produk'],
            'nama_produk' => $row['nama_produk'],
            'harga' => $row['harga'],
            'stok' => $row['stok'],
            'kategori_id' => $kategori->id,
        ]);
    }
}
