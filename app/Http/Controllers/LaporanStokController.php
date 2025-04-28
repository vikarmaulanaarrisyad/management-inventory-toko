<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PembelianDetail;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanStokController extends Controller
{
    public function index(Request $request)
    {
        $start = now()->subDay(30)->format('Y-m-d');
        $end = date('Y-m-d');

        if ($request->has('start') && $request->start != "" && $request->has('end') && $request->end != "") {
            $start = $request->start;
            $end = $request->end;
        }

        return view('admin.laporan.stok.index', compact('start', 'end'));
    }


    public function getData1($start, $end, $escape = false)
    {
        $data = [];
        $i = 1;
        $separate = $escape ? ',-' : '';

        // 1. Default 1 bulan ke belakang jika tidak ada parameter
        if (!$start || !$end) {
            $start = now()->subMonth()->toDateString();
            $end = now()->toDateString();
        } else {
            $start = Carbon::parse($start)->toDateString();
            $end = Carbon::parse($end)->toDateString();
        }

        // 2. Ambil semua data pembelian
        $pembelianDetails = PembelianDetail::select(
            DB::raw('DATE(pembelians.tanggal) as tanggal'),
            'produks.nama_produk',
            DB::raw('SUM(pembelian_details.jumlah) as stok_masuk')
        )
            ->join('pembelians', 'pembelian_details.pembelian_id', '=', 'pembelians.id')
            ->join('produks', 'pembelian_details.produk_id', '=', 'produks.id')
            ->whereBetween('pembelians.tanggal', [$start, $end])
            ->groupBy(DB::raw('DATE(pembelians.tanggal)'), 'produks.nama_produk')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal . '_' . $item->nama_produk;
            });

        // 3. Ambil semua data penjualan
        $penjualanDetails = PenjualanDetail::select(
            DB::raw('DATE(penjualans.tanggal) as tanggal'),
            'produks.nama_produk',
            DB::raw('SUM(penjualan_details.jumlah) as stok_keluar')
        )
            ->join('penjualans', 'penjualan_details.penjualan_id', '=', 'penjualans.id')
            ->join('produks', 'penjualan_details.produk_id', '=', 'produks.id')
            ->whereBetween('penjualans.tanggal', [$start, $end])
            ->groupBy(DB::raw('DATE(penjualans.tanggal)'), 'produks.nama_produk')
            ->get()
            ->groupBy(function ($item) {
                return $item->tanggal . '_' . $item->nama_produk;
            });

        // 4. Dapatkan semua nama produk unik
        $allProduk = collect(
            array_merge(
                $pembelianDetails->pluck('first.nama_produk')->toArray(),
                $penjualanDetails->pluck('first.nama_produk')->toArray()
            )
        )->unique();

        // Debug: Menampilkan produk yang ditemukan
        dd($allProduk); // Debug untuk memastikan $allProduk terisi produk

        // 5. Loop semua tanggal dari start ke end
        $currentDate = Carbon::parse($start);

        $runningStock = [];

        while ($currentDate->lte($end)) {
            $tanggal = $currentDate->format('Y-m-d');

            foreach ($allProduk as $produk) {
                $key = $tanggal . '_' . $produk;

                $stokMasukHariIni = $pembelianDetails->has($key)
                    ? $pembelianDetails[$key]->sum('stok_masuk')
                    : 0;

                $stokKeluarHariIni = $penjualanDetails->has($key)
                    ? $penjualanDetails[$key]->sum('stok_keluar')
                    : 0;

                if (!isset($runningStock[$produk])) {
                    $runningStock[$produk] = 0;
                }

                $runningStock[$produk] += $stokMasukHariIni;
                $runningStock[$produk] -= $stokKeluarHariIni;

                // Tambahkan ke data (tampilkan semua tanggal meski kosong)
                $data[] = [
                    'DT_RowIndex' => $i++,
                    'tanggal' => $currentDate->format('Y-m-d'),
                    'nama_produk' => $produk,
                    'stok_masuk' => format_uang($stokMasukHariIni) . $separate,
                    'stok_keluar' => format_uang($stokKeluarHariIni) . $separate,
                    'sisa_stok' => format_uang($runningStock[$produk]) . $separate,
                ];
            }

            $currentDate->addDay();
        }

        return $data;
    }

    public function getData($start, $end, $escape = false)
    {
        $data = [];
        $i = 1;
        $separate = $escape ? ',-' : '';

        // 1. Default 1 bulan ke belakang jika tidak ada parameter
        if (!$start || !$end) {
            $start = now()->subMonth()->toDateString();
            $end = now()->toDateString();
        } else {
            $start = Carbon::parse($start)->toDateString();
            $end = Carbon::parse($end)->toDateString();
        }

        // 2. Ambil semua nama produk unik
        $allProduk = Produk::with([
            'pembelianDetails' => function ($query) use ($start, $end) {
                $query->join('pembelians', 'pembelians.id', '=', 'pembelian_details.pembelian_id')
                    ->whereBetween('pembelians.tanggal', [$start, $end]); // Pastikan join dengan pembelians
            },
            'penjualanDetails' => function ($query) use ($start, $end) {
                $query->join('penjualans', 'penjualans.id', '=', 'penjualan_details.penjualan_id')
                    ->whereBetween('penjualans.tanggal', [$start, $end]); // Pastikan join dengan penjualans
            }
        ])->get();

        // 3. Loop semua tanggal dari start ke end
        $currentDate = Carbon::parse($start);

        $runningStock = [];

        while ($currentDate->lte($end)) {
            $tanggal = $currentDate->format('Y-m-d');

            foreach ($allProduk as $produk) {
                // Filter pembelianDetails dan penjualanDetails untuk tanggal tertentu
                $stokMasukHariIni = $produk->pembelianDetails->filter(function ($item) use ($tanggal) {
                    return $item->tanggal == $tanggal;
                })->sum('jumlah');

                $stokKeluarHariIni = $produk->penjualanDetails->filter(function ($item) use ($tanggal) {
                    return $item->tanggal == $tanggal;
                })->sum('jumlah');

                if (!isset($runningStock[$produk->id])) {
                    $runningStock[$produk->id] = 0;
                }

                $runningStock[$produk->id] += $stokMasukHariIni;
                $runningStock[$produk->id] -= $stokKeluarHariIni;

                // Tambahkan ke data (tampilkan semua tanggal meski kosong)
                $data[] = [
                    'DT_RowIndex' => $i++,
                    'tanggal' => $tanggal,
                    'nama_produk' => $produk->nama_produk,
                    'stok_masuk' => format_uang($stokMasukHariIni) . $separate,
                    'stok_keluar' => format_uang($stokKeluarHariIni) . $separate,
                    'sisa_stok' => format_uang($runningStock[$produk->id]) . $separate,
                ];
            }

            $currentDate->addDay();
        }

        return $data;
    }



    public function data($start, $end)
    {
        $data = $this->getData($start, $end);

        return datatables($data)
            ->escapeColumns([])
            ->make(true);
    }
}
