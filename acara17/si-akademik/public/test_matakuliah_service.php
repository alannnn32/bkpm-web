<?php

require_once __DIR__ . '/../app/core/autoload.php';

$db = Database::getInstance();
$repo = new MataKuliahRepository($db);
$service = new MataKuliahService($repo, new ProdiRepository($db));

function tampil(string $label, array $hasil): void
{
    echo "--- $label\n";
    echo $hasil['success'] ? "success: true\n" : "success: false\n";
    foreach ($hasil['errors'] ?? [] as $e) {
        echo "  * $e\n";
    }
    echo "\n";
}

$valid = ['kode' => 'TEST101', 'nama' => 'Mata Kuliah Uji', 'sks' => 3, 'prodi_id' => 1];

echo '<pre>';

tampil('1. create: input kosong', $service->create([]));
tampil('2. create: kode kembar (TI101)', $service->create(array_merge($valid, ['kode' => 'TI101'])));
tampil('3. create: sks 9', $service->create(array_merge($valid, ['sks' => 9])));
tampil('4. create: prodi 999', $service->create(array_merge($valid, ['prodi_id' => 999])));

$hasil = $service->create($valid);
tampil('5. create: data valid', $hasil);
$id = (int) ($hasil['id'] ?? 0);

tampil('6. update: kode sendiri, nama diganti', $service->update($id, array_merge($valid, ['nama' => 'Nama Baru'])));
tampil('7. update: kode milik orang lain (TI101)', $service->update($id, array_merge($valid, ['kode' => 'TI101'])));
tampil('8. update: id 999', $service->update(999, $valid));

if ($id > 0) {
    $repo->delete($id);
    echo "Data uji (id=$id) dihapus.\n";
}

echo '</pre>';