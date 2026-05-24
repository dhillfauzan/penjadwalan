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
        $jadwals = Jadwal::with(['guru', 'mataPelajaran', 'jamPelajaran', 'kelas'])->get();

        // Daftar semua kelas
        $kelasList = Kelas::orderBy('nama_kelas')->get();

        // Daftar jam_ke unik (urut)
        $jamKeList = JamPelajaran::orderBy('jam_ke')->pluck('jam_ke')->unique()->values();

        // Siapkan array untuk menyimpan jadwal per kelas, per hari, per jam_ke
        $scheduleByClass = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

        foreach ($kelasList as $kelas) {
            $scheduleByClass[$kelas->id] = [];
            foreach ($days as $day) {
                $scheduleByClass[$kelas->id][$day] = [];
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

        return view('admin.jadwal.index', compact('jadwals', 'kelasList', 'scheduleByClass', 'jamKeList'));
    }

    public function generate()
    {
        $pso = new PSOSchedulingService();
        $fitness = $pso->run();
        return redirect()->route('admin.jadwal.index')->with('success', "Jadwal berhasil digenerate dengan fitness $fitness");
    }
}