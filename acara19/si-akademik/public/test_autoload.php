<?php

require_once __DIR__ . '/../app/core/autoload.php';

$kelas = [
    'Database', 'Logger', 'BaseController', 'Model', 'ProdiModel',
    'MataKuliahModel', 'Mahasiswa', 'MahasiswaRepository', 'ProdiRepository',
    'MahasiswaService', 'MahasiswaController', 'ApiMahasiswaController',
    'ProdiController', 'MataKuliahController', 'AuthController',
    'HomeController', 'AuthMiddleware',
];

echo '<pre>';

foreach ($kelas as $nama) {
    // class_exists() memicu autoloader kalau class belum dimuat
    echo str_pad($nama, 26) . (class_exists($nama) ? 'OK' : 'GAGAL') . "\n";
}

echo '</pre>';