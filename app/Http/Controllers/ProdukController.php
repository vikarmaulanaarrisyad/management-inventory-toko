<?php

namespace App\Http\Controllers;

use App\Exports\ProdukExport;
use App\Imports\ProdukImport;
use App\Models\Produk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.produk.index');
    }

    public function data()
    {
        $query = Produk::orderBy('id', 'DESC');

        return datatables($query)
            ->addIndexColumn()
            ->addColumn('kategori', function ($q) {
                return $q->kategori->nama ?? '-';
            })
            ->addColumn('harga', function ($q) {
                return format_uang($q->harga);
            })
            ->addColumn('aksi', function ($q) {
                return '
                <button onclick="editForm(`' . route('produk.show', $q->id) . '`)" class="btn btn-sm btn-primary" title="Edit"><i class="fas fa-pencil-alt"></i></button>
                <button onclick="deleteData(`' . route('produk.destroy', $q->id) . '`, `' . $q->nama . '`)" class="btn btn-sm btn-danger" title="Delete"><i class="fas fa-trash-alt"></i></button>
            ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'kategori_id' => 'required',
            'harga' => 'required|regex:/^[0-9.]+$/',
            'stok' => 'nullable',
        ], [
            'kategori_id.required' => 'Kategori wajib diisi.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = [
            'kode_produk' => $request->kode_produk,
            'nama_produk' => $request->nama_produk,
            'kategori_id' => $request->kategori_id,
            'harga' => str_replace('.', '', $request->harga),
            'stok' => $request->stok ?? 0,
        ];

        Produk::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil disimpan'
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Produk $produk)
    {
        return response()->json([
            'data' => [
                'id' => $produk->id,
                'kode_produk' => $produk->kode_produk,
                'nama_produk' => $produk->nama_produk,
                'harga' => format_uang($produk->harga),
                'stok' => $produk->stok,
                'kategori' => [
                    'id' => $produk->kategori_id,
                    'nama' => $produk->kategori->nama ?? '-'
                ]
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Produk $produk)
    {
        $validator = Validator::make($request->all(), [
            'kode_produk' => 'required',
            'nama_produk' => 'required',
            'kategori_id' => 'required',
            'harga' => 'required|regex:/^[0-9.]+$/',
            'stok' => 'nullable',
        ], [
            'kategori_id.required' => 'Kategori wajib diisi.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => 'error',
                'errors'  => $validator->errors(),
                'message' => 'Maaf, inputan yang Anda masukkan salah. Silakan periksa kembali dan coba lagi.',
            ], 422);
        }

        $data = [
            'kode_produk' => $request->kode_produk,
            'nama_produk' => $request->nama_produk,
            'kategori_id' => $request->kategori_id,
            'harga' => str_replace('.', '', $request->harga),
            'stok' => $request->stok ?? 0,
        ];

        $produk->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbaharui'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Produk $produk)
    {
        $produk->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ], 200);
    }

    // fungsi import by excel
    public function importExcel(Request $request)
    {
        // Validasi file
        $validator = Validator::make($request->all(), [
            'excelFile' => 'required|file|mimes:xlsx,xls|max:2048', // Maks 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first()
            ], 422);
        }

        try {
            // Proses import menggunakan Laravel Excel
            Excel::import(new ProdukImport, $request->file('excelFile'), null, \Maatwebsite\Excel\Excel::XLSX);

            return response()->json([
                'status' => 'success',
                'message' => 'File berhasil diupload dan diproses!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function exportExcel()
    {
        $fileName = 'data_produk_' . now()->format('Ymd_His') . '.xlsx';
        return Excel::download(new ProdukExport, $fileName);
    }

    public function exportPdf()
    {
        $produk = Produk::with('kategori')->get();
        $pdf = Pdf::loadView('admin.produk.pdf', compact('produk'))->setPaper('A4', 'landscape');

        // Ini untuk preview di browser, bukan download langsung
        return $pdf->stream('laporan_produk_' . now()->format('Ymd_His') . '.pdf');
    }
}
