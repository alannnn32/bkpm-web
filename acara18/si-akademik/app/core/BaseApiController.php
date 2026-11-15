<?php

// Fungsi bersama untuk semua controller API
abstract class BaseApiController extends BaseController
{
    // Membaca body JSON; kalau tidak valid langsung dibalas 400
    protected function bacaJson(): array
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!is_array($input)) {
            $this->json([
                'success' => false,
                'message' => 'Body request harus berupa JSON yang valid.',
            ], 400);
        }

        return $input;
    }

    protected function pastikanIdValid(int $id): void
    {
        if ($id <= 0) {
            $this->json([
                'success' => false,
                'message' => 'ID tidak valid.',
            ], 400);
        }
    }

    protected function tidakDitemukan(string $pesan = 'Data tidak ditemukan.'): void
    {
        $this->json([
            'success' => false,
            'message' => $pesan,
        ], 404);
    }

    protected function methodTidakDiizinkan(): void
    {
        $this->json([
            'success' => false,
            'message' => 'Method tidak diizinkan.',
        ], 405);
    }
}