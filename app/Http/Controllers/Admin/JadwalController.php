<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kelas;          // <-- Tambahkan ini
use App\Models\JamPelajaran;   // <-- Tambahkan ini
use App\Services\PSOSchedulingService;

class JadwalController extends Controller
{
    public function index()
    {
        // Ambil semua jadwal beserta relasi
        $jadwals = Jadwal::with(['guru.mataPelajarans', 'mataPelajaran', 'jamPelajaran', 'kelas'])->get();

        // Daftar semua kelas
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // Daftar semua guru dengan mapel yang diajarkannya
        $guruList = \App\Models\Guru::with('mataPelajarans')->orderBy('nama_guru')->get();

        // Daftar jam_ke unik (urut)
        $jamKeList = JamPelajaran::orderBy('jam_ke')->pluck('jam_ke')->unique()->values();

        // Jam pelajaran grouped by hari (for print view)
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jamPelajaranByHari = [];
        foreach ($days as $day) {
            $jamPelajaranByHari[$day] = JamPelajaran::where('hari', $day)
                ->orderBy('jam_ke')
                ->get();
        }

        // Siapkan array untuk menyimpan jadwal per kelas, per hari, per jam_ke
        $scheduleByClass = [];

        foreach ($kelasList as $kelas) {
            $scheduleByClass[$kelas->getKey()] = [];
            foreach ($days as $day) {
                $scheduleByClass[$kelas->getKey()][$day] = [];
            }
        }

        // Isi data jadwal
        foreach ($jadwals as $jadwal) {
            $kelasId = $jadwal->kelas_id;
            $hari = $jadwal->jamPelajaran->hari ?? null;
            $jamKe = $jadwal->jamPelajaran->jam_ke ?? null;
            if ($hari && $jamKe) {
                $scheduleByClass[$kelasId][$hari][$jamKe] = $jadwal;
            }
        }

        // Mapping Guru (untuk penomoran cetak dan format mapel)
        $guruGroups = [];
        foreach ($guruList as $guru) {
            $name = trim($guru->nama_guru);
            if (!isset($guruGroups[$name])) {
                $guruGroups[$name] = [
                    'ids' => [],
                    'mapels' => [],
                    'nama_guru' => $name
                ];
            }
            $guruGroups[$name]['ids'][] = $guru->getKey();
            
            foreach ($guru->mataPelajarans as $m) {
                $guruGroups[$name]['mapels'][$m->kode_mapel] = $m->nama_mapel;
            }
        }

        $guruMapping = [];
        $uniqueGurus = [];
        $no = 1;

        foreach ($guruGroups as $name => $group) {
            $mapelNames = array_values($group['mapels']);
            $mapelString = implode(' dan ', $mapelNames);
            
            // Format nama: Nama Guru (Mapel1 dan Mapel2)
            $namaFormat = $name;
            if (!empty($mapelString)) {
                $namaFormat .= ' (' . $mapelString . ')';
            }

            $isMultiple = count($group['mapels']) > 1;

            $uniqueGurus[] = [
                'no' => $no,
                'nama_format' => $namaFormat
            ];

            foreach ($group['ids'] as $id) {
                $guruMapping[$id] = [
                    'no' => $no,
                    'is_multiple' => $isMultiple,
                ];
            }
            
            $no++;
        }

        return view('admin.jadwal.index', compact(
            'jadwals', 'kelasList', 'guruList', 'scheduleByClass',
            'jamKeList', 'jamPelajaranByHari', 'guruMapping', 'uniqueGurus'
        ));
    }

    public function semuaGuru()
    {
        $jadwals = Jadwal::with(['guru.mataPelajarans', 'mataPelajaran', 'jamPelajaran', 'kelas'])->get();
        $guruList = \App\Models\Guru::with('mataPelajarans')->orderBy('nama_guru')->get();
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();
        $jamKeList = JamPelajaran::orderBy('jam_ke')->pluck('jam_ke')->unique()->values();

        // Jam pelajaran grouped by hari (for print view)
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
        $jamPelajaranByHari = [];
        foreach ($days as $day) {
            $jamPelajaranByHari[$day] = JamPelajaran::where('hari', $day)
                ->orderBy('jam_ke')
                ->get();
        }

        // Group guru by name
        $guruGroups = [];
        foreach ($guruList as $guru) {
            $name = trim($guru->nama_guru);
            if (!isset($guruGroups[$name])) {
                $guruGroups[$name] = [
                    'ids' => [],
                    'mapels' => [],
                    'nama_guru' => $name
                ];
            }
            $guruGroups[$name]['ids'][] = $guru->getKey();
            foreach ($guru->mataPelajarans as $m) {
                $guruGroups[$name]['mapels'][$m->kode_mapel] = $m->nama_mapel;
            }
        }

        $uniqueGurus = collect();
        $guruIdToUniqueName = [];

        foreach ($guruGroups as $name => $group) {
            $mapelNames = array_values($group['mapels']);
            $mapelString = implode(' dan ', $mapelNames);
            
            $namaFormat = $name;
            if (!empty($mapelString)) {
                $namaFormat .= ' (' . $mapelString . ')';
            }

            $uniqueNameIdentifier = md5($name); // unique string for array key

            $uniqueGurus->push((object)[
                'id' => $uniqueNameIdentifier,
                'nama_guru' => $namaFormat,
                'nama_asli' => $name,
            ]);

            foreach ($group['ids'] as $id) {
                $guruIdToUniqueName[$id] = $uniqueNameIdentifier;
            }
        }

        $scheduleByGuru = [];
        foreach ($uniqueGurus as $ug) {
            $scheduleByGuru[$ug->id] = [];
            foreach ($days as $day) {
                $scheduleByGuru[$ug->id][$day] = [];
            }
        }

        foreach ($jadwals as $jadwal) {
            $guruId = $jadwal->gurus_id;
            $hari = $jadwal->jamPelajaran->hari ?? null;
            $jamKe = $jadwal->jamPelajaran->jam_ke ?? null;
            if ($hari && $jamKe && $guruId) {
                $uniqueId = $guruIdToUniqueName[$guruId] ?? null;
                if ($uniqueId) {
                    $scheduleByGuru[$uniqueId][$hari][$jamKe] = $jadwal;
                }
            }
        }

        return view('admin.jadwal.semua-guru', compact(
            'jadwals', 'uniqueGurus', 'scheduleByGuru', 'jamKeList', 'jamPelajaranByHari', 'kelasList'
        ));
    }

    public function generate()
    {
        $pso = new PSOSchedulingService();
        $fitness = $pso->run();
        return redirect()->route('admin.jadwal.index')->with('success', "Jadwal berhasil digenerate dengan fitness $fitness");
    }
}