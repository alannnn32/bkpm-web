<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../repositories/ProdiRepository.php';
require_once __DIR__ . '/../services/MahasiswaService.php';
require_once __DIR__ . '/../core/Logger.php';

class MahasiswaController extends BaseController
{
    private const URL_DAFTAR = '/bkpm_web/acara17/si-akademik/public/mahasiswa';

    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;
    private MahasiswaService $service;

    public function __construct(
        MahasiswaRepository $repo,
        ProdiRepository $prodiRepo,
        MahasiswaService $service
    ) {
        $this->repo = $repo;
        $this->prodiRepo = $prodiRepo;
        $this->service = $service;
    }

    // Menampilkan daftar dan pencarian mahasiswa
    public function index()
    {
        $keyword = trim($_GET['keyword'] ?? '');

        if ($keyword !== '') {
            $mahasiswa = $this->repo->search($keyword);
        } else {
            $mahasiswa = $this->repo->all();
        }

        $this->view('mahasiswa/index', [
            'mahasiswa' => $mahasiswa,
            'keyword'   => $keyword,
        ]);
    }

    // Menampilkan form tambah mahasiswa
    public function create()
    {
        $this->view('mahasiswa/create', [
            'prodi'  => $this->prodiRepo->all(),
            'errors' => [],
        ]);
    }

        // Memproses penyimpanan mahasiswa
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method tidak diizinkan.');
        }

        $hasil = $this->service->create($_POST);

        if (!$hasil['success']) {
            $this->view('mahasiswa/create', [
                'prodi'  => $this->prodiRepo->all(),
                'errors' => $hasil['errors'],
            ]);
            return;
        }

        $this->setFlash('success', 'Data mahasiswa berhasil ditambahkan.');
        $this->redirect(self::URL_DAFTAR);
    }

    // Menampilkan form edit mahasiswa
    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $mahasiswa = $this->repo->find($id);

        if (!$mahasiswa) {
            http_response_code(404);
            exit('Data mahasiswa tidak ditemukan.');
        }

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa,
            'prodi'     => $this->prodiRepo->all(),
            'errors'    => [],
        ]);
    }

        // Memproses perubahan data mahasiswa
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method tidak diizinkan.');
        }

        $id = (int) ($_POST['id'] ?? 0);

        $dataLama = $this->repo->find($id);

        if (!$dataLama) {
            http_response_code(404);
            exit('Data mahasiswa tidak ditemukan.');
        }

        $hasil = $this->service->update($id, $_POST);

        if (!$hasil['success']) {
            // Isian pengguna dipertahankan agar form tidak kosong kembali
            $kolom = ['nim', 'nama', 'email', 'prodi_id', 'angkatan', 'status'];
            $isianForm = array_merge(
                $dataLama,
                array_intersect_key($_POST, array_flip($kolom))
            );

            $this->view('mahasiswa/edit', [
                'mahasiswa' => $isianForm,
                'prodi'     => $this->prodiRepo->all(),
                'errors'    => $hasil['errors'],
            ]);
            return;
        }

        $this->setFlash('success', 'Data mahasiswa berhasil diubah.');
        $this->redirect(self::URL_DAFTAR);
    }

        // Menghapus data mahasiswa
    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method tidak diizinkan.');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            exit('ID mahasiswa tidak valid.');
        }

        if (!$this->repo->find($id)) {
            http_response_code(404);
            exit('Data mahasiswa tidak ditemukan.');
        }

        try {
            $this->repo->delete($id);
            $this->setFlash('success', 'Data mahasiswa berhasil dihapus.');
                } catch (PDOException $e) {
            Logger::error("MahasiswaController::destroy id={$id} - " . $e->getMessage());
            $this->setFlash('danger', 'Data gagal dihapus.');
        }

        $this->redirect(self::URL_DAFTAR);
    }
}