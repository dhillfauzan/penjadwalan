<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PSOSchedulingService;
use Illuminate\Http\Request;

class OptimasiController extends Controller
{
    public function index()
    {
        return view('admin.optimasi.index');
    }

    public function run(Request $request)
    {
        $request->validate([
            'partikel' => 'integer|min:10|max:100',
            'iterasi' => 'integer|min:10|max:500',
        ]);

        // Validasi ketersediaan data master
        if (\App\Models\Guru::count() == 0 || \App\Models\MataPelajaran::count() == 0 || \App\Models\Kelas::count() == 0 || \App\Models\JamPelajaran::count() == 0) {
            return redirect()->route('admin.jadwal.index')->with('error', 'Gagal menjalankan optimasi: Pastikan data Guru, Mata Pelajaran, Kelas, dan Jam Pelajaran sudah terisi.');
        }

        $pso = new PSOSchedulingService();
        $pso->jumlahPartikel = $request->partikel ?? 30;
        $pso->iterasi = $request->iterasi ?? 100;
        $fitness = $pso->run();

        // Menyimpan nilai fitness terakhir di session untuk ditampilkan di UI jika diinginkan
        session()->flash('last_fitness', $fitness);

        return redirect()->route('admin.jadwal.index')
            ->with('success', "Optimasi PSO Berhasil! Jadwal telah diperbarui menggunakan {$pso->jumlahPartikel} Partikel & {$pso->iterasi} Iterasi. Fitness Terbaik (Konflik): {$fitness}");
    }
}