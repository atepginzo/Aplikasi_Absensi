<?php

namespace App\Imports;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class SiswaImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnFailure
{
    use Importable, SkipsFailures;

    public int $successCount = 0;

    public function model(array $row)
    {
        $kelasNama = isset($row['kelas']) ? trim((string) $row['kelas']) : null;
        $kelas = $kelasNama
            ? Kelas::whereRaw('LOWER(nama_kelas) = ?', [Str::lower($kelasNama)])->first()
            : null;

        if (! $kelas) {
            return null; // skip row when class does not exist
        }

        $namaSiswa = isset($row['nama_siswa']) ? trim((string) $row['nama_siswa']) : '';
        $nisValue = isset($row['nis']) ? (string) $row['nis'] : '';

        $orangTuaId = null;
        $emailOrtu = isset($row['email_ortu']) ? trim((string) $row['email_ortu']) : null;
        if ($emailOrtu) {
            $orangTua = User::firstOrCreate(
                ['email' => $emailOrtu],
                [
                    'name' => ($namaSiswa ?: 'Orang Tua').' - Orang Tua',
                    'password' => Hash::make($nisValue ?: Str::random(10)),
                    'role' => 'orang_tua',
                ]
            );

            $orangTuaId = $orangTua->id;
        }

        $jenisKelamin = strtoupper(trim((string) ($row['jenis_kelamin'] ?? 'L')));
        $jenisKelamin = $jenisKelamin === 'P' ? 'P' : 'L';

        $this->successCount++;

        return new Siswa([
            'kelas_id' => $kelas->id,
            'parent_user_id' => $orangTuaId,
            'nama_siswa' => $namaSiswa,
            'nis' => $nisValue,
            'jenis_kelamin' => $jenisKelamin,
            'qrcode_token' => (string) Str::uuid(),
        ]);
    }

    public function rules(): array
    {
        return [
            '*.nama_siswa' => ['required', 'string', 'max:100'],
            '*.nis' => ['required', 'unique:siswas,nis'],
            '*.jenis_kelamin' => ['required', Rule::in(['L', 'P', 'l', 'p'])],
            '*.kelas' => ['required', 'string'],
            '*.email_ortu' => ['nullable', 'email'],
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            '*.nama_siswa.required' => 'Nama siswa wajib diisi.',
            '*.nis.required' => 'NIS wajib diisi.',
            '*.nis.unique' => 'NIS sudah terdaftar.',
            '*.jenis_kelamin.in' => 'Jenis kelamin hanya boleh L atau P.',
            '*.kelas.required' => 'Nama kelas wajib diisi.',
            '*.email_ortu.email' => 'Format email orang tua tidak valid.',
        ];
    }
}
