<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str; // Tambahkan ini di bagian atas file jika belum ada

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pembelian.index');
    }

    public function data()
    {
        $user = Auth::user()->id;

        $query = Pembelian::where('user_id', $user)
            ->orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($q) {
                return date('d-m-Y', strtotime($q->tanggal));
            })
            ->addColumn('supplier', function ($q) {
                return $q->supplier->nama_toko ?? '-';
            })
            ->addColumn('total_harga', function ($q) {
                return format_uang($q->total_harga);
            })
            ->addColumn('status', function ($q) {
                return '<span class="badge badge-' . $q->statusColor() . '">' . $q->status . '</span>';
            })
            ->addColumn('karyawan', function ($q) {
                return $q->user->name;
            })
            ->addColumn('aksi', function ($q) {
                $btn = '';

                // Tombol cetak faktur hanya kalau status sukses
                if ($q->status == 'success') {
                    $btn .= '
            <button type="button" class="btn btn-success btn-sm" onclick="cetakFaktur(`' . route('pembelian.cetak_faktur', $q->id) . '`)">
                <i class="fas fa-print"></i>
            </button>
        ';
                }

                // Tombol lihat detail selalu muncul
                $btn .= '
        <button onclick="showDetail(`' . route('pembelian.show', $q->id) . '`)" class="btn btn-sm btn-info">
            <i class="fa fa-eye"></i>
        </button>
    ';

                // Tombol hapus kalau status belum sukses
                if ($q->status != 'success') {
                    $btn .= '
            <button onclick="deleteData(`' . route('pembelian.destroy', $q->id) . '`,`' . $q->invoice_number . '`)" class="btn btn-sm btn-danger">
                <i class="fa fa-trash"></i>
            </button>
        ';
                }

                return $btn;
            })

            ->escapeColumns([])
            ->make(true);
    }

    public function create()
    {
        // Check if the user already has a pending pembelian
        $pembelian = Pembelian::where('status', 'pending')
            ->where('user_id', Auth::id())
            ->first();

        if ($pembelian) {
            $memberSelected = Supplier::find($pembelian->supplier_id);

            return redirect()->route('pembeliandetail.index', [
                'pembelian' => $pembelian->id,
                'memberSelected' => optional($memberSelected)->id
            ])->with([
                'error' => true,
                'message' => 'Transaksi pembelian sedang berlangsung'
            ]);
        }

        // Buat invoice number random
        $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        // Create a new pembelian
        $pembelian = new Pembelian();
        $pembelian->tanggal = now();
        $pembelian->user_id = Auth::id();
        $pembelian->invoice_number = $invoiceNumber; // or 0 if needed
        $pembelian->supplier_id = null;    // or 0 if needed
        $pembelian->total_item = 0;
        $pembelian->total_harga = 0;
        $pembelian->status = 'pending';
        $pembelian->save();

        return redirect()->route('pembeliandetail.index', ['pembelian' => $pembelian->id]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $pembelianDetail = PembelianDetail::where('pembelian_id', $request->pembelian_id)->get();

        if ($pembelianDetail->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak ada produk yang ditemukan.'
            ], 400); // atau 422
        }

        $pembelian = Pembelian::findOrfail($request->pembelian_id);
        $pembelian->supplier_id = $request->supplier ?? 1;
        $pembelian->total_item = $request->total_item;
        $pembelian->total_harga = $request->total;
        $pembelian->status = 'success';
        $pembelian->update();

        $pembelianDetail = PembelianDetail::where('pembelian_id', $request->pembelian_id)->get();
        foreach ($pembelianDetail as $item) {
            $produk = Produk::findOrfail($item->produk_id);
            $produk->stok += $item->jumlah;
            $produk->update();
        }

        return redirect()->route('pembelian.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembelian $pembelian)
    {
        $query = PembelianDetail::with(['produk'])->where('pembelian_id', $pembelian->id)->get();

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('kode_produk', function ($query) {
                return $query->produk->kode_produk;
            })
            ->addColumn('nama_produk', function ($query) {
                return $query->produk->nama_produk;
            })
            ->addColumn('harga_lama', function ($query) {
                return format_uang($query->produk->harga);
            })
            ->addColumn('harga', function ($query) {
                return format_uang($query->harga);
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

    /**
     * Show the form for editing the specified resource.
     */
    public function cetakFaktur(Pembelian $pembelian)
    {
        $pembelian->load('pembelianDetail');
        return view('admin.pembelian.cetak_faktur', compact('pembelian'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        $detail  = PembelianDetail::where('pembelian_id', $pembelian->id)->get();

        foreach ($detail as $item) {
            $produk = Produk::findOrfail($item->produk_id);
            if ($produk) {
                $produk->stok -= $item->quantity;
                $produk->update();
            }

            $item->delete();
        }

        $pembelian->delete();

        return response()->json(['message' => 'Data Berhasil Dihapus', 'data' => null]);
    }
}
