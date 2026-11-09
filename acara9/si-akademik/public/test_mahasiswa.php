<?php

require_once __DIR__ . '/../app/models/Mahasiswa.php';
require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';

echo '<pre>';

// 1. Mengisi objek lewat setter, membaca lewat getter
$m = new Mahasiswa();
$m->setNim('E4123999');
$m->setNama('  Contoh Mahasiswa  ');
$m->setEmail('contoh@email.com');
$m->setProdiId(1);
$m->setAngkatan(2026);
$m->setStatus('aktif');

echo "=== Lewat setter dan getter ===\n";
echo "NIM    : " . $m->getNim() . "\n";
echo "Nama   : [" . $m->getNama() . "]\n";
echo "Email  : " . $m->getEmail() . "\n";
echo "Prodi  : " . $m->getProdiId() . "\n";
echo "Angkatan: " . $m->getAngkatan() . "\n";
echo "Status : " . $m->getStatus() . "\n\n";

print_r($m->toArray());

// 2. Objek dari database
echo "\n=== Dari database (id=1) ===\n";
$repo = new MahasiswaRepository(Database::getInstance());
$dariDb = Mahasiswa::fromArray($repo->find(1));
echo $dariDb->getNim() . ' - ' . $dariDb->getNama() . "\n";

// 3. Akses langsung ke atribut private harus gagal
echo "\n=== Akses langsung atribut private ===\n";
try {
    echo $m->nim;
} catch (Error $e) {
    echo "Error: " . $e->getMessage() . "\n";
}

echo '</pre>';