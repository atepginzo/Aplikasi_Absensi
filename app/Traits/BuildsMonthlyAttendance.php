<?php

namespace App\Traits;

use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Support\Collection;

trait BuildsMonthlyAttendance
{
    /**
     * Normalizes the month input (Y-m) into a Carbon instance.
     */
    protected function normalizeMonth(?string $value): ?Carbon
    {
        if (! $value) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m', $value)->startOfDay();
        } catch (\Throwable $th) {
            return null;
        }
    }

    /**
     * Builds monthly recap totals for a given class and period.
     */
    protected function buildMonthlyAttendance(int $kelasId, Carbon $periode): Collection
    {
        $mulai = $periode->copy()->startOfMonth()->toDateString();
        $akhir = $periode->copy()->endOfMonth()->toDateString();

        return Kehadiran::selectRaw(
            "siswas.id as siswa_id,
            siswas.nama_siswa,
            SUM(CASE WHEN kehadirans.status = 'Hadir' THEN 1 ELSE 0 END) AS total_hadir,
            SUM(CASE WHEN kehadirans.status = 'Sakit' THEN 1 ELSE 0 END) AS total_sakit,
            SUM(CASE WHEN kehadirans.status = 'Izin' THEN 1 ELSE 0 END) AS total_izin,
            SUM(CASE WHEN kehadirans.status = 'Alpha' THEN 1 ELSE 0 END) AS total_alpha"
        )
            ->join('siswas', 'siswas.id', '=', 'kehadirans.siswa_id')
            ->where('siswas.kelas_id', $kelasId)
            ->whereBetween('kehadirans.tanggal', [$mulai, $akhir])
            ->groupBy('siswas.id', 'siswas.nama_siswa')
            ->orderBy('siswas.nama_siswa', 'asc')
            ->get();
    }
}
