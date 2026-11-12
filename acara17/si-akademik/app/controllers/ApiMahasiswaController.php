<?php


class ApiMahasiswaController extends BaseController
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
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $this->ambil();
        } elseif ($method === 'POST') {
            $this->simpan();
        } else {
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

    // GET /api/mahasiswa  atau  GET /api/mahasiswa?id=1
    private function ambil(): void
    {
        if (isset($_GET['id'])) {
            $this->tampilkanSatu((int) $_GET['id']);
        }

        $this->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data'    => $this->repo->all(),
        ]);
    }

    // GET /api/mahasiswa/{id}
    private function tampilkanSatu(int $id): void
    {
        $this->pastikanIdValid($id);

        $mahasiswa = $this->repo->find($id);

        if (!$mahasiswa) {
            $this->tidakDitemukan();
        }

        $this->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data'    => $mahasiswa,
        ]);
    }

    // POST /api/mahasiswa  (body: JSON)
    private function simpan(): void
    {
        $input = $this->bacaJson();

        $hasil = $this->service->create($input);

        if (!$hasil['success']) {
            $this->json([
                'success' => false,
                'message' => 'Data tidak valid atau gagal disimpan.',
                'errors'  => $hasil['errors'],
            ], 422);
        }

        $this->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil ditambahkan',
            'data'    => $this->repo->find((int) $hasil['id']),
        ], 201);
    }

    // PUT /api/mahasiswa/{id}  (body: JSON, semua field)
    private function ubah(int $id): void
    {
        $this->pastikanIdValid($id);

        if (!$this->repo->find($id)) {
            $this->tidakDitemukan();
        }

        $input = $this->bacaJson();

        $hasil = $this->service->update($id, $input);

        if (!$hasil['success']) {
            $this->json([
                'success' => false,
                'message' => 'Data tidak valid atau gagal diubah.',
                'errors'  => $hasil['errors'],
            ], 422);
        }

        $this->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil diubah',
            'data'    => $this->repo->find($id),
        ]);
    }

    // DELETE /api/mahasiswa/{id}
    private function hapus(int $id): void
    {
        $this->pastikanIdValid($id);

        if (!$this->repo->find($id)) {
            $this->tidakDitemukan();
        }

        try {
            $this->repo->delete($id);
        } catch (PDOException $e) {
            Logger::error("ApiMahasiswaController::hapus id={$id} - " . $e->getMessage());

            $this->json([
                'success' => false,
                'message' => 'Data gagal dihapus.',
            ], 500);
        }

        $this->json([
            'success' => true,
            'message' => 'Data mahasiswa berhasil dihapus',
        ]);
    }

    // ===== Pembantu =====

    // Membaca body JSON; kalau tidak valid langsung dibalas 400
    private function bacaJson(): array
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

    private function pastikanIdValid(int $id): void
    {
        if ($id <= 0) {
            $this->json([
                'success' => false,
                'message' => 'ID tidak valid.',
            ], 400);
        }
    }

    private function tidakDitemukan(): void
    {
        $this->json([
            'success' => false,
            'message' => 'Data mahasiswa tidak ditemukan.',
        ], 404);
    }

    private function methodTidakDiizinkan(): void
    {
        $this->json([
            'success' => false,
            'message' => 'Method tidak diizinkan.',
        ], 405);
    }
}