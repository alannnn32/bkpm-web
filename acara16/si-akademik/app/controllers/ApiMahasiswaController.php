<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../services/MahasiswaService.php';

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

    // Satu pintu untuk /api/mahasiswa: method HTTP menentukan aksinya
    public function index()
    {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'GET') {
            $this->ambil();
        } elseif ($method === 'POST') {
            $this->simpan();
        } else {
            $this->json([
                'success' => false,
                'message' => 'Method tidak diizinkan.',
            ], 405);
        }
    }

    // GET /api/mahasiswa          -> semua mahasiswa
    // GET /api/mahasiswa?id=1     -> satu mahasiswa
    private function ambil(): void
    {
        if (isset($_GET['id'])) {
            $id = (int) $_GET['id'];

            if ($id <= 0) {
                $this->json([
                    'success' => false,
                    'message' => 'ID tidak valid.',
                ], 400);
            }

            $mahasiswa = $this->repo->find($id);

            if (!$mahasiswa) {
                $this->json([
                    'success' => false,
                    'message' => 'Data mahasiswa tidak ditemukan.',
                ], 404);
            }

            $this->json([
                'success' => true,
                'message' => 'Data berhasil diambil',
                'data'    => $mahasiswa,
            ]);
        }

        $this->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data'    => $this->repo->all(),
        ]);
    }

    // POST /api/mahasiswa  (body: JSON)
    private function simpan(): void
    {
        // Body request mentah, bukan $_POST (karena formatnya JSON)
        $raw = file_get_contents('php://input');
        $input = json_decode($raw, true);

        if (!is_array($input)) {
            $this->json([
                'success' => false,
                'message' => 'Body request harus berupa JSON yang valid.',
            ], 400);
        }

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
            'message' => 'Data berhasil ditambahkan',
            'data'    => $this->repo->find((int) $hasil['id']),
        ], 201);
    }
}