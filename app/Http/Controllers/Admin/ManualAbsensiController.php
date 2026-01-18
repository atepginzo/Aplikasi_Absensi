<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\Kehadiran;
use App\Models\TahunAjaran;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManualAbsensiController extends Controller
{
    public function index(Request $request)
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();
        $tahunPilihanId = $request->input('tahun_ajaran_id', $tahunAktif?->id);

        $daftarKelasQuery = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->orderBy('nama_kelas', 'asc');

        if ($tahunPilihanId) {
            $daftarKelasQuery->where('tahun_ajaran_id', $tahunPilihanId);
        }

        $user = Auth::user();
        if ($user && $user->role === 'wali_kelas') {
            $waliId = optional($user->waliKelas)->id;
            if ($waliId) {
                $daftarKelasQuery->where('wali_kelas_id', $waliId);
            } else {
                $daftarKelasQuery->whereRaw('1 = 0');
            }
        }

        $daftarKelas = $daftarKelasQuery->get();
        $semuaTahunAjaran = TahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        return view('admin.absensi.manual.index', [
            'daftarKelas' => $daftarKelas,
            'semuaTahunAjaran' => $semuaTahunAjaran,
            'tahunPilihanId' => $tahunPilihanId,
            'tahunAktif' => $tahunAktif,
        ]);
    }

    public function show(Request $request, $kelasId)
    {
        $kelas = Kelas::with(['waliKelas', 'tahunAjaran'])
            ->findOrFail($kelasId);

        $this->ensureKelasAccessible($kelas);

        $inputTanggal = $request->input('tanggal');

        if ($inputTanggal) {
            try {
                $tanggalDipilih = Carbon::parse($inputTanggal)->format('Y-m-d');
            } catch (\Throwable $th) {
                $tanggalDipilih = Carbon::today()->format('Y-m-d');
            }
        } else {
            $tanggalDipilih = Carbon::today()->format('Y-m-d');
        }

        $siswaKelas = Siswa::where('kelas_id', $kelas->id)
            ->orderBy('nama_siswa')
            ->get();

        $kehadiranMap = Kehadiran::whereDate('tanggal', $tanggalDipilih)
            ->whereIn('siswa_id', $siswaKelas->pluck('id'))
            ->get()
            ->keyBy('siswa_id');

        $statusOptions = ['Hadir', 'Sakit', 'Izin', 'Alpha'];

        return view('admin.absensi.manual.show', [
            'kelas' => $kelas,
            'siswaKelas' => $siswaKelas,
            'kehadiranMap' => $kehadiranMap,
            'tanggalDipilih' => $tanggalDipilih,
            'tanggalLabel' => Carbon::parse($tanggalDipilih)->translatedFormat('l, d F Y'),
            'statusOptions' => $statusOptions,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'tanggal' => 'required|date',
            'absensi' => 'nullable|array',
            'absensi.*' => 'nullable|in:Hadir,Sakit,Izin,Alpha',
        ]);

        $kelas = Kelas::findOrFail($validated['kelas_id']);
        $this->ensureKelasAccessible($kelas);

        $tanggal = Carbon::parse($validated['tanggal'])->format('Y-m-d');
        $absensiData = $validated['absensi'] ?? [];

        if (empty($absensiData)) {
            return redirect()
                ->route('admin.absensi.manual.show', ['kelas' => $validated['kelas_id'], 'tanggal' => $tanggal])
                ->with('info', 'Tidak ada perubahan absensi yang dikirimkan.');
        }

        foreach ($absensiData as $siswaId => $status) {
            if (!$status) {
                continue;
            }

            $existing = Kehadiran::where('siswa_id', $siswaId)
                ->whereDate('tanggal', $tanggal)
                ->first();

            $jamMasuk = $status === 'Hadir'
                ? ($existing?->jam_masuk ?? Carbon::now()->format('H:i:s'))
                : null;

            $jamPulang = $status === 'Hadir' ? $existing?->jam_pulang : null;

            Kehadiran::updateOrCreate(
                [
                    'siswa_id' => $siswaId,
                    'tanggal' => $tanggal,
                ],
                [
                    'status' => $status,
                    'jam_masuk' => $jamMasuk,
                    'jam_pulang' => $jamPulang,
                ]
            );
        }

        return redirect()
            ->route('admin.absensi.manual.show', ['kelas' => $validated['kelas_id'], 'tanggal' => $tanggal])
            ->with('success', 'Absensi manual berhasil disimpan.');
    }

    protected function ensureKelasAccessible(Kelas $kelas): void
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'wali_kelas') {
            return;
        }

        $waliId = optional($user->waliKelas)->id;

        if (! $waliId || $kelas->wali_kelas_id !== $waliId) {
            abort(403, 'Anda tidak memiliki akses ke kelas ini.');
        }
    }
}
