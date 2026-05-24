<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\Jadwal;
use App\Models\GuruMapelKelas;
use Illuminate\Support\Facades\DB;

class PSOSchedulingService
{
    public $jumlahPartikel = 30;
    public $iterasi = 100;
    protected $c1 = 1.5;
    protected $c2 = 1.5;
    protected $w = 0.5;
    protected $guruList;
    protected $mapelList;
    protected $jamList;
    protected $kelasList;
    protected $batasAtas; // jumlah total slot

    public function __construct()
{
    $this->guruList = Guru::all();
    $this->mapelList = MataPelajaran::all();
    $this->jamList = JamPelajaran::orderBy('hari')->orderBy('jam_ke')->get();
    $this->kelasList = Kelas::all();
    $this->batasAtas = count($this->jamList) * count($this->kelasList);

    // Build allowed mapping [guru_id][mapel_id][kelas_id] = true
    $allowedData = GuruMapelKelas::all();
    foreach ($allowedData as $ad) {
        $this->allowed[$ad->guru_id][$ad->mata_pelajaran_id][$ad->kelas_id] = true;
    }
}

    public function run()
    {
        // Representasi partikel: array sepanjang jumlah (jam * kelas) yang berisi ID guru_mapel
        // Setiap partikel merepresentasikan penempatan guru_mapel pada setiap slot (jam, kelas)
        
        // Langkah 1: Buat daftar semua kemungkinan (guru, mapel) yang valid berdasarkan relasi yang ditentukan di Admin
        $guruMapel = [];
        foreach ($this->guruList as $guru) {
            if ($guru->mataPelajarans->isNotEmpty()) {
                foreach ($guru->mataPelajarans as $mapel) {
                    $guruMapel[] = ['guru_id' => $guru->id, 'mapel_id' => $mapel->id];
                }
            } else {
                // Fallback jika admin belum mengisi mapel guru ini
                foreach ($this->mapelList as $mapel) {
                    $guruMapel[] = ['guru_id' => $guru->id, 'mapel_id' => $mapel->id];
                }
            }
        }

        // Jika tidak ada data sama sekali, gunakan fallback universal
        if (empty($guruMapel)) {
            foreach ($this->guruList as $guru) {
                foreach ($this->mapelList as $mapel) {
                    $guruMapel[] = ['guru_id' => $guru->id, 'mapel_id' => $mapel->id];
                }
            }
        }

        $jumlahKombinasi = count($guruMapel);
        $jumlahSlot = count($this->jamList) * count($this->kelasList);

        // Inisialisasi partikel
        $particles = [];
        $velocities = [];
        $pbest = [];
        $pbestFitness = [];
        $gbest = null;
        $gbestFitness = INF;

        for ($i = 0; $i < $this->jumlahPartikel; $i++) {
            $posisi = [];
            for ($j = 0; $j < $jumlahSlot; $j++) {
                $posisi[] = rand(0, $jumlahKombinasi - 1);
            }
            $particles[] = $posisi;
            $velocities[] = array_fill(0, $jumlahSlot, 0);
            $fitness = $this->hitungFitness($posisi, $guruMapel);
            $pbest[] = $posisi;
            $pbestFitness[] = $fitness;
            if ($fitness < $gbestFitness) {
                $gbestFitness = $fitness;
                $gbest = $posisi;
            }
        }

        // Iterasi PSO
        for ($iter = 0; $iter < $this->iterasi; $iter++) {
            for ($i = 0; $i < $this->jumlahPartikel; $i++) {
                // Update kecepatan
                for ($j = 0; $j < $jumlahSlot; $j++) {
                    $r1 = mt_rand() / mt_getrandmax();
                    $r2 = mt_rand() / mt_getrandmax();
                    $vel = $this->w * $velocities[$i][$j] +
                           $this->c1 * $r1 * ($pbest[$i][$j] - $particles[$i][$j]) +
                           $this->c2 * $r2 * ($gbest[$j] - $particles[$i][$j]);
                    $velocities[$i][$j] = $vel;
                    // Update posisi (round ke integer)
                    $newPos = $particles[$i][$j] + round($vel);
                    $newPos = max(0, min($jumlahKombinasi - 1, $newPos));
                    $particles[$i][$j] = $newPos;
                }
                // Hitung fitness baru
                $fitness = $this->hitungFitness($particles[$i], $guruMapel);
                if ($fitness < $pbestFitness[$i]) {
                    $pbestFitness[$i] = $fitness;
                    $pbest[$i] = $particles[$i];
                    if ($fitness < $gbestFitness) {
                        $gbestFitness = $fitness;
                        $gbest = $particles[$i];
                    }
                }
            }
        }

        // Simpan jadwal terbaik ke database
        $this->simpanJadwal($gbest, $guruMapel);
        return $gbestFitness;
    }

    private function hitungFitness($posisi, $guruMapel)
    {
        // Fitness: jumlah konflik (hard constraint & relation constraint)
        $konflik = 0;
        $slotGuru = []; // [guru_id][slot_index] = ada
        $slotKelas = []; // [kelas_id][slot_index] = ada
        $slotIndex = 0;
        foreach ($this->jamList as $jam) {
            foreach ($this->kelasList as $kelas) {
                $idx = $posisi[$slotIndex];
                $gm = $guruMapel[$idx];
                $guruId = $gm['guru_id'];
                
                // 1. Cek guru sudah mengajar di jam yang sama?
                if (isset($slotGuru[$guruId][$jam->id])) {
                    $konflik += 10;
                } else {
                    $slotGuru[$guruId][$jam->id] = true;
                }

                // 2. Cek kelas sudah mendapat pelajaran di jam ini?
                if (isset($slotKelas[$kelas->id][$jam->id])) {
                    $konflik += 10;
                } else {
                    $slotKelas[$kelas->id][$jam->id] = true;
                }

                // Ambil data guru untuk mengecek konstrain khusus
                $guru = $this->guruList->find($guruId);

                // 3. Constraint Kelas: Apakah guru ini ditugaskan mengajar di kelas ini?
                if ($guru && $guru->kelas->isNotEmpty()) {
                    if (!$guru->kelas->contains($kelas->id)) {
                        $konflik += 5; // Penalti jika mengajar di kelas yang tidak diampu
                    }
                }

                // 4. Constraint Jam: Apakah guru ini bersedia/tersedia di jam ini?
                if ($guru && $guru->jamPelajarans->isNotEmpty()) {
                    if (!$guru->jamPelajarans->contains($jam->id)) {
                        $konflik += 5; // Penalti jika mengajar di luar preferensi jam ketersediaan
                    }
                }

                $slotIndex++;
            }
        }
        return $konflik;
    }

    private function simpanJadwal($bestPosisi, $guruMapel)
    {
        // Hapus jadwal lama
        Jadwal::query()->delete();
        $slotIndex = 0;
        $data = [];
        foreach ($this->jamList as $jam) {
            foreach ($this->kelasList as $kelas) {
                $idx = $bestPosisi[$slotIndex];
                $gm = $guruMapel[$idx];
                $data[] = [
                    'guru_id' => $gm['guru_id'],
                    'mata_pelajaran_id' => $gm['mapel_id'],
                    'jam_pelajaran_id' => $jam->id,
                    'kelas_id' => $kelas->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                $slotIndex++;
            }
        }
        Jadwal::insert($data);
    }
}