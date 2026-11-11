<?php

require_once __DIR__ . '/../app/models/Mahasiswa.php';

echo '<pre>';

$uji = [
    ['setNim', ''],
    ['setNim', 'AB#12'],
    ['setNama', '   '],
    ['setEmail', 'bukan-email'],
    ['setProdiId', 0],
    ['setAngkatan', 1800],
    ['setStatus', 'drop-out'],
    ['setNim', 'E4123999'],   // valid
];

foreach ($uji as [$method, $nilai]) {
    $m = new Mahasiswa();
    $label = $method . '(' . var_export($nilai, true) . ')';

    try {
        $m->$method($nilai);
        echo "DITERIMA : $label\n";
    } catch (InvalidArgumentException $e) {
        echo "DITOLAK  : $label -> " . $e->getMessage() . "\n";
    }
}

echo '</pre>';