<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\JamPelajaran;
use App\Models\MataPelajaran;
use App\Models\Kelas;
use App\Models\GuruMapelKelas;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index()
    {
        $gurus = Guru::with(['user', 'mataPelajarans', 'kelas'])->get();
        return view('admin.guru.index', compact('gurus'));
    }

    public function create()
    {
        $jamPelajarans = JamPelajaran::orderBy('hari')->orderBy('jam_ke')->get();
        $mataPelajarans = MataPelajaran::all();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.guru.create', compact('jamPelajarans', 'mataPelajarans', 'kelas'));
    }

    public function store(Request $request)
{
    $request->validate([
        'nama_guru' => 'required|string|max:255',
        'nip' => 'required|string|unique:gurus,nip',
        'jenis_kelamin' => 'required|in:L,P',
        'no_telp' => 'nullable|string',
        'jam_pelajaran_ids' => 'nullable|array',
        'jam_pelajaran_ids.*' => 'exists:jam_pelajarans,id',
        'mata_pelajaran_ids' => 'nullable|array',
        'mata_pelajaran_ids.*' => 'exists:mata_pelajarans,id',
        'kelas_ids' => 'nullable|array',
        'kelas_ids.*' => 'exists:kelas,id',
    ]);

    $guru = Guru::create($request->only(['nama_guru', 'nip', 'jenis_kelamin', 'no_telp']));

    // Simpan preferensi jam
    if ($request->has('jam_pelajaran_ids')) {
        $guru->jamPelajarans()->sync($request->jam_pelajaran_ids);
    }

    // Simpan pengajaran (mapel + kelas)
    if ($request->has('mata_pelajaran_ids') && $request->has('kelas_ids')) {
        $pengajaranData = [];
        foreach ($request->mata_pelajaran_ids as $mapelId) {
            foreach ($request->kelas_ids as $kelasId) {
                $pengajaranData[] = [
                    'guru_id' => $guru->id,
                    'mata_pelajaran_id' => $mapelId,
                    'kelas_id' => $kelasId,
                ];
            }
        }
        if (!empty($pengajaranData)) {
            GuruMapelKelas::insert($pengajaranData);
        }
    }

    return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil ditambahkan');
}

    public function edit(Guru $guru)
{
    $jamPelajarans = JamPelajaran::orderBy('hari')->orderBy('jam_ke')->get();
    $mataPelajarans = MataPelajaran::all();
    $kelas = Kelas::orderBy('nama_kelas')->get();
    $guru->load(['jamPelajarans', 'mataPelajarans', 'kelas']);

    return view('admin.guru.edit', compact('guru', 'jamPelajarans', 'mataPelajarans', 'kelas'));
}

    public function update(Request $request, Guru $guru)
{
    $request->validate([
        'nama_guru' => 'required|string|max:255',
        'nip' => 'required|string|unique:gurus,nip,'.$guru->id,
        'jenis_kelamin' => 'required|in:L,P',
        'no_telp' => 'nullable|string',
        'jam_pelajaran_ids' => 'nullable|array',
        'jam_pelajaran_ids.*' => 'exists:jam_pelajarans,id',
        'mata_pelajaran_ids' => 'nullable|array',
        'mata_pelajaran_ids.*' => 'exists:mata_pelajarans,id',
        'kelas_ids' => 'nullable|array',
        'kelas_ids.*' => 'exists:kelas,id',
    ]);

    $guru->update($request->only(['nama_guru', 'nip', 'jenis_kelamin', 'no_telp']));
    $guru->jamPelajarans()->sync($request->jam_pelajaran_ids ?? []);

    // Hapus data pengajaran lama, lalu insert baru
    GuruMapelKelas::where('guru_id', $guru->id)->delete();
    
    if ($request->has('mata_pelajaran_ids') && $request->has('kelas_ids')) {
        $pengajaranData = [];
        foreach ($request->mata_pelajaran_ids as $mapelId) {
            foreach ($request->kelas_ids as $kelasId) {
                $pengajaranData[] = [
                    'guru_id' => $guru->id,
                    'mata_pelajaran_id' => $mapelId,
                    'kelas_id' => $kelasId,
                ];
            }
        }
        if (!empty($pengajaranData)) {
            GuruMapelKelas::insert($pengajaranData);
        }
    }

    return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil diperbarui');
}

    public function destroy(Guru $guru)
    {
        if ($guru->user) {
            $guru->user->delete();
        }
        $guru->delete();
        return redirect()->route('admin.guru.index')->with('success', 'Guru berhasil dihapus');
    }
}