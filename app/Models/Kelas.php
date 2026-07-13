<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;
    protected $primaryKey = 'kelas_id';

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
    return $this->belongsToMany(Guru::class, 'guru_mapel_kelas', 'kelas_id', 'gurus_id')
                ->withPivot('mata_pelajarans_id');
}
}