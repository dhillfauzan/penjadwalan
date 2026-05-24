<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('guru_mapel_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guru_id')->constrained('gurus')->onDelete('cascade');
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajarans')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['guru_id', 'mata_pelajaran_id', 'kelas_id'], 'guru_mapel_kelas_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('guru_mapel_kelas');
    }
};