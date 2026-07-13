<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\JamPelajaran;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Seed Admin
        $this->call(AdminUserSeeder::class);

        // 2. Seed Kelas
        $kelasData = ['X-A', 'X-B', 'XI-A', 'XI-B'];
        foreach ($kelasData as $namaKelas) {
            Kelas::create(['nama_kelas' => $namaKelas]);
        }

        // 3. Seed Mata Pelajaran
        $mapelData = [
            ['nama_mapel' => 'Matematika', 'kode_mapel' => 'MTK'],
            ['nama_mapel' => 'Bahasa Indonesia', 'kode_mapel' => 'IND'],
            ['nama_mapel' => 'Bahasa Inggris', 'kode_mapel' => 'ING'],
            ['nama_mapel' => 'Fisika', 'kode_mapel' => 'FIS'],
            ['nama_mapel' => 'Kimia', 'kode_mapel' => 'KIM'],
        ];
        foreach ($mapelData as $mapel) {
            MataPelajaran::create($mapel);
        }

        // 4. Seed Jam Pelajaran
        $hariData = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $waktuData = [
            1 => ['mulai' => '07:30:00', 'selesai' => '08:15:00'],
            2 => ['mulai' => '08:15:00', 'selesai' => '09:00:00'],
            3 => ['mulai' => '09:00:00', 'selesai' => '09:45:00'],
            4 => ['mulai' => '10:00:00', 'selesai' => '10:45:00'],
            5 => ['mulai' => '10:45:00', 'selesai' => '11:30:00'],
        ];
        foreach ($hariData as $hari) {
            foreach ($waktuData as $jamKe => $waktu) {
                JamPelajaran::create([
                    'hari' => $hari,
                    'jam_ke' => $jamKe,
                    'waktu_mulai' => $waktu['mulai'],
                    'waktu_selesai' => $waktu['selesai'],
                ]);
            }
        }

        // 5. Seed Guru with matching User login
        $guruData = [
            [
                'name' => 'Budi Santoso',
                'email' => 'budi@example.com',
                'nip' => '198701022010121001',
                'jenis_kelamin' => 'L',
                'no_telp' => '081234567890'
            ],
            [
                'name' => 'Siti Aminah',
                'email' => 'siti@example.com',
                'nip' => '199003152015042002',
                'jenis_kelamin' => 'P',
                'no_telp' => '081234567891'
            ],
            [
                'name' => 'Ahmad Fauzi',
                'email' => 'ahmad@example.com',
                'nip' => '198505202008011003',
                'jenis_kelamin' => 'L',
                'no_telp' => '081234567892'
            ],
            [
                'name' => 'Dewi Lestari',
                'email' => 'dewi@example.com',
                'nip' => '199208222018032004',
                'jenis_kelamin' => 'P',
                'no_telp' => '081234567893'
            ],
            [
                'name' => 'Eko Prasetyo',
                'email' => 'eko@example.com',
                'nip' => '198911102012121005',
                'jenis_kelamin' => 'L',
                'no_telp' => '081234567894'
            ],
        ];

        $mapels = MataPelajaran::all();
        $kelasList = Kelas::all();
        $jams = JamPelajaran::all();

        foreach ($guruData as $index => $g) {
            $user = User::create([
                'name' => $g['name'],
                'email' => $g['email'],
                'password' => bcrypt('password'),
                'role' => 'guru',
            ]);

            $guru = Guru::create([
                'nama_guru' => $g['name'],
                'nip' => $g['nip'],
                'jenis_kelamin' => $g['jenis_kelamin'],
                'no_telp' => $g['no_telp'],
                'users_id' => $user->getKey(),
            ]);

            // Hubungkan guru dengan Mata Pelajaran (1 mapel berdasarkan indeks)
            $mapelId = $mapels[$index % $mapels->count()]->getKey();

            // Hubungkan guru dengan Kelas (2 kelas berurutan)
            $classIds = [
                $kelasList[$index % $kelasList->count()]->getKey(),
                $kelasList[($index + 1) % $kelasList->count()]->getKey()
            ];
            
            foreach ($classIds as $kelasId) {
                \App\Models\GuruMapelKelas::create([
                    'gurus_id' => $guru->getKey(),
                    'mata_pelajarans_id' => $mapelId,
                    'kelas_id' => $kelasId
                ]);
            }

            // Hubungkan guru dengan beberapa Jam Pelajaran secara acak (10 jam ketersediaan)
            $jamIds = $jams->pluck('jam_pelajarans_id')->random(10)->toArray();
            $guru->jamPelajarans()->sync($jamIds);
        }
    }
}
