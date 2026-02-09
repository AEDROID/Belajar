<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Models\Alat;
use App\Models\User;
use Carbon\Carbon;
use App\Traits\LogsActivity;

class PeminjamanController extends Controller
{
    use LogsActivity;

    public function index()
    {
        $peminjamans = Peminjaman::latest()->get();
        return view('peminjaman.index', compact('peminjamans'));
    }

    public function create()
    {
        $users = User::where('role', 'user')->get();
        $alats = Alat::where('stok', '>', 0)->get();
        return view('peminjaman.create', compact('users', 'alats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required',
            'user_id' => 'required',
            'jumlah' => 'required|numeric|min:1',
            'tanggal_peminjaman' => 'required|date',
            'tanggal_pengembalian_rencana' => 'required|date',
        ]);

        $alat = Alat::findOrFail($request->alat_id);

        if ($alat->stok < $request->jumlah) {
            return back()->with('error', 'Stok habis!');
        }

        // Kurangi Stok
        $alat->decrement('stok', $request->jumlah);

        // Buat Peminjaman
        Peminjaman::create([
            'user_id' => $request->user_id,
            'alat_id' => $request->alat_id,
            'jumlah' => $request->jumlah,
            'tanggal_peminjaman' => $request->tanggal_peminjaman,
            'tanggal_pengembalian_rencana' => $request->tanggal_pengembalian_rencana,
            'status' => 'menunggu',
            'status_denda' => 'tidak_ada',
            'denda' => 0,
            'petugas_id' => auth()->id(),
        ]);

        $this->logCreate('Peminjaman', "Pinjam {$alat->nama_alat}");

        return redirect()->route('peminjaman.index')->with('success', 'Berhasil meminjam!');
    }

    public function edit($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        return view('peminjaman.edit', compact('peminjaman'));
    }

    public function update(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $peminjaman->update($request->all());
        
        $this->logUpdate('Peminjaman', "Update peminjaman ID $id");

        return redirect()->route('peminjaman.index')->with('success', 'Berhasil diupdate!');
    }

    public function updateStatus(Request $request, $id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        $statusBaru = $request->status;
        $alat = $peminjaman->alat;

        // 1. Jika Bayar Denda
        if ($request->status_denda == 'denda_lunas') {
            $peminjaman->update(['status_denda' => 'denda_lunas']);
            return back()->with('success', 'Denda lunas!');
        }

        // 2. Jika Ditolak -> Balikin Stok
        if ($statusBaru == 'ditolak') {
            $alat->increment('stok', $peminjaman->jumlah);
        }

        // 3. Jika Dikembalikan -> Balikin Stok & Hitung Denda
        if ($statusBaru == 'dikembalikan') {
            $alat->increment('stok', $peminjaman->jumlah);
            
            $peminjaman->tanggal_pengembalian_aktual = date('Y-m-d');
            $tglRencana = Carbon::parse($peminjaman->tanggal_pengembalian_rencana);
            $tglAktual  = Carbon::parse($peminjaman->tanggal_pengembalian_aktual);

            if ($tglAktual->gt($tglRencana)) {
                $telat = $tglAktual->diffInDays($tglRencana);
                $peminjaman->denda = $telat * $alat->denda_per_hari;
                $peminjaman->status_denda = 'terkena_denda';
            }
        }

        $peminjaman->status = $statusBaru;
        $peminjaman->save();

        $this->logActivity('Status', 'Peminjaman', "Status jadi $statusBaru");

        return back()->with('success', 'Status berubah!');
    }

    public function destroy($id)
    {
        $peminjaman = Peminjaman::findOrFail($id);
        
        // Kembalikan stok kalau statusnya 'menunggu' atau 'dipinjam' (belum kembali)
        if ($peminjaman->status == 'menunggu' || $peminjaman->status == 'dipinjam') {
            $peminjaman->alat->increment('stok', $peminjaman->jumlah);
        }
        
        $peminjaman->delete();
        $this->logDelete('Peminjaman', "Hapus ID $id");

        return redirect()->route('peminjaman.index')->with('success', 'Dihapus!');
    }

    public function print()
    {
        $peminjamans = Peminjaman::all();
        return view('peminjaman.print', compact('peminjamans'));
    }
}
