<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajarans';
    
    protected $fillable = [
        'nama_mapel',
        'kode_mapel'
    ];

    // Relasi ke jadwal
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'mata_pelajaran_id');
    }
    public function guruKelas()
{
    return $this->belongsToMany(Guru::class, 'guru_mapel_kelas', 'mata_pelajaran_id', 'guru_id')
                ->withPivot('kelas_id');
}
}