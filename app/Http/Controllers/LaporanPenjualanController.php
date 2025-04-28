<?php

namespace App\Http\Controllers;

use App\Exports\PenjualanExport;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
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
        $query = Penjualan::with('penjualanDetail')->orderBy('tanggal', 'desc');

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
            ->addColumn('invoice', function ($penjualan) {
                return $penjualan->invoice_number;
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

                // Tombol lihat detail selalu tampil
                $btn .= '
        <button onclick="showDetail(`' . route('penjualan.show', $q->id) . '`)" class="btn btn-sm btn-info">
            <i class="fa fa-eye"></i>
        </button>
    ';

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

    public function show(Penjualan $penjualan)
    {
        $query = PenjualanDetail::with(['produk'])->where('penjualan_id', $penjualan->id)->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($query) {
                return $query->produk->kode_produk;
            })
            ->addColumn('nama_produk', function ($query) {
                return $query->produk->nama_produk;
            })
            ->addColumn('harga', function ($query) {
                return format_uang($query->produk->harga);
            })
            ->addColumn('jumlah', function ($query) {
                return format_uang($query->jumlah);
            })
            ->addColumn('total_harga', function ($query) {
                return 'Rp. ' . format_uang($query->total_harga);
            })
            ->escapeColumns([])
            ->make(true);
    }
}
