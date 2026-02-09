<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LogAktivitas;
use App\Models\User;

class LogAktivitasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            $this->command->warn('Admin user not found. Please create an admin user first.');
            return;
        }

        // Sample log data
        $logs = [
            [
                'user_id' => $admin->id,
                'aktivitas' => 'Login ke sistem',
                'modul' => 'Auth',
                'keterangan' => 'User berhasil login',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'created_at' => now()->subDays(5),
            ],
            [
                'user_id' => $admin->id,
                'aktivitas' => 'Menambah data Kategori',
                'modul' => 'Kategori',
                'keterangan' => 'Menambah kategori: Elektronik',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'created_at' => now()->subDays(4),
            ],
            [
                'user_id' => $admin->id,
                'aktivitas' => 'Menambah data Alat',
                'modul' => 'Alat',
                'keterangan' => 'Menambah alat: Laptop Dell',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => $admin->id,
                'aktivitas' => 'Menambah data Peminjaman',
                'modul' => 'Peminjaman',
                'keterangan' => 'Membuat peminjaman untuk John Doe, alat: Laptop Dell',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => $admin->id,
                'aktivitas' => 'Perubahan status peminjaman: Menunggu → Dipinjam',
                'modul' => 'Peminjaman',
                'keterangan' => 'User: John Doe, Alat: Laptop Dell',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'created_at' => now()->subDays(1),
            ],
            [
                'user_id' => $admin->id,
                'aktivitas' => 'Melihat data Log Aktivitas',
                'modul' => 'Log Aktivitas',
                'keterangan' => 'Mengakses halaman log aktivitas',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
                'created_at' => now(),
            ],
        ];

        foreach ($logs as $log) {
            LogAktivitas::create($log);
        }

        $this->command->info('Log aktivitas sample data created successfully!');
    }
}
