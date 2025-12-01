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
    Schema::create('kehadirans', function (Blueprint $table) {
        $table->id();

        // Kolom 'foreign key'
        $table->foreignId('siswa_id')
              ->constrained('siswas')
              ->onDelete('cascade');
        
        $table->date('tanggal'); // Tanggal absensi
        $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpha']);
        $table->time('jam_masuk')->nullable(); // Opsional: jam berapa dia scan
        $table->time('jam_pulang')->nullable(); // Opsional: jika ada scan pulang

        // Ini penting: Mencegah 1 siswa punya 2 status di 1 hari
        $table->unique(['siswa_id', 'tanggal']);

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kehadirans');
    }
};
