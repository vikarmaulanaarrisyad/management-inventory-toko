<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Pembelian;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        if (Auth::user()->hasRole('admin')) {
            // Data untuk grafik
            $totalPenjualanHarian = Penjualan::whereDate('tanggal', Carbon::today())->sum('total_harga');
            $totalPembelianHarian = Pembelian::whereDate('tanggal', Carbon::today())->sum('total_harga');
            $totalProduk = Produk::count();
            $produkTerlaris = PenjualanDetail::select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
                ->groupBy('produk_id')
                ->orderByDesc('total_terjual')
                ->limit(5)
                ->get();

            // Data untuk grafik bulanan
            $bulanIni = Carbon::now()->month;
            $penjualanPerBulan = Penjualan::whereMonth('tanggal', $bulanIni)->groupBy(DB::raw('DAY(tanggal)'))
                ->selectRaw('DAY(tanggal) as day, SUM(total_harga) as total_penjualan')
                ->get();

            $pembelianPerBulan = Pembelian::whereMonth('tanggal', $bulanIni)->groupBy(DB::raw('DAY(tanggal)'))
                ->selectRaw('DAY(tanggal) as day, SUM(total_harga) as total_pembelian')
                ->get();

            // Menambahkan data manajemen stok
            $produkHabis = Produk::where('stok', '<=', 0)->get();  // Produk dengan stok habis
            $produkStokRendah = Produk::where('stok', '<', 10)->where('stok', '>', 0)->get(); // Produk dengan stok rendah (kurang dari 10)

            $penjualanPending = Penjualan::where('status', 'pending')->count();
            $penjualanSuccess = Penjualan::where('status', 'success')->count();

            $totalCustomer = Customer::count();

            return view('admin.dashboard.index', compact(
                'totalProduk',
                'produkTerlaris',
                'totalPenjualanHarian',
                'totalPembelianHarian',
                'penjualanPerBulan',
                'pembelianPerBulan',
                'produkHabis',
                'produkStokRendah',
                'penjualanPending',
                'penjualanSuccess',
                'totalCustomer'
            ));
        } else {
            // Mendapatkan user yang sedang login
            $user = Auth::user();

            // Menghitung total produk
            $totalProduk = Produk::count();  // Filter produk berdasarkan user
            $transaksiPenjualanTerbaru = Penjualan::where('user_id', $user->id)->latest()->take(5)->get(); // Transaksi penjualan terbaru berdasarkan user

            // Menambahkan data manajemen stok berdasarkan user
            $produkHabis = Produk::where('stok', '<=', 0)->get();  // Produk dengan stok habis
            $produkStokRendah = Produk::where('stok', '<', 10)->where('stok', '>', 0)->get(); // Produk dengan stok rendah

            // Menyaring transaksi berdasarkan status dan user yang login
            $penjualanPending = Penjualan::where('user_id', $user->id)->where('status', 'pending')->count();
            $penjualanSuccess = Penjualan::where('user_id', $user->id)->where('status', 'success')->count();

            // Total penjualan hari ini untuk user yang login
            $totalPenjualanHarian = Penjualan::where('user_id', $user->id)->whereDate('tanggal', Carbon::today())->sum('total_harga');

            // Produk terlaris untuk user yang login
            $produkTerlaris = PenjualanDetail::whereHas('penjualan', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })->select('produk_id', DB::raw('SUM(jumlah) as total_terjual'))
                ->groupBy('produk_id')
                ->orderByDesc('total_terjual')
                ->limit(5)
                ->get();

            // Menghitung jumlah customer yang terhubung dengan user yang login
            $totalCustomer = Customer::count();

            return view('karyawan.dashboard.index', compact(
                'transaksiPenjualanTerbaru',
                'totalPenjualanHarian',
                'produkHabis',
                'produkStokRendah',
                'penjualanPending',
                'penjualanSuccess',
                'produkTerlaris',
                'totalProduk',
                'totalCustomer',
                'user'
            ));
        }
    }
}
