<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Route Khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // CRUD Kategori
    Route::resource('kategori', App\Http\Controllers\KategoriController::class);
    
    // Alat
    Route::get('alat/print', [App\Http\Controllers\AlatController::class, 'print'])->name('alat.print');
    Route::resource('alat', App\Http\Controllers\AlatController::class);

    // Log Aktivitas
    Route::delete('log-aktivitas/clear', [App\Http\Controllers\LogAktivitasController::class, 'clearAll'])->name('log-aktivitas.clear');
    Route::resource('log-aktivitas', App\Http\Controllers\LogAktivitasController::class)->only(['index', 'destroy']);
    
    // CRUD User (Hanya Admin)
    Route::resource('users', App\Http\Controllers\UserController::class);
});

// Route Khusus Petugas
Route::middleware(['auth', 'role:petugas'])->group(function () {
});

// Route Shared (Admin + Petugas) - Validasi/Print
Route::middleware(['auth', 'role:admin,petugas'])->group(function () {
    Route::get('peminjaman/print', [App\Http\Controllers\PeminjamanController::class, 'print'])->name('peminjaman.print');
    Route::patch('peminjaman/{id}/status', [App\Http\Controllers\PeminjamanController::class, 'updateStatus'])->name('peminjaman.updateStatus');
});


Route::middleware(['auth', 'role:admin,petugas,user'])->group(function () {
    Route::resource('peminjaman', App\Http\Controllers\PeminjamanController::class);
});


require __DIR__.'/auth.php';
