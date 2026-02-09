<?php

namespace App\Traits;

use App\Models\LogAktivitas;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    /**
     * Catat aktivitas ke database
     * 
     * @param string $aktivitas Deskripsi aktivitas
     * @param string|null $modul Nama modul (Peminjaman, Alat, dll)
     * @param string|null $keterangan Detail tambahan
     * @return void
     */
    protected function logActivity($aktivitas, $modul = null, $keterangan = null)
    {
        try {
            LogAktivitas::create([
                'user_id' => Auth::id(),
                'aktivitas' => $aktivitas,
                'modul' => $modul,
                'keterangan' => $keterangan,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        } catch (\Exception $e) {
            // Silent fail - jangan sampai log error mengganggu proses utama
            \Log::error('Failed to log activity: ' . $e->getMessage());
        }
    }

    /**
     * Catat aktivitas login
     */
    protected function logLogin()
    {
        $this->logActivity(
            'Login ke sistem',
            'Auth',
            'User berhasil login'
        );
    }

    /**
     * Catat aktivitas logout
     */
    protected function logLogout()
    {
        $this->logActivity(
            'Logout dari sistem',
            'Auth',
            'User berhasil logout'
        );
    }

    /**
     * Catat aktivitas CRUD
     */
    protected function logCreate($modul, $keterangan = null)
    {
        $this->logActivity("Menambah data {$modul}", $modul, $keterangan);
    }

    protected function logUpdate($modul, $keterangan = null)
    {
        $this->logActivity("Mengubah data {$modul}", $modul, $keterangan);
    }

    protected function logDelete($modul, $keterangan = null)
    {
        $this->logActivity("Menghapus data {$modul}", $modul, $keterangan);
    }

    protected function logView($modul, $keterangan = null)
    {
        $this->logActivity("Melihat data {$modul}", $modul, $keterangan);
    }
}
