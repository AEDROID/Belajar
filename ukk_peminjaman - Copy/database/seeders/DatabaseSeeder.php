<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Buat User Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // 2. Buat User Petugas
        User::create([
            'name' => 'Petugas Rajin',
            'email' => 'petugas@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'petugas',
        ]);

        // 3. Buat User Peminjam
        User::create([
            'name' => 'Siswa Teladan',
            'email' => 'siswa@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // 4. Buat Kategori Dummy
        $kategori1 = \App\Models\kategori::create(['nama_kategori' => 'Elektronik']);
        $kategori2 = \App\Models\kategori::create(['nama_kategori' => 'Kendaraan']);

        // 5. Buat Alat Dummy
        \App\Models\alat::create([
            'kategori_id' => $kategori1->id,
            'nama_alat' => 'Laptop ROG',
            'deskripsi' => 'Laptop gaming spek dewa',
            'stok' => 5,
            'denda_per_hari' => 50000,
        ]);
        
        \App\Models\alat::create([
            'kategori_id' => $kategori1->id,
            'nama_alat' => 'Kamera DSLR',
            'deskripsi' => 'Kamera buat dokumentasi',
            'stok' => 3,
            'denda_per_hari' => 25000,
        ]);
    }
}
