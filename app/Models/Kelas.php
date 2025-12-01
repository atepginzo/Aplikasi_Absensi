<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Kelas extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kelas',
        'tahun_ajaran_id',
        'wali_kelas_id',
    ];
    
    /**
     * Relasi ke TahunAjaran
     */
    public function tahunAjaran()
    {
        // belongsTo -> "Satu Kelas ini MILIK satu TahunAjaran"
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * Relasi ke WaliKelas
     */
    public function waliKelas()
    {
        // belongsTo -> "Satu Kelas ini MILIK satu WaliKelas"
        return $this->belongsTo(WaliKelas::class);
    }
}
