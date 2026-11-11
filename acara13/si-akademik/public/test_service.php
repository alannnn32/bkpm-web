<?php

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/repositories/ProdiRepository.php';
require_once __DIR__ . '/../app/services/MahasiswaService.php';

$db = Database::getInstance();
$repo = new MahasiswaRepository($db);
$service = new MahasiswaService($repo, new ProdiRepository($db));

function tampil(string $label, array $hasil): void
{
    echo "--- $label\n";
    echo $hasil['success'] ? "success: true\n" : "success: false\n";
    foreach ($hasil['errors'] ?? [] as $e) {
        echo "  * $e\n";
    }
    echo "\n";
}

$valid = [
    'nim' => 'TEST0001', 'nama' => 'Mahasiswa Uji', 'email' => 'uji@email.com',
    'prodi_id' => 1, 'angkatan' => 2026, 'status' => 'aktif',
];

echo '<pre>';

tampil('1. create: input kosong', $service->create([]));
tampil('2. create: NIM kembar (2401001)', $service->create(array_merge($valid, ['nim' => '2401001'])));
tampil('3. create: prodi 999', $service->create(array_merge($valid, ['prodi_id' => 999])));

$hasil = $service->create($valid);
tampil('4. create: data valid', $hasil);
$id = (int) ($hasil['id'] ?? 0);

tampil('5. update: NIM sendiri, nama diganti', $service->update($id, array_merge($valid, ['nama' => 'Nama Baru'])));
tampil('6. update: NIM milik orang lain (2401001)', $service->update($id, array_merge($valid, ['nim' => '2401001'])));

if ($id > 0) {
    $repo->delete($id);
    echo "Data uji (id=$id) dihapus.\n";
}

echo '</pre>';