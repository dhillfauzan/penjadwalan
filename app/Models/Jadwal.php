<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $primaryKey = 'jadwals_id';
    protected $fillable = ['gurus_id', 'mata_pelajarans_id', 'jam_pelajarans_id', 'kelas_id'];

public function guru()
{
    return $this->belongsTo(Guru::class, 'gurus_id');
}
public function mataPelajaran()
{
    return $this->belongsTo(MataPelajaran::class, 'mata_pelajarans_id');
}
public function jamPelajaran()
{
    return $this->belongsTo(JamPelajaran::class, 'jam_pelajarans_id');
}
public function kelas()
{
    return $this->belongsTo(Kelas::class, 'kelas_id');
}
}
