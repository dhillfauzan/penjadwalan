<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\JamPelajaran;

class DashboardController extends Controller
{
    public function index()
    {
        $totalGuru = Guru::count();
        $totalMapel = MataPelajaran::count();
        $totalKelas = Kelas::count();
        $totalJam = JamPelajaran::count();

        return view('admin.dashboard', compact('totalGuru', 'totalMapel', 'totalKelas', 'totalJam'));
    }
}