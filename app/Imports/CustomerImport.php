<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomerImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Pastikan 'nama' tidak kosong
        if (empty(trim($row['nama'])) && empty(trim($row['nama_toko']))) {
            return null;
        }

        // Cek apakah sudah ada berdasarkan nama
        $customer = Customer::where('nama_toko', trim($row['nama_toko']))->first();

        if ($customer) {
            // Jika sudah ada, update data
            $customer->update([
                'nama_toko' => trim($row['nama_toko']),
                'nama' => trim($row['nama']),
                'alamat' => trim($row['alamat']),
                'nomorhp' => trim($row['nomor']),
                // Tambahkan kolom lain yang perlu diupdate
            ]);
            return null; // Tidak perlu mengembalikan data baru
        } else {
            // Jika belum ada, simpan data baru
            return new Customer([
                'nama_toko' => trim($row['nama_toko']),
                'nama' => trim($row['nama']),
                'alamat' => trim($row['alamat']),
                'nomorhp' => trim($row['nomor']),
                // Tambahkan kolom lain jika perlu, misalnya:
            ]);
        }
    }
}
