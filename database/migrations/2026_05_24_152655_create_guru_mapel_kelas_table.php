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
            $table->foreignId('gurus_id')->constrained('gurus', 'gurus_id')->onDelete('cascade');
            $table->foreignId('mata_pelajarans_id')->constrained('mata_pelajarans', 'mata_pelajarans_id')->onDelete('cascade');
            $table->foreignId('kelas_id')->constrained('kelas', 'kelas_id')->onDelete('cascade');
            $table->timestamps();
            
            $table->unique(['gurus_id', 'mata_pelajarans_id', 'kelas_id'], 'guru_mapel_kelas_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('guru_mapel_kelas');
    }
};