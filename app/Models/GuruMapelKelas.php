<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruMapelKelas extends Model
{
    use HasFactory;

    protected $table = 'guru_mapel_kelas';
    protected $fillable = ['gurus_id', 'mata_pelajarans_id', 'kelas_id'];

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
}
