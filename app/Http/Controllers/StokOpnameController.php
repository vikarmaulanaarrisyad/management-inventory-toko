<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\StokOpname;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class StokOpnameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.stok-opname.index');
    }

    public function data()
    {
        $query = StokOpname::with(['produk', 'user'])->orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('produk', function ($q) {
                return $q->produk->nama_produk ?? '-';
            })
            ->addColumn('user', function ($q) {
                return $q->user->name ?? '-';
            })
            ->addColumn('tanggal', function ($q) {
                return date('d/m/Y', strtotime($q->tanggal));
            })
            ->addColumn('selisih_badge', function ($q) {
                if ($q->selisih > 0) {
                    return '<span class="badge badge-success">+' . $q->selisih . '</span>';
                } elseif ($q->selisih < 0) {
                    return '<span class="badge badge-danger">' . $q->selisih . '</span>';
                } else {
                    return '<span class="badge badge-secondary">0</span>';
                }
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.stok-opname.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tanggal' => 'required|date',
            'produk_id' => 'required|exists:produks,id',
            'stok_fisik' => 'required|integer|min:0',
            'keterangan' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::beginTransaction();
        try {
            $produk = Produk::findOrFail($request->produk_id);
            $stok_sistem = $produk->stok;
            $stok_fisik = $request->stok_fisik;
            $selisih = $stok_fisik - $stok_sistem;

            // 1. Catat history stok opname
            StokOpname::create([
                'tanggal' => $request->tanggal,
                'produk_id' => $request->produk_id,
                'stok_sistem' => $stok_sistem,
                'stok_fisik' => $stok_fisik,
                'selisih' => $selisih,
                'keterangan' => $request->keterangan,
                'user_id' => Auth::id()
            ]);

            // 2. Update stok di tabel produk
            $produk->update([
                'stok' => $stok_fisik
            ]);

            DB::commit();

            return redirect()->route('stok-opname.index')->with('success', 'Stok Opname berhasil disimpan dan stok produk telah disesuaikan.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * API Endpoint to fetch sistem stock of a product
     */
    public function getStokSistem(Request $request)
    {
        $produk = Produk::find($request->id);
        if ($produk) {
            return response()->json(['status' => 'success', 'stok' => $produk->stok]);
        }
        return response()->json(['status' => 'error', 'message' => 'Produk tidak ditemukan'], 404);
    }
}
