<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'username' => 'admin',
                'password' => Hash::make('password'), // ganti password untuk produksi
            ]
        );

        // Karyawan User
        $karyawan = User::firstOrCreate(
            ['email' => 'karyawan@example.com'],
            [
                'name' => 'Karyawan',
                'username' => 'karyawan',
                'password' => Hash::make('password'), // ganti password untuk produksi
            ]
        );

        // Assign roles
        $admin->assignRole('admin');
        $karyawan->assignRole('karyawan');
    }
}
