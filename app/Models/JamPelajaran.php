<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JamPelajaran extends Model
{
    use HasFactory;
    protected $primaryKey = 'jam_pelajarans_id';

    protected $table = 'jam_pelajarans';
    
    protected $fillable = [
        'hari',
        'jam_ke',
        'waktu_mulai',
        'waktu_selesai'
    ];

    // Relasi ke jadwal
    public function jadwals()
    {
        return $this->hasMany(Jadwal::class, 'jam_pelajarans_id');
    }
}