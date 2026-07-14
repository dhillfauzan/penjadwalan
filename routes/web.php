<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\GuruController;
use App\Http\Controllers\Admin\JamPelajaranController;
use App\Http\Controllers\Admin\MataPelajaranController;
use App\Http\Controllers\Admin\KelasController;
use App\Http\Controllers\Admin\JadwalController;
use App\Http\Controllers\Admin\OptimasiController;
use App\Http\Controllers\Guru\JadwalGuruController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('guru.jadwal');
        }
    })->name('dashboard');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('users', UserController::class);
        Route::resource('guru', GuruController::class);
        Route::resource('jam-pelajaran', JamPelajaranController::class);
        Route::resource('mata-pelajaran', MataPelajaranController::class);
        Route::resource('kelas', KelasController::class)->parameters([
            'kelas' => 'kelas'
        ]);
        Route::post('/jadwal/generate', [JadwalController::class, 'generate'])->name('jadwal.generate');
        Route::get('/jadwal/semua-guru', [JadwalController::class, 'semuaGuru'])->name('jadwal.semua-guru');
        Route::resource('jadwal', JadwalController::class);
        Route::get('/optimasi', [OptimasiController::class, 'index'])->name('optimasi');
        Route::post('/optimasi/run', [OptimasiController::class, 'run'])->name('optimasi.run');
    });

    // Guru routes
    Route::middleware(['role:guru'])->prefix('guru')->name('guru.')->group(function () {
        Route::get('/jadwal', [JadwalGuruController::class, 'index'])->name('jadwal');
        Route::get('/jadwal-mengajar', [JadwalGuruController::class, 'jadwalMengajar'])->name('jadwal.mengajar');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';