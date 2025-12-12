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

    // Relasi ke Siswa: satu kelas memiliki banyak siswa
    public function siswas()
    {
        return $this->hasMany(Siswa::class);
    }
}