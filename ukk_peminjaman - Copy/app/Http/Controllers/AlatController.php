<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alat;
use App\Models\Kategori;
use App\Traits\LogsActivity;

class AlatController extends Controller
{
    use LogsActivity;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->logView('Alat', 'Mengakses halaman daftar alat');
        
        // Mengambil semua data alat beserta kategorinya
        $alats = Alat::with('kategori')->get();
        return view('alat.index', compact('alats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Mengambil data kategori untuk dropdown
        $kategoris = Kategori::all();
        return view('alat.create', compact('kategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id', // pastikan nama tabel benar (jamak)
            'deskripsi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'denda_per_hari' => 'required|integer|max:999999',
        ]);

        $alat = Alat::create($request->all());
        
        $this->logCreate('Alat', "Menambah alat: {$alat->nama_alat}");

        return redirect()->route('alat.index')->with('success', 'Alat berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $alat = Alat::findOrFail($id);
        $kategoris = Kategori::all();
        return view('alat.edit', compact('alat', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_alat' => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategoris,id',
            'deskripsi' => 'required|string',
            'stok' => 'required|integer|min:0',
            'denda_per_hari' => 'required|integer|min:0',
        ]);

        $alat = Alat::findOrFail($id);
        $alat->update($request->all());
        
        $this->logUpdate('Alat', "Mengubah alat: {$alat->nama_alat}");

        return redirect()->route('alat.index')->with('success', 'Alat berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $alat = Alat::findOrFail($id);
        $namaAlat = $alat->nama_alat;
        $alat->delete();
        
        $this->logDelete('Alat', "Menghapus alat: {$namaAlat}");

        return redirect()->route('alat.index')->with('success', 'Alat berhasil dihapus!');
    }

    public function print()
    {
        $alats = Alat::with('kategori')->get();
        return view('alat.print', compact('alats'));
    }
}
