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
                Response::json(200, true, 'Data berhasil diambil', $this->repo->all());
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

    // GET /api/matakuliah/{id}  (publik)
    private function tampilkanSatu(int $id): void
    {
        $this->pastikanIdValid($id);

        $matakuliah = $this->repo->find($id);

        if (!$matakuliah) {
            $this->tidakDitemukan('Data mata kuliah tidak ditemukan.');
        }

        Response::json(200, true, 'Data berhasil diambil', $matakuliah);
    }

    // POST /api/matakuliah  (butuh token; body: JSON)
    private function simpan(): void
    {
        Auth::requireAuth();

        $hasil = $this->service->create($this->bacaJson());

        if (!$hasil['success']) {
            Response::json(
                422,
                false,
                'Data tidak valid atau gagal disimpan.',
                null,
                $hasil['errors']
            );
        }

        Response::json(
            201,
            true,
            'Data mata kuliah berhasil ditambahkan',
            $this->repo->find((int) $hasil['id'])
        );
    }

    // PUT /api/matakuliah/{id}  (butuh token; body: JSON, semua field)
    private function ubah(int $id): void
    {
        Auth::requireAuth();

        $this->pastikanIdValid($id);

        if (!$this->repo->find($id)) {
            $this->tidakDitemukan('Data mata kuliah tidak ditemukan.');
        }

        $hasil = $this->service->update($id, $this->bacaJson());

        if (!$hasil['success']) {
            Response::json(
                422,
                false,
                'Data tidak valid atau gagal diubah.',
                null,
                $hasil['errors']
            );
        }

        Response::json(
            200,
            true,
            'Data mata kuliah berhasil diubah',
            $this->repo->find($id)
        );
    }

    // DELETE /api/matakuliah/{id}  (butuh token)
    private function hapus(int $id): void
    {
        Auth::requireAuth();

        $this->pastikanIdValid($id);

        if (!$this->repo->find($id)) {
            $this->tidakDitemukan('Data mata kuliah tidak ditemukan.');
        }

        try {
            $this->repo->delete($id);
        } catch (PDOException $e) {
            Logger::error("ApiMataKuliahController::hapus id={$id} - " . $e->getMessage());

            Response::json(500, false, 'Data gagal dihapus.');
        }

        Response::json(200, true, 'Data mata kuliah berhasil dihapus');
    }
}