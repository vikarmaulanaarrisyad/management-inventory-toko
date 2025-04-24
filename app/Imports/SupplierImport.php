<?php

namespace App\Imports;

use App\Models\Supplier;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SupplierImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Pastikan 'nama' tidak kosong
        if (empty(trim($row['nama']))) {
            return null;
        }

        // Cek apakah sudah ada berdasarkan nama
        $supplier = Supplier::where('nama', trim($row['nama']))->first();

        if ($supplier) {
            // Jika sudah ada, update data
            $supplier->update([
                'nama' => trim($row['nama']),
                'alamat' => trim($row['alamat']),
                'nomorhp' => trim($row['nomor']),
                // Tambahkan kolom lain yang perlu diupdate
            ]);
            return null; // Tidak perlu mengembalikan data baru
        } else {
            // Jika belum ada, simpan data baru
            return new Supplier([
                'nama' => trim($row['nama']),
                'alamat' => trim($row['alamat']),
                'nomorhp' => trim($row['nomor']),
                // Tambahkan kolom lain jika perlu, misalnya:
            ]);
        }
    }
}
