<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat peran (roles)
        $admin = Role::firstOrCreate(['name' => 'admin']);
        $karyawan = Role::firstOrCreate(['name' => 'karyawan']);

        // Daftar izin lengkap untuk admin
        $adminPermissions = [
            // Barang
            'barang.view',
            'barang.create',
            'barang.edit',
            'barang.delete',
            'barang.import',
            'barang.export',

            // Kategori Barang
            'kategori.view',
            'kategori.create',
            'kategori.edit',
            'kategori.delete',

            // Supplier
            'supplier.view',
            'supplier.create',
            'supplier.edit',
            'supplier.delete',

            // Customer
            'customer.view',
            'customer.create',
            'customer.edit',
            'customer.delete',

            // Pembelian
            'pembelian.view',
            'pembelian.create',
            'pembelian.edit',
            'pembelian.delete',
            'pembelian.print',

            // Penjualan
            'penjualan.view',
            'penjualan.create',
            'penjualan.edit',
            'penjualan.delete',
            'penjualan.print',

            // Laporan
            'laporan.stok',
            'laporan.transaksi',
            'laporan.pembelian',
            'laporan.penjualan',
            'laporan.download',

            // User Management
            'user.view',
            'user.create',
            'user.edit',
            'user.delete',
            'user.assign-role',

            // Role & Permission
            'role.view',
            'role.create',
            'role.edit',
            'role.delete',
            'permission.manage',
        ];

        // Izin terbatas untuk karyawan
        $karyawanPermissions = [
            // Barang
            'barang.view',

            // Kategori Barang
            'kategori.view',

            // Supplier
            'supplier.view',

            // Customer
            'customer.view',
            'customer.create',
            'customer.edit',

            // Pembelian
            'pembelian.view',
            'pembelian.create',
            'pembelian.print',

            // Penjualan
            'penjualan.view',
            'penjualan.create',
            'penjualan.print',

            // Laporan (terbatas)
            'laporan.stok',
            'laporan.penjualan',
        ];

        // Buat permission jika belum ada
        $allPermissions = array_unique(array_merge($adminPermissions, $karyawanPermissions));
        foreach ($allPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Berikan izin ke masing-masing role
        $admin->givePermissionTo($adminPermissions);
        $karyawan->givePermissionTo($karyawanPermissions);
    }
}
