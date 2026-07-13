<?php

$migrationsDir = __DIR__ . '/database/migrations/';
$modelsDir = __DIR__ . '/app/Models/';

function replaceInFile($path, $search, $replace) {
    if (!file_exists($path)) return;
    $content = file_get_contents($path);
    $newContent = str_replace($search, $replace, $content);
    if ($content !== $newContent) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
    }
}

// 1. users
replaceInFile($migrationsDir . '0001_01_01_000000_create_users_table.php', '$table->id();', '$table->id(\'users_id\');');
replaceInFile($modelsDir . 'User.php', 'use HasFactory, Notifiable;', "use HasFactory, Notifiable;\n    protected \$primaryKey = 'users_id';");

// 2. gurus
replaceInFile($migrationsDir . '2026_05_17_145003_create_gurus_table.php', '$table->id();', '$table->id(\'gurus_id\');');
replaceInFile($modelsDir . 'Guru.php', 'use HasFactory;', "use HasFactory;\n    protected \$primaryKey = 'gurus_id';");

// 3. mata_pelajarans
replaceInFile($migrationsDir . '2026_05_17_145128_create_mata_pelajarans_table.php', '$table->id();', '$table->id(\'mata_pelajarans_id\');');
replaceInFile($modelsDir . 'MataPelajaran.php', 'use HasFactory;', "use HasFactory;\n    protected \$primaryKey = 'mata_pelajarans_id';");

// 4. jam_pelajarans
replaceInFile($migrationsDir . '2026_05_17_145215_create_jam_pelajarans_table.php', '$table->id();', '$table->id(\'jam_pelajarans_id\');');
replaceInFile($modelsDir . 'JamPelajaran.php', 'use HasFactory;', "use HasFactory;\n    protected \$primaryKey = 'jam_pelajarans_id';");

// 5. kelas
replaceInFile($migrationsDir . '2026_05_17_145252_create_kelas_table.php', '$table->id();', '$table->id(\'kelas_id\');');
replaceInFile($modelsDir . 'Kelas.php', 'use HasFactory;', "use HasFactory;\n    protected \$primaryKey = 'kelas_id';");

// 6. jadwals
replaceInFile($migrationsDir . '2026_05_17_145344_create_jadwals_table.php', '$table->id();', '$table->id(\'jadwals_id\');');
replaceInFile($modelsDir . 'Jadwal.php', 'use HasFactory;', "use HasFactory;\n    protected \$primaryKey = 'jadwals_id';");

// Foreign Keys in jadwals
$jadwalsMig = $migrationsDir . '2026_05_17_145344_create_jadwals_table.php';
replaceInFile($jadwalsMig, '$table->foreignId(\'guru_id\')->constrained(\'gurus\')', '$table->foreignId(\'gurus_id\')->constrained(\'gurus\', \'gurus_id\')');
replaceInFile($jadwalsMig, '$table->foreignId(\'mata_pelajaran_id\')->constrained(\'mata_pelajarans\')', '$table->foreignId(\'mata_pelajarans_id\')->constrained(\'mata_pelajarans\', \'mata_pelajarans_id\')');
replaceInFile($jadwalsMig, '$table->foreignId(\'jam_pelajaran_id\')->constrained(\'jam_pelajarans\')', '$table->foreignId(\'jam_pelajarans_id\')->constrained(\'jam_pelajarans\', \'jam_pelajarans_id\')');
replaceInFile($jadwalsMig, '$table->foreignId(\'kelas_id\')->constrained(\'kelas\')', '$table->foreignId(\'kelas_id\')->constrained(\'kelas\', \'kelas_id\')');

// Foreign Keys in add_user_id_to_gurus
$addUserId = $migrationsDir . '2026_05_17_145429_add_user_id_to_gurus_table.php';
replaceInFile($addUserId, '$table->foreignId(\'user_id\')->nullable()->constrained(\'users\')', '$table->foreignId(\'users_id\')->nullable()->constrained(\'users\', \'users_id\')');
replaceInFile($addUserId, 'dropForeign([\'user_id\'])', 'dropForeign([\'users_id\'])');
replaceInFile($addUserId, 'dropColumn(\'user_id\')', 'dropColumn(\'users_id\')');

// Foreign Keys in guru relationships
$guruRel = $migrationsDir . '2026_05_17_150000_create_guru_relationships_tables.php';
replaceInFile($guruRel, '$table->id();', '$table->id(\'id\');'); // Just to keep them as id or wait, we can rename them.
replaceInFile($guruRel, '$table->id(\'id\');', '$table->id();'); // Revert if replaced
$contentRel = file_get_contents($guruRel);
$contentRel = preg_replace('/\$table->id\(\);/', '$table->id(\'id\');', $contentRel);
// Let's replace foreign keys manually
$contentRel = str_replace('$table->foreignId(\'guru_id\')->constrained(\'gurus\')', '$table->foreignId(\'gurus_id\')->constrained(\'gurus\', \'gurus_id\')', $contentRel);
$contentRel = str_replace('$table->foreignId(\'mata_pelajaran_id\')->constrained(\'mata_pelajarans\')', '$table->foreignId(\'mata_pelajarans_id\')->constrained(\'mata_pelajarans\', \'mata_pelajarans_id\')', $contentRel);
$contentRel = str_replace('$table->foreignId(\'kelas_id\')->constrained(\'kelas\')', '$table->foreignId(\'kelas_id\')->constrained(\'kelas\', \'kelas_id\')', $contentRel);
$contentRel = str_replace('$table->foreignId(\'jam_pelajaran_id\')->constrained(\'jam_pelajarans\')', '$table->foreignId(\'jam_pelajarans_id\')->constrained(\'jam_pelajarans\', \'jam_pelajarans_id\')', $contentRel);
file_put_contents($guruRel, $contentRel);

// Foreign Keys in guru_mapel_kelas
$guruMapelKelas = $migrationsDir . '2026_05_24_152655_create_guru_mapel_kelas_table.php';
replaceInFile($guruMapelKelas, '$table->foreignId(\'guru_id\')->constrained(\'gurus\')', '$table->foreignId(\'gurus_id\')->constrained(\'gurus\', \'gurus_id\')');
replaceInFile($guruMapelKelas, '$table->foreignId(\'mata_pelajaran_id\')->constrained(\'mata_pelajarans\')', '$table->foreignId(\'mata_pelajarans_id\')->constrained(\'mata_pelajarans\', \'mata_pelajarans_id\')');
replaceInFile($guruMapelKelas, '$table->foreignId(\'kelas_id\')->constrained(\'kelas\')', '$table->foreignId(\'kelas_id\')->constrained(\'kelas\', \'kelas_id\')');

echo "Migrations and Models updated.\n";

