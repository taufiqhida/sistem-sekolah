<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Raport - {{ $siswa->nama_lengkap }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif; font-size: 11px; color: #333; }

        .header { text-align: center; border-bottom: 3px double #1a1a2e; padding-bottom: 12px; margin-bottom: 16px; }
        .header h1 { font-size: 18px; font-weight: bold; color: #1a1a2e; letter-spacing: 1px; }
        .header h2 { font-size: 13px; color: #4e73df; margin: 4px 0; }
        .header p { font-size: 10px; color: #666; }

        .info-table { width: 100%; margin-bottom: 16px; }
        .info-table td { padding: 3px 6px; }
        .info-table td:first-child { width: 30%; font-weight: bold; color: #555; }
        .info-table td:nth-child(2) { width: 5%; }

        .section-title {
            background: linear-gradient(135deg, #1a1a2e, #4e73df);
            color: white;
            padding: 6px 12px;
            font-weight: bold;
            font-size: 11px;
            border-radius: 4px;
            margin-bottom: 8px;
        }

        table.nilai { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        table.nilai thead tr { background: #4e73df; color: white; }
        table.nilai thead th { padding: 6px 8px; text-align: center; font-size: 10px; }
        table.nilai tbody tr:nth-child(even) { background: #f8f9fc; }
        table.nilai tbody td { padding: 5px 8px; border-bottom: 1px solid #e3e6f0; }
        table.nilai tbody td.text-center { text-align: center; }
        .lulus { color: #1cc88a; font-weight: bold; }
        .gagal { color: #e74a3b; font-weight: bold; }

        .rekap-absensi { display: flex; gap: 8px; margin-bottom: 16px; }
        .rekap-box { flex: 1; text-align: center; padding: 8px; border-radius: 6px; }
        .rekap-box .num { font-size: 20px; font-weight: bold; }
        .rekap-box .label { font-size: 9px; text-transform: uppercase; }
        .box-hadir { background: #d4edda; color: #155724; }
        .box-izin  { background: #d1ecf1; color: #0c5460; }
        .box-sakit { background: #fff3cd; color: #856404; }
        .box-alfa  { background: #f8d7da; color: #721c24; }

        .signature-section { margin-top: 30px; }
        .signature-table { width: 100%; }
        .signature-table td { text-align: center; vertical-align: top; padding: 0 20px; }
        .signature-line { border-bottom: 1px solid #333; width: 180px; margin: 60px auto 4px; }

        .footer { margin-top: 20px; border-top: 1px solid #e3e6f0; padding-top: 8px; text-align: center; font-size: 9px; color: #aaa; }

        .avg-box { background: #4e73df; color: white; border-radius: 8px; padding: 10px 16px; margin-bottom: 16px; }
        .avg-box span { font-size: 24px; font-weight: bold; }
    </style>
</head>
<body>

    <!-- Header -->
    <div class="header">
        <h1>LAPORAN HASIL BELAJAR SISWA</h1>
        <h2>SMK AT KAUSAR</h2>
        <p>Jl. Pendidikan No. 1, Kota &bull; Telp. (021) 1234567 &bull; smkatkausar.sch.id</p>
        <p style="margin-top:4px;">
            Semester: <strong>{{ $semester?->tahunAjaran->nama }} {{ $semester?->nama }}</strong>
        </p>
    </div>

    <!-- Info Siswa -->
    <div class="section-title">&#9654; Data Siswa</div>
    <table class="info-table">
        <tr>
            <td>Nama Lengkap</td><td>:</td>
            <td><strong>{{ $siswa->nama_lengkap }}</strong></td>
            <td>Kelas</td><td>:</td>
            <td><strong>{{ $siswaKelas?->kelas->nama ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td>NISN</td><td>:</td>
            <td>{{ $siswa->nisn }}</td>
            <td>NIS</td><td>:</td>
            <td>{{ $siswa->nis ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td><td>:</td>
            <td>{{ $siswa->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td>Tahun Pelajaran</td><td>:</td>
            <td>{{ $semester?->tahunAjaran->nama ?? '-' }}</td>
        </tr>
    </table>

    <!-- Rata-rata -->
    @php $rataRata = $nilais->whereNotNull('nilai_akhir')->avg('nilai_akhir'); @endphp
    @if($rataRata)
    <div class="avg-box">
        Rata-rata Nilai Semester: <span>{{ number_format($rataRata, 1) }}</span>
        &nbsp;&nbsp;|&nbsp;&nbsp;
        Predikat:
        <span>
            @if($rataRata >= 90) A (Sangat Baik)
            @elseif($rataRata >= 80) B (Baik)
            @elseif($rataRata >= 70) C (Cukup)
            @else D (Perlu Perbaikan)
            @endif
        </span>
    </div>
    @endif

    <!-- Nilai -->
    <div class="section-title">&#9654; Daftar Nilai</div>
    <table class="nilai">
        <thead>
            <tr>
                <th width="4%">No</th>
                <th>Mata Pelajaran</th>
                <th width="8%">KKM</th>
                <th width="10%">Tugas</th>
                <th width="10%">UTS</th>
                <th width="10%">UAS</th>
                <th width="12%">Nilai Akhir</th>
                <th width="8%">Predikat</th>
                <th width="10%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($nilais as $idx => $nilai)
            @php $lulus = ($nilai->nilai_akhir ?? 0) >= $nilai->mataPelajaran->kkm; @endphp
            <tr>
                <td class="text-center">{{ $idx + 1 }}</td>
                <td>{{ $nilai->mataPelajaran->nama }}</td>
                <td class="text-center">{{ $nilai->mataPelajaran->kkm }}</td>
                <td class="text-center">{{ $nilai->nilai_tugas ?? '-' }}</td>
                <td class="text-center">{{ $nilai->nilai_uts ?? '-' }}</td>
                <td class="text-center">{{ $nilai->nilai_uas ?? '-' }}</td>
                <td class="text-center">
                    <strong class="{{ $lulus ? 'lulus' : 'gagal' }}">{{ $nilai->nilai_akhir ?? '-' }}</strong>
                </td>
                <td class="text-center"><strong>{{ $nilai->predikat ?? '-' }}</strong></td>
                <td class="text-center">
                    <span class="{{ $lulus ? 'lulus' : 'gagal' }}">{{ $lulus ? 'Lulus' : 'Remedial' }}</span>
                </td>
            </tr>
            @empty
            <tr><td colspan="9" style="text-align:center;padding:12px;color:#aaa;">Belum ada data nilai</td></tr>
            @endforelse
        </tbody>
    </table>

    <!-- Rekap Absensi -->
    <div class="section-title">&#9654; Rekap Kehadiran</div>
    <table style="width:100%;border-collapse:collapse;margin-bottom:16px;">
        <tr>
            <td style="text-align:center;padding:8px;background:#d4edda;border-radius:6px;color:#155724;">
                <div style="font-size:20px;font-weight:bold;">{{ $rekap['hadir'] }}</div>
                <div style="font-size:9px;">HADIR</div>
            </td>
            <td style="width:10px;"></td>
            <td style="text-align:center;padding:8px;background:#d1ecf1;border-radius:6px;color:#0c5460;">
                <div style="font-size:20px;font-weight:bold;">{{ $rekap['izin'] }}</div>
                <div style="font-size:9px;">IZIN</div>
            </td>
            <td style="width:10px;"></td>
            <td style="text-align:center;padding:8px;background:#fff3cd;border-radius:6px;color:#856404;">
                <div style="font-size:20px;font-weight:bold;">{{ $rekap['sakit'] }}</div>
                <div style="font-size:9px;">SAKIT</div>
            </td>
            <td style="width:10px;"></td>
            <td style="text-align:center;padding:8px;background:#f8d7da;border-radius:6px;color:#721c24;">
                <div style="font-size:20px;font-weight:bold;">{{ $rekap['alfa'] }}</div>
                <div style="font-size:9px;">ALFA</div>
            </td>
        </tr>
    </table>

    <!-- Tanda Tangan -->
    <table class="signature-table">
        <tr>
            <td>
                <p>Orang Tua/Wali</p>
                <div class="signature-line"></div>
                <p>( .......................... )</p>
            </td>
            <td>
                <p>Mengetahui,<br>Wali Kelas</p>
                <div class="signature-line"></div>
                <p>( .......................... )</p>
            </td>
            <td>
                <p>Kepala Sekolah</p>
                <div class="signature-line"></div>
                <p>( .......................... )</p>
            </td>
        </tr>
    </table>

    <div class="footer">
        Dicetak oleh Sistem Informasi Akademik SMK At Kausar &bull; {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
