<?php

namespace Database\Seeders;

use App\Models\TahunAjaran;
use App\Models\Semester;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Guru;
use App\Models\Siswa;
use App\Models\GuruKelasMapel;
use App\Models\SiswaKelas;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Roles
        $roleAdmin = Role::firstOrCreate(['name' => 'admin']);
        $roleGuru  = Role::firstOrCreate(['name' => 'guru']);
        $roleSiswa = Role::firstOrCreate(['name' => 'siswa']);

        // =================== TAHUN AJARAN ===================
        $ta = TahunAjaran::create(['nama' => '2025/2026', 'is_aktif' => true]);

        $semGanjil = Semester::create(['tahun_ajaran_id' => $ta->id, 'nama' => 'Ganjil', 'is_aktif' => true]);
        $semGenap  = Semester::create(['tahun_ajaran_id' => $ta->id, 'nama' => 'Genap',  'is_aktif' => false]);

        // =================== KELAS ===================
        $kelas = [
            ['nama' => 'X RPL 1',   'tingkat' => 'X',   'jurusan' => 'RPL'],
            ['nama' => 'X RPL 2',   'tingkat' => 'X',   'jurusan' => 'RPL'],
            ['nama' => 'XI RPL 1',  'tingkat' => 'XI',  'jurusan' => 'RPL'],
            ['nama' => 'XI RPL 2',  'tingkat' => 'XI',  'jurusan' => 'RPL'],
            ['nama' => 'XII RPL 1', 'tingkat' => 'XII', 'jurusan' => 'RPL'],
            ['nama' => 'X TKJ 1',   'tingkat' => 'X',   'jurusan' => 'TKJ'],
            ['nama' => 'XI TKJ 1',  'tingkat' => 'XI',  'jurusan' => 'TKJ'],
            ['nama' => 'XII TKJ 1', 'tingkat' => 'XII', 'jurusan' => 'TKJ'],
        ];
        $kelasList = collect($kelas)->map(fn($k) => Kelas::create($k));

        // =================== MATA PELAJARAN ===================
        $mapelList = [
            ['kode' => 'MTK',  'nama' => 'Matematika',              'kkm' => 75],
            ['kode' => 'BIN',  'nama' => 'Bahasa Indonesia',        'kkm' => 75],
            ['kode' => 'BING', 'nama' => 'Bahasa Inggris',          'kkm' => 70],
            ['kode' => 'PKK',  'nama' => 'Produk Kreatif & Kewirausahaan', 'kkm' => 75],
            ['kode' => 'WEB',  'nama' => 'Pemrograman Web',         'kkm' => 78],
            ['kode' => 'BD',   'nama' => 'Basis Data',              'kkm' => 78],
            ['kode' => 'JAR',  'nama' => 'Jaringan Komputer',       'kkm' => 75],
            ['kode' => 'PAI',  'nama' => 'Pendidikan Agama Islam',  'kkm' => 75],
            ['kode' => 'PKN',  'nama' => 'Pendidikan Kewarganegaraan', 'kkm' => 75],
            ['kode' => 'PJOK', 'nama' => 'Pendidikan Jasmani',     'kkm' => 70],
        ];
        $mapels = collect($mapelList)->map(fn($m) => MataPelajaran::create($m));

        // =================== ADMIN ===================
        $adminUser = User::create([
            'name'              => 'Administrator',
            'email'             => 'admin@smkatkausar.sch.id',
            'password'          => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
        $adminUser->assignRole($roleAdmin);

        // =================== GURU ===================
        $guruData = [
            ['nama' => 'Budi Santoso, S.Pd',    'nip' => '198501012010011001', 'email' => 'budi@smkatkausar.sch.id'],
            ['nama' => 'Siti Rahayu, S.Pd',     'nip' => '198703152012012002', 'email' => 'siti@smkatkausar.sch.id'],
            ['nama' => 'Ahmad Fauzi, S.Kom',    'nip' => '199001012015011003', 'email' => 'ahmad@smkatkausar.sch.id'],
            ['nama' => 'Dewi Kurniawati, S.Pd', 'nip' => '198805202013012004', 'email' => 'dewi@smkatkausar.sch.id'],
            ['nama' => 'Rizki Pratama, S.Kom',  'nip' => '199205102016011005', 'email' => 'rizki@smkatkausar.sch.id'],
        ];

        $guruUsers = [];
        foreach ($guruData as $g) {
            $user = User::create([
                'name'              => $g['nama'],
                'email'             => $g['email'],
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $user->assignRole($roleGuru);
            $guru = Guru::create([
                'user_id'      => $user->id,
                'nip'          => $g['nip'],
                'nama_lengkap' => $g['nama'],
                'jenis_kelamin'=> str_contains($g['nama'], 'Siti') || str_contains($g['nama'], 'Dewi') ? 'P' : 'L',
                'status'       => 'Aktif',
            ]);
            $guruUsers[] = $guru;
        }

        // =================== ASSIGN GURU KE KELAS & MAPEL ===================
        // Guru 0 (Budi) → MTK → semua kelas X
        GuruKelasMapel::create(['guru_id' => $guruUsers[0]->id, 'kelas_id' => $kelasList[0]->id, 'mata_pelajaran_id' => $mapels[0]->id, 'semester_id' => $semGanjil->id]);
        GuruKelasMapel::create(['guru_id' => $guruUsers[0]->id, 'kelas_id' => $kelasList[1]->id, 'mata_pelajaran_id' => $mapels[0]->id, 'semester_id' => $semGanjil->id]);
        // Guru 1 (Siti) → BIN → kelas XI
        GuruKelasMapel::create(['guru_id' => $guruUsers[1]->id, 'kelas_id' => $kelasList[2]->id, 'mata_pelajaran_id' => $mapels[1]->id, 'semester_id' => $semGanjil->id]);
        // Guru 2 (Ahmad) → WEB, BD → kelas RPL
        GuruKelasMapel::create(['guru_id' => $guruUsers[2]->id, 'kelas_id' => $kelasList[0]->id, 'mata_pelajaran_id' => $mapels[4]->id, 'semester_id' => $semGanjil->id]);
        GuruKelasMapel::create(['guru_id' => $guruUsers[2]->id, 'kelas_id' => $kelasList[2]->id, 'mata_pelajaran_id' => $mapels[5]->id, 'semester_id' => $semGanjil->id]);
        // Guru 3 (Dewi) → BING → kelas XII
        GuruKelasMapel::create(['guru_id' => $guruUsers[3]->id, 'kelas_id' => $kelasList[4]->id, 'mata_pelajaran_id' => $mapels[2]->id, 'semester_id' => $semGanjil->id]);
        // Guru 4 (Rizki) → JAR → TKJ
        GuruKelasMapel::create(['guru_id' => $guruUsers[4]->id, 'kelas_id' => $kelasList[5]->id, 'mata_pelajaran_id' => $mapels[6]->id, 'semester_id' => $semGanjil->id]);

        // =================== SISWA ===================
        $siswaData = [
            // Kelas X RPL 1
            ['nisn' => '0071234001', 'nama' => 'Andi Wijaya',        'kelas_idx' => 0, 'jk' => 'L'],
            ['nisn' => '0071234002', 'nama' => 'Bunga Lestari',      'kelas_idx' => 0, 'jk' => 'P'],
            ['nisn' => '0071234003', 'nama' => 'Candra Putra',       'kelas_idx' => 0, 'jk' => 'L'],
            ['nisn' => '0071234004', 'nama' => 'Dina Safitri',       'kelas_idx' => 0, 'jk' => 'P'],
            ['nisn' => '0071234005', 'nama' => 'Eko Setiawan',       'kelas_idx' => 0, 'jk' => 'L'],
            // Kelas XI RPL 1
            ['nisn' => '0061234001', 'nama' => 'Fajar Ramadan',      'kelas_idx' => 2, 'jk' => 'L'],
            ['nisn' => '0061234002', 'nama' => 'Gita Permata',       'kelas_idx' => 2, 'jk' => 'P'],
            ['nisn' => '0061234003', 'nama' => 'Hendra Gunawan',     'kelas_idx' => 2, 'jk' => 'L'],
            // Kelas XII RPL 1
            ['nisn' => '0051234001', 'nama' => 'Irfan Maulana',      'kelas_idx' => 4, 'jk' => 'L'],
            ['nisn' => '0051234002', 'nama' => 'Jasmine Putri',      'kelas_idx' => 4, 'jk' => 'P'],
        ];

        foreach ($siswaData as $idx => $s) {
            $user = User::create([
                'name'              => $s['nama'],
                'email'             => 'siswa' . ($idx + 1) . '@smkatkausar.sch.id',
                'password'          => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
            $user->assignRole($roleSiswa);

            $siswa = Siswa::create([
                'user_id'       => $user->id,
                'nisn'          => $s['nisn'],
                'nis'           => '2026' . str_pad($idx + 1, 4, '0', STR_PAD_LEFT),
                'nama_lengkap'  => $s['nama'],
                'jenis_kelamin' => $s['jk'],
                'status'        => 'Aktif',
            ]);

            SiswaKelas::create([
                'siswa_id'   => $siswa->id,
                'kelas_id'   => $kelasList[$s['kelas_idx']]->id,
                'semester_id'=> $semGanjil->id,
            ]);
        }

        $this->command->info('✅ Seeder berhasil! Data dummy SMK At Kausar telah dibuat.');
        $this->command->line('');
        $this->command->line('👤 Login Admin   : admin@smkatkausar.sch.id / password');
        $this->command->line('👨‍🏫 Login Guru    : budi@smkatkausar.sch.id / password');
        $this->command->line('👨‍🎓 Login Siswa   : siswa1@smkatkausar.sch.id / password');
    }
}
