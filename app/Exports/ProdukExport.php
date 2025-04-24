<?php

namespace App\Exports;

use App\Models\Produk;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProdukExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * Ambil semua data produk beserta kategori.
     */
    public function collection()
    {
        return Produk::with('kategori')->get();
    }

    /**
     * Judul kolom Excel.
     */
    public function headings(): array
    {
        return [
            'Kode Produk',
            'Nama Produk',
            'Harga',
            'Stok',
            'Kategori',
        ];
    }

    /**
     * Format per baris data yang akan diekspor.
     */
    public function map($produk): array
    {
        return [
            $produk->kode_produk,
            $produk->nama_produk,
            $produk->harga,
            $produk->stok,
            optional($produk->kategori)->nama,
        ];
    }
}
