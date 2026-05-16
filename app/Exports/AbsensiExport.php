<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AbsensiExport implements FromCollection, WithHeadings, WithTitle, WithStyles
{
    public function __construct(
        private int $guruId,
        private ?int $semesterId,
        private ?int $kelasId,
        private ?int $mapelId,
    ) {}

    public function collection()
    {
        return Absensi::with(['siswa', 'kelas', 'mataPelajaran'])
            ->where('guru_id', $this->guruId)
            ->when($this->semesterId, fn($q) => $q->where('semester_id', $this->semesterId))
            ->when($this->kelasId,   fn($q) => $q->where('kelas_id', $this->kelasId))
            ->when($this->mapelId,   fn($q) => $q->where('mata_pelajaran_id', $this->mapelId))
            ->orderBy('tanggal')
            ->orderBy('siswa_id')
            ->get()
            ->map(fn($a, $i) => [
                'No'              => $i + 1,
                'NISN'            => $a->siswa->nisn,
                'Nama Siswa'      => $a->siswa->nama_lengkap,
                'Kelas'           => $a->kelas->nama,
                'Mata Pelajaran'  => $a->mataPelajaran->nama,
                'Tanggal'         => $a->tanggal->format('d/m/Y'),
                'Status'          => $a->status,
                'Keterangan'      => $a->keterangan ?? '-',
            ]);
    }

    public function headings(): array
    {
        return ['No', 'NISN', 'Nama Siswa', 'Kelas', 'Mata Pelajaran', 'Tanggal', 'Status', 'Keterangan'];
    }

    public function title(): string
    {
        return 'Rekap Absensi';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
