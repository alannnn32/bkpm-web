<?php

require_once __DIR__ . '/../repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../Models/ProdiModel.php';
require_once __DIR__ . '/../models/Mahasiswa.php';

class MahasiswaController
{
    private MahasiswaRepository $repo;

    // Constructor injection: controller tidak membuat koneksi sendiri
    public function __construct(MahasiswaRepository $repo)
    {
        $this->repo = $repo;
    }

        // Mengisi objek Mahasiswa lewat setter; semua error dikumpulkan
    private function buatMahasiswa(array $input, array &$errors): Mahasiswa
    {
        $mahasiswa = new Mahasiswa();

        $isian = [
            'setNim'      => (string) ($input['nim'] ?? ''),
            'setNama'     => (string) ($input['nama'] ?? ''),
            'setEmail'    => (string) ($input['email'] ?? ''),
            'setProdiId'  => (int) ($input['prodi_id'] ?? 0),
            'setAngkatan' => (int) ($input['angkatan'] ?? 0),
            'setStatus'   => (string) ($input['status'] ?? ''),
        ];

        foreach ($isian as $setter => $nilai) {
            try {
                $mahasiswa->$setter($nilai);
            } catch (InvalidArgumentException $e) {
                $errors[] = $e->getMessage();
            }
        }

        return $mahasiswa;
    }

    // Menampilkan ulang form edit (dipakai saat update gagal)
    private function tampilFormEdit(array $mahasiswa, array $errors): void
    {
        $prodiModel = new ProdiModel();
        $prodi = $prodiModel->all();

        require __DIR__ . '/../Views/partials/header.php';
        require __DIR__ . '/../Views/mahasiswa/edit.php';
        require __DIR__ . '/../Views/partials/footer.php';
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

        require __DIR__ . '/../Views/partials/header.php';
        require __DIR__ . '/../Views/mahasiswa/index.php';
        require __DIR__ . '/../Views/partials/footer.php';
    }

    // Menampilkan form tambah mahasiswa
    public function create()
    {
        $prodiModel = new ProdiModel();
        $prodi = $prodiModel->all();
        $errors = [];

        require __DIR__ . '/../Views/partials/header.php';
        require __DIR__ . '/../Views/mahasiswa/create.php';
        require __DIR__ . '/../Views/partials/footer.php';
    }

        // Memproses penyimpanan mahasiswa
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method tidak diizinkan.');
        }

        $errors = [];
        $mahasiswa = $this->buatMahasiswa($_POST, $errors);

        $prodiModel = new ProdiModel();
        $prodi = $prodiModel->all();

        if (!empty($errors)) {
            require __DIR__ . '/../Views/partials/header.php';
            require __DIR__ . '/../Views/mahasiswa/create.php';
            require __DIR__ . '/../Views/partials/footer.php';
            return;
        }

        try {
            $this->repo->create($mahasiswa->toArray());

            header('Location: /bkpm_web/acara9/si-akademik/public/mahasiswa');
            exit;

        } catch (PDOException $e) {
            $errors[] = 'Gagal menyimpan data. Periksa apakah NIM sudah digunakan.';

            require __DIR__ . '/../Views/partials/header.php';
            require __DIR__ . '/../Views/mahasiswa/create.php';
            require __DIR__ . '/../Views/partials/footer.php';
        }
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

        $prodiModel = new ProdiModel();
        $prodi = $prodiModel->all();

        $errors = [];

        require __DIR__ . '/../Views/partials/header.php';
        require __DIR__ . '/../Views/mahasiswa/edit.php';
        require __DIR__ . '/../Views/partials/footer.php';
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

        $errors = [];
        $mahasiswa = $this->buatMahasiswa($_POST, $errors);

        // Isian pengguna dipertahankan agar form tidak kosong kembali
        $isianForm = array_merge($dataLama, [
            'nim'      => $_POST['nim'] ?? '',
            'nama'     => $_POST['nama'] ?? '',
            'email'    => $_POST['email'] ?? '',
            'prodi_id' => $_POST['prodi_id'] ?? '',
            'angkatan' => $_POST['angkatan'] ?? '',
            'status'   => $_POST['status'] ?? '',
        ]);

        if (!empty($errors)) {
            $this->tampilFormEdit($isianForm, $errors);
            return;
        }

        try {
            $this->repo->update($id, $mahasiswa->toArray());

            header('Location: /bkpm_web/acara9/si-akademik/public/mahasiswa');
            exit;

        } catch (PDOException $e) {
            $errors[] = 'Gagal memperbarui data. Periksa apakah NIM sudah digunakan.';
            $this->tampilFormEdit($isianForm, $errors);
        }
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

        try {
            $mahasiswa = $this->repo->find($id);

            if (!$mahasiswa) {
                http_response_code(404);
                exit('Data mahasiswa tidak ditemukan.');
            }

            $this->repo->delete($id);

            header('Location: /bkpm_web/acara9/si-akademik/public/mahasiswa');
            exit;

        } catch (PDOException $e) {
            exit('Gagal menghapus data mahasiswa.');
        }
    }
}