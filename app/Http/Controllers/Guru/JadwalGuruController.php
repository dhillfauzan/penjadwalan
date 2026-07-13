<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Guru;
use Illuminate\Http\Request;

class JadwalGuruController extends Controller
{
    /**
     * Tampilkan halaman utama jadwal Guru.
     */
    public function index()
    {
        $gurus = auth()->user()->gurus;

        if ($gurus->isEmpty()) {
            return redirect()->route('profile.edit')->with('error', 'Profil Guru belum lengkap. Silakan hubungi admin.');
        }

        $guruIds = $gurus->pluck('gurus_id')->toArray();

        // Ambil jadwal yang sesuai dengan gurus_id dari guru-guru yang sedang login
        $jadwals = Jadwal::with(['mataPelajaran', 'jamPelajaran', 'kelas'])
            ->whereIn('gurus_id', $guruIds)
            ->orderBy('jam_pelajarans_id')
            ->get();
            
        // Gunakan record guru pertama sebagai representasi profil (NIP dan Nama)
        $guru = $gurus->first();

        return view('guru.jadwal', compact('guru', 'jadwals'));
    }

    /**
     * Tampilkan jadwal mengajar spesifik.
     */
    public function jadwalMengajar()
    {
        // Ambil semua jadwal beserta relasi
        $jadwals = \App\Models\Jadwal::with(['guru', 'mataPelajaran', 'jamPelajaran', 'kelas'])->get();

        // Daftar semua kelas
        $kelasList = \App\Models\Kelas::orderBy('nama_kelas')->get();

        // Daftar jam_ke unik (urut)
        $jamKeList = \App\Models\JamPelajaran::orderBy('jam_ke')->pluck('jam_ke')->unique()->values();

        // Siapkan array untuk menyimpan jadwal per kelas, per hari, per jam_ke
        $scheduleByClass = [];
        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];

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

        return view('guru.jadwal_mengajar', compact('jadwals', 'kelasList', 'scheduleByClass', 'jamKeList'));
    }
}
