<?php

class ApiMahasiswaController extends BaseApiController
{
    private MahasiswaRepository $repo;
    private MahasiswaService $service;

    public function __construct(
        MahasiswaRepository $repo,
        MahasiswaService $service
    ) {
        $this->repo = $repo;
        $this->service = $service;
    }

    // /api/mahasiswa : method HTTP menentukan aksinya
    public function index()
    {
        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $this->ambil();
                break;

            case 'POST':
                $this->simpan();
                break;

            default:
                $this->methodTidakDiizinkan();
        }
    }

    // /api/mahasiswa/{id}
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

    // GET /api/mahasiswa  atau  GET /api/mahasiswa?id=1  (publik)
    private function ambil(): void
    {
        if (isset($_GET['id'])) {
            $this->tampilkanSatu((int) $_GET['id']);
        }

        Response::json(200, true, 'Data berhasil diambil', $this->repo->all());
    }

    // GET /api/mahasiswa/{id}  (publik)
    private function tampilkanSatu(int $id): void
    {
        $this->pastikanIdValid($id);

        $mahasiswa = $this->repo->find($id);

        if (!$mahasiswa) {
            $this->tidakDitemukan('Data mahasiswa tidak ditemukan.');
        }

        Response::json(200, true, 'Data berhasil diambil', $mahasiswa);
    }

    // POST /api/mahasiswa  (butuh token; body: JSON)
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
            'Data mahasiswa berhasil ditambahkan',
            $this->repo->find((int) $hasil['id'])
        );
    }

    // PUT /api/mahasiswa/{id}  (butuh token; body: JSON, semua field)
    private function ubah(int $id): void
    {
        Auth::requireAuth();

        $this->pastikanIdValid($id);

        if (!$this->repo->find($id)) {
            $this->tidakDitemukan('Data mahasiswa tidak ditemukan.');
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
            'Data mahasiswa berhasil diubah',
            $this->repo->find($id)
        );
    }

    // DELETE /api/mahasiswa/{id}  (butuh token)
    private function hapus(int $id): void
    {
        Auth::requireAuth();

        $this->pastikanIdValid($id);

        if (!$this->repo->find($id)) {
            $this->tidakDitemukan('Data mahasiswa tidak ditemukan.');
        }

        try {
            $this->repo->delete($id);
        } catch (PDOException $e) {
            Logger::error("ApiMahasiswaController::hapus id={$id} - " . $e->getMessage());

            Response::json(500, false, 'Data gagal dihapus.');
        }

        Response::json(200, true, 'Data mahasiswa berhasil dihapus');
    }
}