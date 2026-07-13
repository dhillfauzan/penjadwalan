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
    protected $jamList;
    protected $kelasList;
    
    protected $guruKelasMap;
    protected $guruJamMap;

    protected $alokasiPerKelas; // Array of [kelas_id => array of GuruMapelKelas arrays]
    
    public function __construct()
    {
        // Eager load relations to prevent N+1 queries during processing
        $this->guruList = Guru::with(['mataPelajarans', 'kelas', 'jamPelajarans'])->get();
        $this->jamList = JamPelajaran::orderBy('hari')->orderBy('jam_ke')->get();
        $this->kelasList = Kelas::all();
        
        // Pre-calculate fast lookups for fitness function to be O(1)
        $this->guruKelasMap = [];
        $this->guruJamMap = [];
        foreach ($this->guruList as $guru) {
            $this->guruKelasMap[$guru->getKey()] = $guru->kelas->pluck('kelas_id')->flip()->toArray();
            $this->guruJamMap[$guru->getKey()] = $guru->jamPelajarans->pluck('jam_pelajarans_id')->flip()->toArray();
        }

        // Kumpulkan alokasi per kelas sebagai TASKS
        $this->alokasiPerKelas = [];
        $allowedData = GuruMapelKelas::all();
        foreach ($allowedData as $ad) {
            $this->alokasiPerKelas[$ad->kelas_id][] = [
                'id' => $ad->getKey(),
                'gurus_id' => $ad->gurus_id,
                'mapel_id' => $ad->mata_pelajarans_id,
            ];
        }
    }

    public function run()
    {
        $jumlahJam = count($this->jamList);
        $jumlahKelas = count($this->kelasList);
        $jumlahSlot = $jumlahJam * $jumlahKelas;
        
        // Probabilitas Discrete PSO (Normalisasi dari w, c1, c2)
        $totalWeight = $this->w + $this->c1 + $this->c2;
        $probPBest = $this->c1 / $totalWeight; // ~ 0.43
        $probGBest = $this->c2 / $totalWeight; // ~ 0.43
        
        $particles = [];
        $pbest = [];
        $pbestFitness = [];
        $gbest = null;
        $gbestFitness = INF;

        // Langkah 1: Inisialisasi Partikel sesuai alokasi
        for ($i = 0; $i < $this->jumlahPartikel; $i++) {
            $posisi = [];
            
            // Susun posisi secara logis: Dikelompokkan per Kelas, kemudian per Jam.
            foreach ($this->kelasList as $kelas) {
                $alokasiKelas = $this->alokasiPerKelas[$kelas->getKey()] ?? [];
                $tasks = [];
                
                $jumlahAlokasi = count($alokasiKelas);
                if ($jumlahAlokasi > 0) {
                    // Distribusikan alokasi agar memenuhi seluruh jam dalam seminggu
                    // Misalnya ada 40 jam seminggu dan 11 mapel, maka tiap mapel rata-rata dapat 3-4 jam
                    $baseCount = floor($jumlahJam / $jumlahAlokasi);
                    $remainder = $jumlahJam % $jumlahAlokasi;
                    
                    foreach ($alokasiKelas as $idx => $alokasi) {
                        $count = $baseCount + ($idx < $remainder ? 1 : 0);
                        for ($k = 0; $k < $count; $k++) {
                            $tasks[] = $alokasi;
                        }
                    }
                } else {
                    // Jika kelas ini tidak punya alokasi mapel sama sekali, isi dengan null
                    for ($k = 0; $k < $jumlahJam; $k++) {
                        $tasks[] = null;
                    }
                }
                
                // Acak penempatan jadwal (tasks) ke dalam jam (slot) untuk kelas ini
                shuffle($tasks);
                
                foreach ($tasks as $t) {
                    $posisi[] = $t;
                }
            }
            
            $particles[] = $posisi;
            $fitness = $this->hitungFitness($posisi);
            
            $pbest[] = $posisi;
            $pbestFitness[] = $fitness;
            
            if ($fitness < $gbestFitness) {
                $gbestFitness = $fitness;
                $gbest = $posisi;
            }
        }

        // Langkah 2: Iterasi PSO
        for ($iter = 0; $iter < $this->iterasi; $iter++) {
            for ($i = 0; $i < $this->jumlahPartikel; $i++) {
                
                $newPos = $particles[$i];
                
                // Discrete Position Update berdasarkan probabilitas meniru Best
                for ($j = 0; $j < $jumlahSlot; $j++) {
                    $r = mt_rand() / mt_getrandmax(); 
                    
                    if ($r < $probPBest) {
                        $newPos[$j] = $pbest[$i][$j];
                    } elseif ($r < ($probPBest + $probGBest)) {
                        $newPos[$j] = $gbest[$j];
                    }
                    // Else: retain current position (w)
                }
                
                // Terapkan Repair Function untuk memperbaiki duplikasi / task hilang akibat update PSO
                $newPos = $this->repairFunction($newPos);
                $particles[$i] = $newPos;

                // Hitung fitness baru
                $fitness = $this->hitungFitness($particles[$i]);
                
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

        // Langkah 3: Heuristic Local Search pada GBest untuk menghilangkan sisa bentrok
        $gbest = $this->resolveConflicts($gbest);
        $gbestFitness = $this->hitungFitness($gbest);

        // Langkah 4: Simpan jadwal terbaik ke database
        $this->simpanJadwal($gbest);
        return $gbestFitness;
    }

    private function repairFunction($posisi)
    {
        // Memperbaiki constraint Duplikasi berlebih dan Kehilangan Alokasi per kelas
        $jumlahJam = count($this->jamList);
        $repairedPos = [];
        $slotIndex = 0;
        
        foreach ($this->kelasList as $kelas) {
            $alokasiKelas = $this->alokasiPerKelas[$kelas->getKey()] ?? [];
            $jumlahAlokasi = count($alokasiKelas);
            
            $requiredCounts = [];
            $taskData = [];
            
            if ($jumlahAlokasi > 0) {
                $baseCount = floor($jumlahJam / $jumlahAlokasi);
                $remainder = $jumlahJam % $jumlahAlokasi;
                
                foreach ($alokasiKelas as $idx => $alokasi) {
                    $count = $baseCount + ($idx < $remainder ? 1 : 0);
                    $requiredCounts[$alokasi['id']] = $count;
                    $taskData[$alokasi['id']] = $alokasi;
                }
            }
            
            $currentTasks = []; // [task_id => [slot_index1, slot_index2]]
            $emptySlots = [];
            
            // Evaluasi kondisi per kelas
            for ($j = 0; $j < $jumlahJam; $j++) {
                $task = $posisi[$slotIndex + $j];
                if ($task === null) {
                    $emptySlots[] = $slotIndex + $j;
                } else {
                    $currentTasks[$task['id']][] = $slotIndex + $j;
                }
            }
            
            // 1. Cari Duplikat yang berlebih dari kuota
            foreach ($currentTasks as $taskId => $slots) {
                $maxAllowed = $requiredCounts[$taskId] ?? 0;
                if (count($slots) > $maxAllowed) {
                    // Sisakan sebanyak maxAllowed, sisanya ubah jadi slot kosong
                    for ($k = $maxAllowed; $k < count($slots); $k++) {
                        $emptySlots[] = $slots[$k];
                        $posisi[$slots[$k]] = null;
                    }
                }
            }
            
            // 2. Cari Task yang Hilang (kurang dari kuota) akibat Update PSO
            $missingTasks = [];
            foreach ($requiredCounts as $reqId => $reqCount) {
                $currentCount = isset($currentTasks[$reqId]) ? count($currentTasks[$reqId]) : 0;
                // Jika terhapus oleh repairFunction di iterasi sebelumnya atau update PSO
                $diff = $reqCount - $currentCount;
                if ($diff > 0) {
                    for ($i = 0; $i < $diff; $i++) {
                        $missingTasks[] = $taskData[$reqId];
                    }
                }
            }
            
            // 3. Pindahkan Task yang Hilang ke Slot yang Kosong
            foreach ($missingTasks as $mTask) {
                if (count($emptySlots) > 0) {
                    $fillSlot = array_shift($emptySlots); // Ambil slot kosong pertama
                    $posisi[$fillSlot] = $mTask;
                }
            }
            
            // Gabungkan hasil perbaikan
            for ($j = 0; $j < $jumlahJam; $j++) {
                $repairedPos[] = $posisi[$slotIndex + $j];
            }
            $slotIndex += $jumlahJam;
        }
        return $repairedPos;
    }

    private function resolveConflicts($posisi)
    {
        $jumlahJam = count($this->jamList);
        
        // FASE 2: Resolusi Bentrok Guru (Guru mengajar di >1 kelas pada jam yang sama)
        // Lakukan beberapa kali percobaan swap untuk meminimalisir bentrok
        $maxAttempts = 10; // Bisa lebih banyak karena hanya dipanggil 1 kali di akhir
        $jumlahKelas = count($this->kelasList);
        
        $repairedPos = $posisi;
        
        for ($attempt = 0; $attempt < $maxAttempts; $attempt++) {
            $slotGuru = []; // [gurus_id][jam_id] => index di $repairedPos
            $konflikDitemukan = false;
            
            for ($k = 0; $k < $jumlahKelas; $k++) {
                for ($j = 0; $j < $jumlahJam; $j++) {
                    $posIndex = ($k * $jumlahJam) + $j;
                    $task = $repairedPos[$posIndex];
                    
                    if ($task !== null) {
                        $guruId = $task['gurus_id'];
                        $jamId = $this->jamList[$j]->getKey();
                        
                        if (isset($slotGuru[$guruId][$jamId])) {
                            $konflikDitemukan = true;
                            // Terjadi bentrok! Guru ini sudah mengajar di kelas lain pada jam ini.
                            // Coba cari jam lain ($j2) di kelas yang sama ($k) di mana guru ini BISA mengajar
                            // dan guru di jam $j2 tersebut BISA mengajar di jam $j.
                            
                            $swapSuccess = false;
                            // Acak urutan pencarian agar tidak bias
                            $searchSlots = range(0, $jumlahJam - 1);
                            shuffle($searchSlots);
                            
                            foreach ($searchSlots as $j2) {
                                if ($j2 == $j) continue;
                                
                                $posIndex2 = ($k * $jumlahJam) + $j2;
                                $task2 = $repairedPos[$posIndex2];
                                $jamId2 = $this->jamList[$j2]->getKey();
                                
                                // Syarat Swap:
                                // 1. Di jam $j2, guruId belum ada jadwal di kelas lain
                                // 2. Jika task2 tidak null, guruId2 belum ada jadwal di jam $j di kelas lain
                                
                                if (!isset($slotGuru[$guruId][$jamId2])) {
                                    $bisaSwap = true;
                                    if ($task2 !== null) {
                                        $guruId2 = $task2['gurus_id'];
                                        if (isset($slotGuru[$guruId2][$jamId])) {
                                            $bisaSwap = false;
                                        }
                                    }
                                    
                                    if ($bisaSwap) {
                                        // Lakukan Swap!
                                        $repairedPos[$posIndex] = $task2;
                                        $repairedPos[$posIndex2] = $task;
                                        
                                        // Update state pencarian
                                        $slotGuru[$guruId][$jamId2] = $posIndex2;
                                        if ($task2 !== null) {
                                            $slotGuru[$task2['gurus_id']][$jamId] = $posIndex;
                                        }
                                        $swapSuccess = true;
                                        break;
                                    }
                                }
                            }
                            
                            if (!$swapSuccess) {
                                // Jika tidak bisa swap dengan aman, catat posisinya untuk ditimpa (tetap bentrok)
                                $slotGuru[$guruId][$jamId] = $posIndex;
                            }
                        } else {
                            $slotGuru[$guruId][$jamId] = $posIndex;
                        }
                    }
                }
            }
            if (!$konflikDitemukan) break;
        }
        
        return $repairedPos;
    }

    private function hitungFitness($posisi)
    {
        // Fitness = Total Pelanggaran Konflik (0 adalah yang terbaik)
        $konflik = 0;
        $slotGuru = []; // [gurus_id][jam_id] = ada
        
        $slotIndex = 0;
        $jumlahJam = count($this->jamList);
        
        foreach ($this->kelasList as $kelas) {
            for ($j = 0; $j < $jumlahJam; $j++) {
                $jamId = $this->jamList[$j]->getKey();
                $task = $posisi[$slotIndex];
                
                if ($task !== null) {
                    $guruId = $task['gurus_id'];
                    
                    // HARD CONSTRAINT 1: Guru tidak boleh mengajar > 1 kelas di slot waktu yang sama
                    if (isset($slotGuru[$guruId][$jamId])) {
                        $konflik += 1000; // Penalti Sangat Besar
                    } else {
                        $slotGuru[$guruId][$jamId] = true;
                    }

                    // HARD CONSTRAINT 2: Jam mengajar guru harus sesuai ketersediaan guru
                    $allowedJam = $this->guruJamMap[$guruId] ?? [];
                    if (!empty($allowedJam) && !isset($allowedJam[$jamId])) {
                        $konflik += 500; // Penalti Sangat Besar
                    }
                    
                    // Catatan:
                    // - "Kelas tidak boleh memiliki 2 pelajaran di slot yg sama" -> Sudah mustahil terjadi krn struktur array
                    // - "Tidak boleh ada duplikasi jadwal/mapel" -> Sudah diselesaikan 100% oleh Repair Function
                    // - "Mata pelajaran harus sesuai kelas" -> Repair Function & inisialisasi hanya mengambil dr alokasi kelas yg tepat
                }
                
                $slotIndex++;
            }
        }
        
        return $konflik;
    }

    private function simpanJadwal($bestPosisi)
    {
        // Hapus jadwal lama sebelum memasukkan jadwal optimasi terbaik
        Jadwal::query()->truncate();
        
        $slotIndex = 0;
        $data = [];
        $jumlahJam = count($this->jamList);
        
        foreach ($this->kelasList as $kelas) {
            for ($j = 0; $j < $jumlahJam; $j++) {
                $task = $bestPosisi[$slotIndex];
                
                if ($task !== null) {
                    $data[] = [
                        'gurus_id' => $task['gurus_id'],
                        'mata_pelajarans_id' => $task['mapel_id'],
                        'jam_pelajarans_id' => $this->jamList[$j]->getKey(),
                        'kelas_id' => $kelas->getKey(),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                $slotIndex++;
            }
        }
        
        // Simpan dalam batching memori besar
        $chunks = array_chunk($data, 500);
        foreach ($chunks as $chunk) {
            Jadwal::insert($chunk);
        }
    }
}