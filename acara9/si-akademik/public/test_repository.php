<?php

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';

// Dependency diberikan dari luar
$repo = new MahasiswaRepository(Database::getInstance());

echo '<pre>';

echo "Jumlah mahasiswa: " . count($repo->all()) . "\n\n";

echo "Cari 'rifki':\n";
print_r($repo->search('rifki'));

echo "\nFind id=1:\n";
print_r($repo->find(1));

echo '</pre>';