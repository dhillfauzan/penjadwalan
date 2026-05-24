<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $fillable = ['guru_id', 'mata_pelajaran_id', 'jam_pelajaran_id', 'kelas_id'];

public function guru()
{
    return $this->belongsTo(Guru::class);
}
public function mataPelajaran()
{
    return $this->belongsTo(MataPelajaran::class);
}
public function jamPelajaran()
{
    return $this->belongsTo(JamPelajaran::class);
}
public function kelas()
{
    return $this->belongsTo(Kelas::class);
}
}
