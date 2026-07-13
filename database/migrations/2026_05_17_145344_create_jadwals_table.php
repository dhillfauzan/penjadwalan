<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         Schema::create('jadwals', function (Blueprint $table) {
        $table->id('jadwals_id');
        $table->foreignId('gurus_id')->constrained('gurus', 'gurus_id')->onDelete('cascade');
        $table->foreignId('mata_pelajarans_id')->constrained('mata_pelajarans', 'mata_pelajarans_id')->onDelete('cascade');
        $table->foreignId('jam_pelajarans_id')->constrained('jam_pelajarans', 'jam_pelajarans_id')->onDelete('cascade');
        $table->foreignId('kelas_id')->constrained('kelas', 'kelas_id')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
