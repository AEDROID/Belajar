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

Route::middleware('auth', 'role:admin')->group(function () {
   Route::resource('kategori', App\Http\Controllers\KategoriController::class,);
   Route::resource('user', App\Http\Controllers\UserController::class,);
    Route::resource('alat', App\Http\Controllers\AlatController::class,);
    Route::resource('log', App\Http\Controllers\LogAktivitasController::class,);
});

require __DIR__.'/auth.php';
