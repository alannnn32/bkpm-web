<?php

require_once __DIR__ . '/../app/core/autoload.php';
require_once __DIR__ . '/../config/app.php';

function enc(string $d): string
{
    return rtrim(strtr(base64_encode($d), '+/', '-_'), '=');
}

function dec(string $d): string
{
    $d .= str_repeat('=', (4 - strlen($d) % 4) % 4);
    return base64_decode(strtr($d, '-_', '+/'));
}

function hasil(string $label, $nilai): void
{
    echo str_pad($label, 46) . ': ' . ($nilai === false ? 'DITOLAK' : 'DITERIMA') . "\n";
}

echo '<pre>';

$token = Auth::generateToken(['id' => 1, 'role' => 'user']);
[$h, $p, $s] = explode('.', $token);

echo "Token:\n$token\n\n";
echo "Header  : " . dec($h) . "\n";
echo "Payload : " . dec($p) . "\n\n";

hasil('1. token asli', Auth::validateToken($token));

// Payload diubah jadi admin, tanda tangan tidak dihitung ulang
$palsu = enc(json_encode(['id' => 1, 'role' => 'admin', 'exp' => time() + 3600]));
hasil('2. payload diubah (role admin), signature lama', Auth::validateToken("$h.$palsu.$s"));

// Tanda tangan diubah satu karakter
$sRusak = substr($s, 0, -1) . (substr($s, -1) === 'A' ? 'B' : 'A');
hasil('3. signature diubah satu karakter', Auth::validateToken("$h.$p.$sRusak"));

hasil('4. teks sembarang', Auth::validateToken('abc'));

// Token yang ditandatangani dengan benar, tapi sudah kedaluwarsa
$pKedaluwarsa = enc(json_encode(['id' => 1, 'role' => 'user', 'exp' => time() - 10]));
$sKedaluwarsa = enc(hash_hmac('sha256', "$h.$pKedaluwarsa", JWT_SECRET, true));
hasil('5. kedaluwarsa (exp 10 detik lalu)', Auth::validateToken("$h.$pKedaluwarsa.$sKedaluwarsa"));

echo '</pre>';