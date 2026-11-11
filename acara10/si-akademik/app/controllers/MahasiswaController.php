<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../repositories/MahasiswaRepository.php';
require_once __DIR__ . '/../models/Mahasiswa.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

class MahasiswaController extends BaseController
{
    private const URL_DAFTAR = '/bkpm_web/acara10/si-akademik/public/mahasiswa';

    private MahasiswaRepository $repo;

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

    // Daftar prodi untuk dropdown pada form
    private function daftarProdi(): array
    {
        return (new ProdiModel())->all();
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
            'prodi'  => $this->daftarProdi(),
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

        $errors = [];
        $mahasiswa = $this->buatMahasiswa($_POST, $errors);

        if (!empty($errors)) {
            $this->view('mahasiswa/create', [
                'prodi'  => $this->daftarProdi(),
                'errors' => $errors,
            ]);
            return;
        }

        try {
            $this->repo->create($mahasiswa->toArray());
            $this->redirect(self::URL_DAFTAR);

        } catch (PDOException $e) {
            $errors[] = 'Gagal menyimpan data. Periksa apakah NIM sudah digunakan.';

            $this->view('mahasiswa/create', [
                'prodi'  => $this->daftarProdi(),
                'errors' => $errors,
            ]);
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

        $this->view('mahasiswa/edit', [
            'mahasiswa' => $mahasiswa,
            'prodi'     => $this->daftarProdi(),
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
            $this->view('mahasiswa/edit', [
                'mahasiswa' => $isianForm,
                'prodi'     => $this->daftarProdi(),
                'errors'    => $errors,
            ]);
            return;
        }

        try {
            $this->repo->update($id, $mahasiswa->toArray());
            $this->redirect(self::URL_DAFTAR);

        } catch (PDOException $e) {
            $errors[] = 'Gagal memperbarui data. Periksa apakah NIM sudah digunakan.';

            $this->view('mahasiswa/edit', [
                'mahasiswa' => $isianForm,
                'prodi'     => $this->daftarProdi(),
                'errors'    => $errors,
            ]);
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
            if (!$this->repo->find($id)) {
                http_response_code(404);
                exit('Data mahasiswa tidak ditemukan.');
            }

            $this->repo->delete($id);
            $this->redirect(self::URL_DAFTAR);

        } catch (PDOException $e) {
            exit('Gagal menghapus data mahasiswa.');
        }
    }
}