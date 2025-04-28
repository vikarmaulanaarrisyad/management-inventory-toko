<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.penjualan.index');
    }

    public function data()
    {
        $user = Auth::user();
        if ($user->hasRole('karyawan')) {
            $query = Penjualan::with('customer', 'user')
                ->where('user_id', $user->id)
                ->orderBy('id', 'desc');
        }
        $query = Penjualan::with('customer', 'user')
            ->orderBy('id', 'desc');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('tanggal', function ($query) {
                return tanggal_indonesia($query->tanggal);
            })
            ->editColumn('invoice_number', function ($query) {
                return '<span class="badge badge-success">' . $query->invoice_number . '</span>';
            })
            ->editColumn('total_harga', function ($query) {
                return format_uang($query->total_harga);
            })
            ->editColumn('user', function ($query) {
                return $query->user->name ?? '';
            })
            ->editColumn('customer', function ($query) {
                $namaToko = $query->customer->nama_toko ?? '';
                $namaCustomer = $query->customer->nama ?? '';

                if ($namaToko || $namaCustomer) {
                    return "{$namaToko} - {$namaCustomer}";
                }

                return '';
            })
            ->addColumn('status', function ($q) {
                return '<span class="badge badge-' . $q->statusColor() . '">' . $q->status . '</span>';
            })
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

                // Tombol transaksi sebelumnya (lanjutkan) jika pending
                if ($q->status == 'pending') {
                    $btn .= '
        <button type="button" class="btn btn-warning btn-sm" onclick="lanjutkanTransaksi(`' . route('penjualan.edit', $q->id) . '`)">
            <i class="fas fa-forward"></i>
        </button>
        ';
                }

                // Tombol lihat detail selalu tampil
                $btn .= '
        <button onclick="showDetail(`' . route('penjualan.show', $q->id) . '`)" class="btn btn-sm btn-info">
            <i class="fa fa-eye"></i>
        </button>
    ';

                // Tombol hapus jika belum sukses
                if ($q->status != 'success') {
                    $btn .= '
        <button onclick="deleteData(`' . route('penjualan.destroy', $q->id) . '`,`' . $q->invoice_number . '`)" class="btn btn-sm btn-danger">
            <i class="fa fa-trash"></i>
        </button>
        ';
                }

                return $btn;
            })

            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if the user already has a pending
        $penjualan = Penjualan::where('status', 'pending')
            ->where('user_id', Auth::id())
            ->first();

        if ($penjualan) {
            $memberSelected = Customer::find($penjualan->customer_id);

            return redirect()->route('penjualandetail.index', [
                'penjualan' => $penjualan->id,
                'memberSelected' => optional($memberSelected)->id
            ])->with([
                'error' => true,
                'message' => 'Transaksi penjualan sedang berlangsung'
            ]);
        }

        // Buat invoice number random
        $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));

        // Create a new pembelian
        $penjualan = new Penjualan();
        $penjualan->tanggal = now();
        $penjualan->user_id = Auth::id();
        $penjualan->invoice_number = $invoiceNumber; // or 0 if needed
        $penjualan->customer_id = null;    // or 0 if needed
        $penjualan->total_item = 0;
        $penjualan->total_harga = 0;
        $penjualan->status = 'pending';
        $penjualan->save();

        return redirect()->route('penjualandetail.index', ['penjualan' => $penjualan->id]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $penjualan = Penjualan::findOrfail($request->penjualan_id);
        $penjualan->customer_id = $request->customer_id;
        $penjualan->total_item = $request->total_item;
        $penjualan->total_harga = $request->total;
        $penjualan->status = 'success';
        $penjualan->update();

        $penjualanDetail = PenjualanDetail::where('penjualan_id', $request->penjualan_id)->get();
        foreach ($penjualanDetail as $item) {
            $produk = Produk::findOrfail($item->produk_id);
            $produk->stok -= $item->quantity;
            $produk->update();
        }

        return redirect()->route('penjualan.index');
    }

    /**
     * Display the specified resource.
     */
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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Penjualan $penjualan)
    {
        return redirect()->route('penjualandetail.index', ['penjualan' => $penjualan->id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penjualan $penjualan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        $detail  = PenjualanDetail::where('penjualan_id', $penjualan->id)->get();

        foreach ($detail as $item) {
            $produk = Produk::findOrfail($item->produk_id);
            if ($produk) {
                $produk->stok += $item->quantity;
                $produk->update();
            }

            $item->delete();
        }

        $penjualan->delete();

        return response()->json(['message' => 'Data Berhasil Dihapus', 'data' => null]);
    }

    public function cetakFaktur(Penjualan $penjualan)
    {
        $penjualan->load('penjualanDetail');
        return view('admin.penjualan.cetak_faktur', compact('penjualan'));
    }
}
