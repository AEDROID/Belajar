<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LogAktivitas;
use App\Traits\LogsActivity;

class LogAktivitasController extends Controller
{
    use LogsActivity;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Log aktivitas melihat halaman log
        $this->logView('Log Aktivitas', 'Mengakses halaman log aktivitas');

        $query = LogAktivitas::with('user')->orderBy('created_at', 'desc');

        // Filter berdasarkan modul
        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_mulai')) {
            $query->whereDate('created_at', '>=', $request->tanggal_mulai);
        }

        if ($request->filled('tanggal_selesai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_selesai);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('aktivitas', 'like', "%{$search}%")
                  ->orWhere('keterangan', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $logs = $query->paginate(20);

        // Ambil daftar modul unik untuk filter
        $moduls = LogAktivitas::distinct()->pluck('modul')->filter();

        return view('log-aktivitas.index', compact('logs', 'moduls'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $log = LogAktivitas::findOrFail($id);
        
        // Log sebelum hapus
        $this->logDelete('Log Aktivitas', "Menghapus log: {$log->aktivitas}");
        
        $log->delete();

        return redirect()->route('log-aktivitas.index')
            ->with('success', 'Log aktivitas berhasil dihapus');
    }

    /**
     * Clear all logs (optional - untuk admin)
     */
    public function clearAll()
    {
        $this->logActivity('Menghapus semua log aktivitas', 'Log Aktivitas', 'Semua log dibersihkan');
        
        LogAktivitas::truncate();

        return redirect()->route('log-aktivitas.index')
            ->with('success', 'Semua log aktivitas berhasil dihapus');
    }
}
 