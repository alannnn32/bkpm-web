<?php

require_once __DIR__ . '/../app/core/Database.php';
require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../app/repositories/ProdiRepository.php';
require_once __DIR__ . '/../app/services/MahasiswaService.php';

$db = Database::getInstance();

// Repository "rusak": selalu melempar error saat menulis
$repoRusak = new class($db) extends MahasiswaRepository {
    public function create(array $data): string
    {
        throw new PDOException('Simulasi: SQLSTATE[HY000] koneksi database terputus');
    }

    public function update(int $id, array $data): bool
    {
        throw new PDOException('Simulasi: SQLSTATE[HY000] koneksi database terputus');
    }
};

$service = new MahasiswaService($repoRusak, new ProdiRepository($db));

$valid = [
    'nim' => 'TEST0002', 'nama' => 'Mahasiswa Uji', 'email' => 'uji@email.com',
    'prodi_id' => 1, 'angkatan' => 2026, 'status' => 'aktif',
];

function tampil(string $label, array $hasil): void
{
    echo "--- $label\n";
    echo $hasil['success'] ? "success: true\n" : "success: false\n";
    foreach ($hasil['errors'] ?? [] as $e) {
        echo "  * $e\n";
    }
    echo "\n";
}

echo '<pre>';

tampil('create (database rusak)', $service->create($valid));
tampil('update id=1 (database rusak)', $service->update(1, $valid));

echo "4 baris terakhir app.log:\n";
$baris = file(__DIR__ . '/../storage/logs/app.log');
echo htmlspecialchars(implode('', array_slice($baris, -4)));

echo '</pre>';