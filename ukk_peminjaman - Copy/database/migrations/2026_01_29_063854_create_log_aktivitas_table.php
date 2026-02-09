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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('aktivitas'); // Deskripsi aktivitas (Login, Create Peminjaman, dll)
            $table->string('modul')->nullable(); // Modul terkait (Peminjaman, Alat, Kategori, dll)
            $table->text('keterangan')->nullable(); // Detail tambahan
            $table->string('ip_address', 45)->nullable(); // IP Address user
            $table->text('user_agent')->nullable(); // Browser/Device info
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
