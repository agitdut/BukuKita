<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Permission
        Permission::firstOrCreate(['name' => 'kelola buku']);
        Permission::firstOrCreate(['name' => 'pinjam buku']);
        Permission::firstOrCreate(['name' => 'lihat katalog']);
        Permission::firstOrCreate(['name' => 'kelola transaksi']);

        // 2. Role Petugas (semua akses)
        $petugas = Role::firstOrCreate(['name' => 'petugas']);
        $petugas->givePermissionTo(Permission::all());

        // 3. Role Mahasiswa (hanya pinjam & lihat katalog)
        $mahasiswa = Role::firstOrCreate(['name' => 'mahasiswa']);
        $mahasiswa->givePermissionTo(['pinjam buku', 'lihat katalog']);

        // 4. Buat User Petugas
        $userPetugas = User::firstOrCreate(
            ['email' => 'petugas@example.com'],
            [
                'name'     => 'Petugas Perpustakaan',
                'password' => Hash::make('password'),
            ]
        );
        $userPetugas->assignRole($petugas);

        // 5. Buat User Mahasiswa
        $userMahasiswa = User::firstOrCreate(
            ['email' => 'mahasiswa@example.com'],
            [
                'name'     => 'Mahasiswa',
                'password' => Hash::make('password'),
            ]
        );
        $userMahasiswa->assignRole($mahasiswa);
    }
}