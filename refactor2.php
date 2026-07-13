<?php
$modelsDir = __DIR__ . '/app/Models/';

$models = [
    'User' => 'users_id',
    'Guru' => 'gurus_id',
    'Kelas' => 'kelas_id',
    'MataPelajaran' => 'mata_pelajarans_id',
    'JamPelajaran' => 'jam_pelajarans_id',
    'Jadwal' => 'jadwals_id',
    'GuruMapelKelas' => 'id' // don't change this one
];

foreach ($models as $modelName => $pk) {
    if ($pk === 'id') continue;
    $path = $modelsDir . $modelName . '.php';
    if (!file_exists($path)) continue;
    
    $content = file_get_contents($path);
    // Check if protected $primaryKey is already there
    if (strpos($content, 'protected $primaryKey') !== false) {
        continue;
    }
    
    // Insert protected $primaryKey inside the class body
    $content = preg_replace('/class\s+'.$modelName.'\s+extends\s+(?:Authenticatable|Model)\s*\{/i', "$0\n    protected \$primaryKey = '$pk';", $content);
    file_put_contents($path, $content);
    echo "Updated Model $modelName\n";
}

// Now replace all foreign keys in Models
// E.g., user_id -> users_id, guru_id -> gurus_id
$allModels = glob($modelsDir . '*.php');
foreach ($allModels as $file) {
    $content = file_get_contents($file);
    $content = str_replace("'user_id'", "'users_id'", $content);
    $content = str_replace("'guru_id'", "'gurus_id'", $content);
    $content = str_replace("'mata_pelajaran_id'", "'mata_pelajarans_id'", $content);
    $content = str_replace("'jam_pelajaran_id'", "'jam_pelajarans_id'", $content);
    $content = str_replace("'kelas_id'", "'kelas_id'", $content); // remains same
    file_put_contents($file, $content);
    echo "Updated foreign keys in " . basename($file) . "\n";
}

// Update controllers and views globally
$dirsToSearch = [__DIR__ . '/app/Http/Controllers', __DIR__ . '/resources/views', __DIR__ . '/app/Services'];
foreach ($dirsToSearch as $dir) {
    $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iter as $file) {
        if ($file->isDir()) continue;
        $path = $file->getPathname();
        if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') continue;
        
        $content = file_get_contents($path);
        $origContent = $content;
        
        // Very aggressive replacements for foreign keys in code
        $content = str_replace("user_id", "users_id", $content);
        $content = str_replace("guru_id", "gurus_id", $content);
        $content = str_replace("mata_pelajaran_id", "mata_pelajarans_id", $content);
        $content = str_replace("jam_pelajaran_id", "jam_pelajarans_id", $content);
        
        // Replace ->id with respective ->{model}_id? That's too risky to do globally with blind replace.
        // Instead, let's target specific models:
        // $user->id -> $user->users_id
        // $guru->id -> $guru->gurus_id
        // $kelas->id -> $kelas->kelas_id
        // $mapel->id -> $mapel->mata_pelajarans_id
        // $jam->id -> $jam->jam_pelajarans_id
        // $jadwal->id -> $jadwal->jadwals_id
        // $item->id -> depends.
        
        $content = str_replace('->id', '->getKey()', $content); // Replace all ->id with ->getKey() is safer! Wait, in blade it's fine.
        // Wait, ->getKey() doesn't work well if it's an array or in some view syntaxes.
        // Actually ->id is magically mapped to the primaryKey in Laravel Eloquent if we don't explicitly access raw attributes.
        // Wait, NO. `$user->id` will try to get the 'id' column. If 'id' is not the primary key, it returns null unless we access `$user->getKey()`.
        
        if ($content !== $origContent) {
            file_put_contents($path, $content);
            echo "Updated references in $path\n";
        }
    }
}
