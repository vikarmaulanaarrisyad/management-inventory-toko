<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Produk;
use Illuminate\Http\Request;

class PembelianDetailController extends Controller
{
    public function data($id)
    {
        $query = PembelianDetail::with(['produk', 'pembelian'])->where('pembelian_id', $id)->orderBy('id', 'desc')->get();

        $data = [];
        $total = 0;
        $total_item = 0;

        foreach ($query as $item) {
            $row = [];
            $row['kode_produk'] = '<span class="badge badge-info">' . $item->produk->kode_produk . '</span>';
            $row['nama_produk'] = $item->produk->nama_produk;
            // $row['harga'] = format_uang($item->produk->harga);
            // $row['harga'] = '<input type="text" onkeyup="format_uang(this)" name="harga" class="form-control input-xs harga" data-id="' . $item->id . '" min="0" value="' . format_uang($item->produk->harga) . '">';
            $row['harga'] = '<input type="text" onkeyup="format_uang(this); updateHarga(this)" name="harga" class="form-control input-xs harga" data-id="' . $item->id . '" value="' . format_uang($item->harga) . '">';
            $row['quantity'] = '<input type="number" name="quantity" class="form-control input-sm quantity" data-id="' . $item->id . '" min="1" value="' . $item->jumlah . '">';

            $row['total_harga'] = 'Rp. ' . format_uang($item->total_harga);
            $row['aksi'] = '
            <button class="btn btn-sm btn-danger" onclick="deleteData(`' . route('pembeliandetail.destroy', $item->id) . '`,`' . $item->produk->nama_produk . '`)"><i class="fas fa-trash"></i></button>
        ';

            $data[] = $row;

            $total += $item->produk->harga * $item->jumlah;
            $total_item += $item->jumlah;
        }

        $data[] = [
            'kode_produk' => '',
            'nama_produk' => '
            <div class="total hide">' . $total . '</div>
            <div class="total_item hide">' . $total_item . '</div>
        ',
            'harga' => '',
            'quantity' => '',
            'total_harga' => '',
            'aksi' => '',
        ];

        return datatables($data)
            ->addIndexColumn()
            ->escapeColumns([])
            ->make(true);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pembelian = Pembelian::where('status', 'pending')->first();
        $memberSelected = Customer::where('id', $pembelian->customer_id)->first();
        return view('admin.pembelian-detail.index', compact('pembelian', 'memberSelected'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $produk = Produk::where('id', $request->produk_id)->first();

        if (!$produk) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        // Cek apakah ada pembelian dengan status "pending"
        $pembelian = Pembelian::where('id', $request->pembelian_id)->where('status', 'pending')->first();

        if (!$pembelian) {
            return response()->json(['message' => 'Pembelian dengan status pending tidak ditemukan'], 404);
        }

        // Cek apakah produk sudah ada di detail pembelian
        $detail = PembelianDetail::where('pembelian_id', $pembelian->id)
            ->where('produk_id', $request->produk_id)
            ->first();

        if ($detail) {
            // Jika produk sudah ada, tambahkan jumlahnya dan perbarui subtotal
            $detail->jumlah += 1;
            $detail->total_harga = $detail->jumlah * $detail->harga;
            $detail->save();

            return response()->json(['message' => 'Jumlah produk berhasil diperbarui'], 200);
        } else {
            // Jika produk belum ada, tambahkan produk baru ke keranjang
            $detail = new PembelianDetail();
            $detail->pembelian_id = $pembelian->id;
            $detail->produk_id = $request->produk_id;
            $detail->jumlah = 1;
            $detail->harga = $produk->harga;
            $detail->total_harga = $detail->harga;
            $detail->save();

            return response()->json(['message' => 'Produk berhasil ditambahkan ke keranjang'], 200);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $detail = PembelianDetail::findOrFail($id);
        $detail->jumlah = $request->quantity;
        $detail->total_harga = $detail->produk->harga * $request->quantity;
        $detail->update();

        return response()->json(['message' => 'Detail pembelian berhasil diperbarui'], 200);
    }

    public function updateHarga(Request $request)
    {
        $detail = PembelianDetail::findOrFail($request->id); // ID dari pembelian_detail, bukan produk
        $detail->harga = $request->harga;
        $detail->total_harga = $request->harga * $detail->jumlah;
        $detail->update();

        return response()->json(['message' => 'Harga berhasil diperbarui']);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            // Hapus detail pembelian
            $pembelianDetail = PembelianDetail::find($id);
            $pembelianDetail->delete();

            return response()->json(['message' => 'Item berhasil dihapus'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal menghapus item', 'error' => $e->getMessage()], 500);
        }
    }

    public function produk()
    {
        $query = Produk::with('kategori')->get();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('harga', function ($query) {
                return format_uang($query->harga);
            })
            ->addColumn('aksi', function ($query) {
                return '
                    <button type="button" class="btn btn-xs btn-danger" onclick="pilihProduk(`' . $query->id . '`,`' . $query->nama_produk . '`)"><i class="fas fa-check-circle"></i> Pilih</button>
                ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function customer()
    {
        $query = Customer::all();

        return datatables($query)
            ->addIndexColumn()
            ->editColumn('nama_toko', function ($query) {
                return '<span class="badge badge-info">' . $query->nama_toko . '</span>';
            })
            ->addColumn('aksi', function ($query) {
                return '
                    <button type="button" class="btn btn-sm btn-danger" onclick="pilihCustomer(`' . $query->id . '`,`' . $query->nama_toko . '`)"><i class="fas fa-check-circle"></i> Pilih</button>
                ';
            })
            ->escapeColumns([])
            ->make(true);
    }

    public function loadForm($total)
    {
        $data  = [
            'totalrp' => format_uang($total),
            'bayar'   => $total,
            'bayarrp' => format_uang($total),
            'terbilang' => ucwords(terbilang($total) . ' Rupiah'),
        ];

        return response()->json($data);
    }
}
