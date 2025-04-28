<?php

namespace App\Http\Controllers;

use App\Exports\PenjualanExport;
use App\Models\Penjualan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

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
            ->addColumn('harga', function ($penjualan) {
                return $penjualan->penjualanDetail->map(function ($detail) {
                    return $detail->produk->harga ?? '-';
                })->implode(', ');
            })

            ->addColumn('quantity', function ($penjualan) {
                return $penjualan->penjualanDetail->sum('jumlah');
            })
            ->editColumn('total_harga', function ($penjualan) {
                return 'Rp ' . format_uang($penjualan->total_harga);
            })
            ->editColumn('tanggal', function ($penjualan) {
                return tanggal_indonesia($penjualan->tanggal);
            })
            ->with([
                'total_omzet' => format_uang($totalOmzet)
            ])
            ->addColumn('aksi', function ($q) {
                $btn = '';

                // Tombol cetak faktur hanya jika status sukses
                if ($q->status == 'success') {
                    $btn .= '
        <button type="button" class="btn btn-success btn-sm" onclick="cetakFaktur(`' . route('penjualan.cetak_faktur', $q->id) . '`)">
            <i class="fas fa-print"></i>
        </button>
        ';
                }

                return $btn;
            })
            ->escapeColumns([])
            ->make(true);
    }

    // Export PDF
    public function exportPdf(Request $request)
    {
        // Inisialisasi variabel
        $tanggal = null;
        $bulan = null;
        $tahun = null;

        // Filter berdasarkan tanggal, bulan, dan tahun (optional)
        $penjualan = Penjualan::with('penjualanDetail');

        if ($request->filled('tanggal')) {
            $penjualan->whereDate('tanggal', $request->tanggal);
            $tanggal = $request->tanggal; // Menyimpan tanggal untuk ditampilkan di view
        }
        if ($request->filled('bulan')) {
            $penjualan->whereMonth('tanggal', $request->bulan);
            $bulan = $request->bulan; // Menyimpan bulan untuk ditampilkan di view
        }
        if ($request->filled('tahun')) {
            $penjualan->whereYear('tanggal', $request->tahun);
            $tahun = $request->tahun; // Menyimpan tahun untuk ditampilkan di view
        }

        // Mengambil data yang diperlukan untuk laporan
        $data = $penjualan->get();

        // Generate PDF
        $pdf = Pdf::loadView('admin.laporan.penjualan.pdf', compact('data', 'tanggal', 'bulan', 'tahun'))
            ->setPaper('a4', 'landscape'); // You can adjust paper size or orientation here

        // Membuat nama file berdasarkan waktu sekarang dengan format h:i:s
        $fileName = 'laporan_penjualan_' . now()->format('H-i-s') . '.pdf';

        // Stream the PDF to the browser with a generated filename
        return $pdf->stream($fileName);
    }

    // Export Excel
    public function exportExcel(Request $request)
    {
        // Filter berdasarkan tanggal, bulan, dan tahun (optional)
        $penjualan = Penjualan::with('penjualanDetail');

        if ($request->filled('tanggal')) {
            $penjualan->whereDate('tanggal', $request->tanggal);
        }
        if ($request->filled('bulan')) {
            $penjualan->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $penjualan->whereYear('tanggal', $request->tahun);
        }

        // Meng-export ke Excel
        return Excel::download(new PenjualanExport($penjualan->get()), 'laporan_penjualan.xlsx');
    }
}
