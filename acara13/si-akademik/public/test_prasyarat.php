<?php

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/repositories/ProdiRepository.php';

$db = Database::getInstance();
$mhs = new MahasiswaRepository($db);
$prodi = new ProdiRepository($db);

echo '<pre>';

echo "Jumlah prodi              : " . count($prodi->all()) . "\n";
echo "Prodi id=1 ada?           : "; var_dump($prodi->exists(1));
echo "Prodi id=999 ada?         : "; var_dump($prodi->exists(999));

echo "\n";
echo "NIM 2401001 sudah ada?                  : "; var_dump($mhs->existsByNim('2401001'));
echo "NIM 2401001, mengedit id=1 (diri sendiri): "; var_dump($mhs->existsByNim('2401001', 1));
echo "NIM 2401001, mengedit id=2 (orang lain)  : "; var_dump($mhs->existsByNim('2401001', 2));
echo "NIM ZZZ9999 sudah ada?                  : "; var_dump($mhs->existsByNim('ZZZ9999'));

echo '</pre>';