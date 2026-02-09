<?php

namespace App\Http\Controllers;

use App\Models\kategori;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kategoris = kategori::all();
        $log = new LogAktivitasController();
        $log->store('Melihat', 'Melihat Index Kategori');
        return view('kategori.index', compact('kategoris'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kategori.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_kategori'=>'required|string|max:255',
        ]);

        kategori::create($request->all());
        $log = new LogAktivitasController();
        $log->store('Menambah', 'Menambahkan Kategori');
        return redirect()->route('kategori.index')->with('success','data berhasil ditambahkan!');
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
        $kategori = kategori::findOrfail($id);
        return view('kategori.edit', compact('kategori'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama_kategori'=>'required|string|max:255',
        ]);
        
        $kategori = kategori::findOrfail($id);
        $kategori->update($request->all());
        return redirect()->route('kategori.index')->with('success','data berhasil ditambahkan!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kategori = kategori::findOrfail($id)->delete();

        return redirect()->route('kategori.index')->with('success','data berhasil dihapus!');
    }
}
