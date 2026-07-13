<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $primaryKey = 'gurus_id';
    protected $fillable = ['nama_guru', 'nip', 'jenis_kelamin', 'no_telp', 'users_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'gurus_id');
    }

    public function jamPelajarans()
    {
        return $this->belongsToMany(JamPelajaran::class, 'guru_jam_pelajaran', 'gurus_id', 'jam_pelajarans_id');
    }

    // Relasi many-to-many dengan mapel dan kelas via tabel pivot
    public function mapelKelas()
    {
        return $this->hasMany(GuruMapelKelas::class);
    }

    // Ambil semua mapel yang diajar (unique)
    public function mataPelajarans()
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mapel_kelas', 'gurus_id', 'mata_pelajarans_id')->distinct();
    }

    // Ambil semua kelas yang diampu (unique)
    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'guru_mapel_kelas', 'gurus_id', 'kelas_id')->distinct();
    }

    // Cek apakah guru diperbolehkan mengajar mapel tertentu di kelas tertentu
    public function canTeach($mapelId, $kelasId)
    {
        return $this->mapelKelas()
            ->where('mata_pelajarans_id', $mapelId)
            ->where('kelas_id', $kelasId)
            ->exists();
    }
}