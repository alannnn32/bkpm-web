<?php

require_once __DIR__ . '/../app/core/autoload.php';
require_once __DIR__ . '/../config/app.php';
date_default_timezone_set('Asia/Jakarta');

$kode = (int) ($_GET['kode'] ?? 200);

switch ($kode) {
    case 201:
        Response::json(201, true, 'Data berhasil dibuat', ['id' => 21]);
    case 400:
        Response::json(400, false, 'Request tidak valid');
    case 404:
        Response::json(404, false, 'Data tidak ditemukan');
    case 422:
        Response::json(422, false, 'Data tidak valid', null, [
            'NIM wajib diisi.',
            'Format email tidak valid.',
        ]);
    case 500:
        Response::json(500, false, 'Terjadi kesalahan pada server.');
    default:
        Response::json(200, true, 'Data berhasil diambil', ['nama' => 'Budi']);
}