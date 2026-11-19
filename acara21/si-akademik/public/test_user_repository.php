<?php

require_once __DIR__ . '/../app/core/autoload.php';

$db = Database::getInstance();
$repo = new UserRepository($db);

echo '<pre>';

echo "ujiuser sudah ada sebelum dibuat? : ";
var_dump($repo->existsByUsername('ujiuser'));

$hash = password_hash('rahasia123', PASSWORD_DEFAULT);
$id = (int) $repo->create([
    'username' => 'ujiuser',
    'email'    => 'uji@email.com',
    'password' => $hash,
]);
echo "ID pengguna baru: $id\n\n";

echo "existsByUsername('ujiuser')    : "; var_dump($repo->existsByUsername('ujiuser'));
echo "existsByEmail('uji@email.com') : "; var_dump($repo->existsByEmail('uji@email.com'));
echo "existsByUsername('tidakada')   : "; var_dump($repo->existsByUsername('tidakada'));

$user = $repo->findByUsername('ujiuser');
echo "\nrole bawaan                        : " . $user['role'] . "\n";
echo "panjang hash di database           : " . strlen($user['password']) . "\n";
echo "password tersimpan sebagai teks?   : "; var_dump($user['password'] === 'rahasia123');
echo "password_verify (password benar)   : "; var_dump(password_verify('rahasia123', $user['password']));
echo "password_verify (password salah)   : "; var_dump(password_verify('salahsalah', $user['password']));

$db->prepare("DELETE FROM users WHERE id = :id")->execute(['id' => $id]);
echo "\nData uji (id=$id) dihapus.\n";

echo '</pre>';