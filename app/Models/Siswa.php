<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'kelas_id',
        'user_id',
        'parent_user_id',
        'nis',
        'nama_siswa',
        'jenis_kelamin',
        'qrcode_token', // Penting untuk nanti
    ];

    /**
     * Relasi ke Kelas
     */
    public function kelas()
    {
        // "Satu Siswa ini MILIK satu Kelas"
        return $this->belongsTo(Kelas::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orangTua()
    {
        return $this->belongsTo(User::class, 'parent_user_id');
    }
}