<?php

require_once __DIR__ . '/../app/core/autoload.php';

$db = Database::getInstance();
$users = new UserRepository($db);
$service = new AuthService($users);

function tampil(string $label, array $hasil): void
{
    echo "--- $label\n";
    echo $hasil['success'] ? "success: true\n" : "success: false\n";
    foreach ($hasil['errors'] ?? [] as $e) {
        echo "  * $e\n";
    }
    echo "\n";
}

$dibuat = [];

echo '<pre>';

tampil('1. input kosong', $service->register([]));

tampil('2. format salah', $service->register([
    'username' => 'a b', 'email' => 'bukan-email', 'password' => '123',
]));

$hasil = $service->register([
    'username' => 'ujiuser2', 'email' => 'uji2@email.com', 'password' => 'rahasia123',
]);
tampil('3. data valid', $hasil);
if (!empty($hasil['id'])) {
    $dibuat[] = $hasil['id'];
}

tampil('4. username kembar', $service->register([
    'username' => 'ujiuser2', 'email' => 'lain@email.com', 'password' => 'rahasia123',
]));

tampil('5. email kembar', $service->register([
    'username' => 'ujiuser3', 'email' => 'uji2@email.com', 'password' => 'rahasia123',
]));

$hasil = $service->register([
    'username' => 'ujiuser4', 'email' => 'uji4@email.com',
    'password' => 'rahasia123', 'role' => 'admin',
]);
tampil('6. mencoba mendaftar sebagai admin', $hasil);
if (!empty($hasil['id'])) {
    $dibuat[] = $hasil['id'];
}

$u4 = $users->findByUsername('ujiuser4');
echo "role ujiuser4 di database : " . ($u4['role'] ?? '-') . "\n";

$u2 = $users->findByUsername('ujiuser2');
echo "password ujiuser2 berupa hash yang cocok? ";
var_dump($u2 && $u2['password'] !== 'rahasia123' && password_verify('rahasia123', $u2['password']));

foreach ($dibuat as $id) {
    $db->prepare("DELETE FROM users WHERE id = :id")->execute(['id' => $id]);
}
echo "\nData uji dihapus.\n";

echo '</pre>';