<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alat;
use App\Models\Kategori;

class AlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alats = Alat::with('kategori')->get();
        return view('alat.index', compact('alats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kategoris = Kategori::all();
        return view('alat.create', compact('kategoris'));
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_alat'=>'required|string|max:255',
            'kategori_id'=>'required|exists:kategoris,id',
            'deskripsi'=>'required|string',
            'stok'=>'required|integer|min:0',
            'denda_per_hari'=>'required|integer|max:9999999',
        ]);

        $alat = Alat::create($request->all());

        return redirect()->route('alat.index')->with('success', 'alat berhasil ditambahkan!');
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
        $alat = Alat::findOrfail($id);
        $kategoris = kategori::all();
        return view('alat.edit', compact('alat', 'kategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_alat'=>'required|string|max:255',
            'kategori_id'=>'required|exists:kategoris,id',
            'deskripsi'=>'required|string',
            'stok'=>'required|integer|min:0',
            'denda_per_hari'=>'required|integer|max:9999999',
        ]);

        $alat = Alat::findOrfail($id);
        $alat->update($request->all());

        return redirect()->route('alat.index')->with('success', 'alat berhasil diperbaharui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $alat = Alat::findOrfail($id)->delete();
         return redirect()
        ->route('alat.index')
        ->with('success', 'Alat berhasil dihapus!');
    }
}
