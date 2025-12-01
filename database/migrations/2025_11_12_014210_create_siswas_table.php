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
    Schema::create('siswas', function (Blueprint $table) {
        $table->id();

        // Kolom 'foreign key'
        $table->foreignId('kelas_id')
              ->constrained('kelas')
              ->onDelete('cascade');

        $table->string('nis', 20)->unique(); // Nomor Induk Siswa
        $table->string('nama_siswa', 100);
        $table->enum('jenis_kelamin', ['L', 'P']);

        // Ini adalah kolom KUNCI untuk QR Code
        // Kita akan mengisinya dengan ID unik (UUID)
        $table->uuid('qrcode_token')->unique()->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswas');
    }
};
