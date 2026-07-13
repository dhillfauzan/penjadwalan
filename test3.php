<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo \Illuminate\Support\Facades\DB::table('guru_jam_pelajaran')->count() . "\n";
