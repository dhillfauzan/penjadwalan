<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Hapus semua Jam Pelajaran yang ada
\App\Models\JamPelajaran::query()->delete();

$hariData = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat'];
$waktuData = [
    1 => ['mulai' => '07:30:00', 'selesai' => '08:15:00'],
    2 => ['mulai' => '08:15:00', 'selesai' => '09:00:00'],
    3 => ['mulai' => '09:00:00', 'selesai' => '09:45:00'],
    4 => ['mulai' => '10:00:00', 'selesai' => '10:45:00'],
    5 => ['mulai' => '10:45:00', 'selesai' => '11:30:00'],
    6 => ['mulai' => '11:30:00', 'selesai' => '12:15:00'],
    7 => ['mulai' => '13:00:00', 'selesai' => '13:45:00'],
    8 => ['mulai' => '13:45:00', 'selesai' => '14:30:00'],
];

foreach ($hariData as $hari) {
    foreach ($waktuData as $jamKe => $waktu) {
        \App\Models\JamPelajaran::create([
            'hari' => $hari,
            'jam_ke' => $jamKe,
            'waktu_mulai' => $waktu['mulai'],
            'waktu_selesai' => $waktu['selesai'],
        ]);
    }
}

echo "Jam Pelajaran berhasil direset ke 40 slot (Senin-Jumat, Jam 1-8).\n";
