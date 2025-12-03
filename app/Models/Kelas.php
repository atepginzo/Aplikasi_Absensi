<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kelas',
        'tahun_ajaran_id',
        'wali_kelas_id',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function waliKelas()
    {
        return $this->belongsTo(WaliKelas::class);
    }

    // --- TAMBAHKAN INI (RELASI KE SISWA) ---
    public function siswas()
    {
        // hasMany = Satu Kelas punya BANYAK Siswa
        return $this->hasMany(Siswa::class);
    }
}