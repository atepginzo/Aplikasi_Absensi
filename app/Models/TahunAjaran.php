<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Kelas;

class TahunAjaran extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tahun_ajaran',
        'is_active',
    ];

    /**
     * Relasi ke daftar kelas dalam tahun ajaran ini.
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class);
    }
}
