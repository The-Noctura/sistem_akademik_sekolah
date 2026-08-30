<?php

namespace Database\Seeders;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Mapel;
use App\Models\Mengajar;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AkademikSeeder extends Seeder
{
    public function run(): void
    {
        // 1 Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama' => 'Admin Sekolah',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // 2 Guru
        $guru1 = User::firstOrCreate(
            ['email' => 'guru1@example.com'],
            [
                'nama' => 'Budi Santoso, S.Pd',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
            ]
        );
        $guru1Profile = Guru::firstOrCreate(
            ['user_id' => $guru1->id],
            [
                'nip' => '198001012005011001',
                'nama' => 'Budi Santoso, S.Pd',
                'no_hp' => '081234567890',
            ]
        );

        $guru2 = User::firstOrCreate(
            ['email' => 'guru2@example.com'],
            [
                'nama' => 'Siti Rahayu, S.Pd',
                'password' => Hash::make('guru123'),
                'role' => 'guru',
            ]
        );
        $guru2Profile = Guru::firstOrCreate(
            ['user_id' => $guru2->id],
            [
                'nip' => '198502022010012002',
                'nama' => 'Siti Rahayu, S.Pd',
                'no_hp' => '081234567891',
            ]
        );

        // 1 Kelas
        $kelas = Kelas::firstOrCreate(
            ['nama_kelas' => 'X IPA 1'],
            [
                'tingkat' => 'X',
                'wali_kelas_id' => $guru1Profile->id,
                'tahun_ajaran' => '2024/2025',
            ]
        );

        // 5 Siswa
        $siswaData = [
            ['nis' => '2024001', 'nama' => 'Ahmad Fauzi', 'jk' => 'L', 'tgl' => '2008-01-15'],
            ['nis' => '2024002', 'nama' => 'Budi Santoso', 'jk' => 'L', 'tgl' => '2008-03-22'],
            ['nis' => '2024003', 'nama' => 'Citra Dewi', 'jk' => 'P', 'tgl' => '2008-05-10'],
            ['nis' => '2024004', 'nama' => 'Dedi Pratama', 'jk' => 'L', 'tgl' => '2008-07-18'],
            ['nis' => '2024005', 'nama' => 'Eka Putri', 'jk' => 'P', 'tgl' => '2008-09-30'],
        ];

        foreach ($siswaData as $index => $data) {
            $email = 'siswa'.($index + 1).'@example.com';
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'nama' => $data['nama'],
                    'password' => Hash::make('siswa123'),
                    'role' => 'siswa',
                ]
            );

            Siswa::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'nis' => $data['nis'],
                    'nama' => $data['nama'],
                    'kelas_id' => $kelas->id,
                    'jenis_kelamin' => $data['jk'],
                    'tanggal_lahir' => $data['tgl'],
                ]
            );
        }

        // 2 Mapel
        $mapel1 = Mapel::firstOrCreate(
            ['kode_mapel' => 'MTK'],
            ['nama_mapel' => 'Matematika', 'kode_mapel' => 'MTK']
        );

        $mapel2 = Mapel::firstOrCreate(
            ['kode_mapel' => 'BIN'],
            ['nama_mapel' => 'Bahasa Indonesia', 'kode_mapel' => 'BIN']
        );

        // Mengajar records
        Mengajar::firstOrCreate(
            [
                'guru_id' => $guru1Profile->id,
                'mapel_id' => $mapel1->id,
                'kelas_id' => $kelas->id,
                'tahun_ajaran' => '2024/2025',
                'semester' => 'Ganjil',
            ]
        );

        Mengajar::firstOrCreate(
            [
                'guru_id' => $guru2Profile->id,
                'mapel_id' => $mapel2->id,
                'kelas_id' => $kelas->id,
                'tahun_ajaran' => '2024/2025',
                'semester' => 'Ganjil',
            ]
        );

        $this->command->info('AkademikSeeder completed!');
        $this->command->info('Akun testing:');
        $this->command->info('  Admin: admin@example.com / admin123');
        $this->command->info('  Guru 1: guru1@example.com / guru123');
        $this->command->info('  Guru 2: guru2@example.com / guru123');
        $this->command->info('  Siswa 1-5: siswa1-5@example.com / siswa123');
    }
}
