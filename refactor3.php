<?php

$dirsToSearch = [__DIR__ . '/database/seeders', __DIR__ . '/database/factories'];

foreach ($dirsToSearch as $dir) {
    if (!is_dir($dir)) continue;
    $iter = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iter as $file) {
        if ($file->isDir()) continue;
        $path = $file->getPathname();
        if (pathinfo($path, PATHINFO_EXTENSION) !== 'php') continue;
        
        $content = file_get_contents($path);
        $origContent = $content;
        
        $content = str_replace("'user_id'", "'users_id'", $content);
        $content = str_replace("'guru_id'", "'gurus_id'", $content);
        $content = str_replace("'mata_pelajaran_id'", "'mata_pelajarans_id'", $content);
        $content = str_replace("'jam_pelajaran_id'", "'jam_pelajarans_id'", $content);
        
        if ($content !== $origContent) {
            file_put_contents($path, $content);
            echo "Updated $path\n";
        }
    }
}
