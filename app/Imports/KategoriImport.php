<?php

namespace App\Imports;

use App\Models\Kategori;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KategoriImport implements ToModel, WithHeadingRow
{
    /**
     * Handle each row from Excel
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // Pastikan nama tidak kosong
        if (!isset($row['nama']) || empty(trim($row['nama']))) {
            return null; // Skip jika kosong
        }

        // Cek apakah sudah ada berdasarkan nama
        $kategori = Kategori::where('nama', trim($row['nama']))->first();

        if ($kategori) {
            // Update jika sudah ada
            $kategori->update([
                'nama' => trim($row['nama']),
            ]);
            return null; // Tidak perlu insert baru
        } else {
            // Insert baru
            return new Kategori([
                'nama' => trim($row['nama']),
            ]);
        }
    }
}
