<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('peminjamen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('alat_id')->constrained('alats')->cascadeOnDelete();
            $table->integer('jumlah');
            $table->date('tanggal_peminjaman');
            $table->date('tanggal_pengembalian_rencana');
            $table->date('tanggal_pengembalian_aktual')->nullable();
            $table->enum('status', ['menunggu','ditolak','disetujui','dipinjam','dikembalikan'])->default('menunggu');
            $table->foreignId('petugas_id')->constrained('users')->nullable()->cascadeOnDelete();
            $table->enum('status_denda',['tidak_ada', 'terkena_denda', 'denda_lunas'])->default('tidak_ada');
            $table->decimal('denda',8,2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjamen');
    }
};
