<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$jamList = \App\Models\JamPelajaran::orderBy('hari')->orderBy('jam_ke')->get();
foreach($jamList as $jam) {
    echo $jam->hari . ' - Jam ' . $jam->jam_ke . "\n";
}
