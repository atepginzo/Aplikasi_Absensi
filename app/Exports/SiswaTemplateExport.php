<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class SiswaTemplateExport implements FromCollection, WithHeadings
{
    public function collection(): Collection
    {
        return collect([
            ['nama_siswa' => 'Budi Santoso', 'nis' => '123456', 'jenis_kelamin' => 'L', 'kelas' => '7A', 'email_ortu' => 'ortu.budi@example.com'],
        ]);
    }

    public function headings(): array
    {
        return ['nama_siswa', 'nis', 'jenis_kelamin', 'kelas', 'email_ortu'];
    }
}
