<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JamPelajaran;
use Illuminate\Http\Request;

class JamPelajaranController extends Controller
{
    public function index()
    {
        $jam = JamPelajaran::orderBy('hari')->orderBy('jam_ke')->get();
        return view('admin.jam-pelajaran.index', compact('jam'));
    }

    public function create()
    {
        return view('admin.jam-pelajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_ke' => 'required|integer|min:1',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
        ]);

        JamPelajaran::create($request->all());
        return redirect()->route('admin.jam-pelajaran.index')->with('success', 'Jam pelajaran berhasil ditambahkan.');
    }

    public function edit(JamPelajaran $jamPelajaran)
    {
        return view('admin.jam-pelajaran.edit', compact('jamPelajaran'));
    }

    public function update(Request $request, JamPelajaran $jamPelajaran)
    {
        $request->validate([
            'hari' => 'required|in:Senin,Selasa,Rabu,Kamis,Jumat',
            'jam_ke' => 'required|integer|min:1',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required|after:waktu_mulai',
        ]);

        $jamPelajaran->update($request->all());
        return redirect()->route('admin.jam-pelajaran.index')->with('success', 'Jam pelajaran berhasil diperbarui.');
    }

    public function destroy(JamPelajaran $jamPelajaran)
    {
        $jamPelajaran->delete();
        return redirect()->route('admin.jam-pelajaran.index')->with('success', 'Jam pelajaran berhasil dihapus.');
    }
}