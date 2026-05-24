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
        $guru = auth()->user()->guru;

        if (!$guru) {
            return redirect()->route('profile.edit')->with('error', 'Profil Guru belum lengkap. Silakan hubungi admin.');
        }

        // Ambil jadwal yang sesuai dengan guru_id dari guru yang sedang login
        $jadwals = Jadwal::with(['mataPelajaran', 'jamPelajaran', 'kelas'])
            ->where('guru_id', $guru->id)
            ->orderBy('jam_pelajaran_id')
            ->get();

        return view('guru.jadwal', compact('guru', 'jadwals'));
    }

    /**
     * Tampilkan jadwal mengajar spesifik.
     */
    public function jadwalMengajar()
    {
        return $this->index();
    }
}
