<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $table = 'kelas';
    
    protected $fillable = [
        'nama_kelas'
    ];

    // Relasi ke jadwal
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'kelas_id');
    }
    public function guruMapel()
{
    return $this->belongsToMany(Guru::class, 'guru_mapel_kelas', 'kelas_id', 'guru_id')
                ->withPivot('mata_pelajaran_id');
}
}