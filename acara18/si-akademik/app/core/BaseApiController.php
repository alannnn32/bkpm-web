<?php

// Fungsi bersama untuk semua controller API
abstract class BaseApiController
{
    // Membaca body JSON; kalau tidak valid langsung dibalas 400
    protected function bacaJson(): array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input)) {
            Response::json(400, false, 'Body request harus berupa JSON yang valid.');
        }

        return $input;
    }

    protected function pastikanIdValid(int $id): void
    {
        if ($id <= 0) {
            Response::json(400, false, 'ID tidak valid.');
        }
    }

    protected function tidakDitemukan(string $pesan = 'Data tidak ditemukan.'): void
    {
        Response::json(404, false, $pesan);
    }

    protected function methodTidakDiizinkan(): void
    {
        Response::json(405, false, 'Method tidak diizinkan.');
    }
}