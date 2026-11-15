<?php

class ApiMataKuliahController extends BaseApiController
{
    private MataKuliahRepository $repo;
    private MataKuliahService $service;

    public function __construct(
        MataKuliahRepository $repo,
        MataKuliahService $service
    ) {
        $this->repo = $repo;
        $this->service = $service;
    }

    // /api/matakuliah : method HTTP menentukan aksinya
    public function index()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->ambilSemua();
                break;

            case 'POST':
                $this->simpan();
                break;

            default:
                $this->methodTidakDiizinkan();
        }
    }

    // /api/matakuliah/{id}
    public function item(array $params = [])
    {
        $id = (int) ($params['id'] ?? 0);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->tampilkanSatu($id);
                break;

            case 'PUT':
                $this->ubah($id);
                break;

            case 'DELETE':
                $this->hapus($id);
                break;

            default:
                $this->methodTidakDiizinkan();
        }
    }

    // GET /api/matakuliah
    private function ambilSemua(): void
    {
        $this->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data'    => $this->repo->all(),
        ]);
    }

    // GET /api/matakuliah/{id}
    private function tampilkanSatu(int $id): void
    {
        $this->pastikanIdValid($id);

        $matakuliah = $this->repo->find($id);

        if (!$matakuliah) {
            $this->tidakDitemukan('Data mata kuliah tidak ditemukan.');
        }

        $this->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data'    => $matakuliah,
        ]);
    }

    // POST /api/matakuliah  (body: JSON)
    private function simpan(): void
    {
        $hasil = $this->service->create($this->bacaJson());

        if (!$hasil['success']) {
            $this->json([
                'success' => false,
                'message' => 'Data tidak valid atau gagal disimpan.',
                'errors'  => $hasil['errors'],
            ], 422);
        }

        $this->json([
            'success' => true,
            'message' => 'Data mata kuliah berhasil ditambahkan',
            'data'    => $this->repo->find((int) $hasil['id']),
        ], 201);
    }

    // PUT /api/matakuliah/{id}  (body: JSON, semua field)
    private function ubah(int $id): void
    {
        $this->pastikanIdValid($id);

        if (!$this->repo->find($id)) {
            $this->tidakDitemukan('Data mata kuliah tidak ditemukan.');
        }

        $hasil = $this->service->update($id, $this->bacaJson());

        if (!$hasil['success']) {
            $this->json([
                'success' => false,
                'message' => 'Data tidak valid atau gagal diubah.',
                'errors'  => $hasil['errors'],
            ], 422);
        }

        $this->json([
            'success' => true,
            'message' => 'Data mata kuliah berhasil diubah',
            'data'    => $this->repo->find($id),
        ]);
    }

    // DELETE /api/matakuliah/{id}
    private function hapus(int $id): void
    {
        $this->pastikanIdValid($id);

        if (!$this->repo->find($id)) {
            $this->tidakDitemukan('Data mata kuliah tidak ditemukan.');
        }

        try {
            $this->repo->delete($id);
        } catch (PDOException $e) {
            Logger::error("ApiMataKuliahController::hapus id={$id} - " . $e->getMessage());

            $this->json([
                'success' => false,
                'message' => 'Data gagal dihapus.',
            ], 500);
        }

        $this->json([
            'success' => true,
            'message' => 'Data mata kuliah berhasil dihapus',
        ]);
    }
}