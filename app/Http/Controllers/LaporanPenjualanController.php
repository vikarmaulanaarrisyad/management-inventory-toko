<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use Illuminate\Http\Request;

class LaporanPenjualanController extends Controller
{
    public function index()
    {
        return view('admin.laporan.penjualan.index');
    }

    public function data(Request $request)
    {
        $query = Penjualan::with('penjualanDetail');

        if ($request->filled('tanggal')) {
            $query->whereDate('tanggal', $request->tanggal);
        }

        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $totalOmzet = $query->sum('total_harga');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('product_code', function ($penjualan) {
                return $penjualan->penjualanDetail->map(function ($detail) {
                    return $detail->produk->kode_produk ?? '-';
                })->implode(', ');
            })
            ->addColumn('product_name', function ($penjualan) {
                return $penjualan->penjualanDetail->map(function ($detail) {
                    return $detail->produk->nama_produk ?? '-';
                })->implode(', ');
            })
            ->addColumn('product_price', function ($penjualan) {
                return $penjualan->penjualanDetail->map(function ($detail) {
                    return $detail->produk->harga ?? '-';
                })->implode(', ');
            })

            ->addColumn('quantity', function ($penjualan) {
                return $penjualan->penjualanDetail->sum('jumlah');
            })
            ->editColumn('total_harga', function ($penjualan) {
                return format_uang($penjualan->total_harga);
            })
            ->editColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->tanggal);
            })
            ->with([
                'total_omzet' => format_uang($totalOmzet)
            ])
            ->escapeColumns([])
            ->make(true);
    }
}
