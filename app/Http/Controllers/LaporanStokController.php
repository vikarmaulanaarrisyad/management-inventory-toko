<?php

namespace App\Http\Controllers;

use App\Models\PembelianDetail;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Illuminate\Http\Request;

class LaporanStokController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal filter dari request (jika tidak ada, pakai tanggal sekarang)
        $startDate = $request->input('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', now()->toDateString());

        $produks = Produk::all()
            ->map(function ($item) use ($startDate, $endDate) {
                // Cari stok masuk dari tabel pembelian_detail dalam rentang tanggal
                $stokMasuk = PembelianDetail::where('produk_id', $item->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('jumlah');

                // Cari stok keluar dari tabel penjualan_detail dalam rentang tanggal
                $stokKeluar = PenjualanDetail::where('produk_id', $item->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->sum('jumlah');

                // Simpan nilai ke dalam objek produk
                $item->stok_masuk = $stokMasuk;
                $item->stok_keluar = $stokKeluar;
                $item->stok_akhir = $stokMasuk - $stokKeluar;

                return $item;
            });

        // Kirim data produk dan tanggal yang difilter ke view
        return view('admin.laporan.stok', compact('produks', 'startDate', 'endDate'));
    }
}
