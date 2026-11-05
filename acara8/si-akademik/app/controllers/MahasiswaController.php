
<?php

require_once __DIR__ . '/../Models/MahasiswaModel.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

class MahasiswaController
{
    // Menampilkan daftar mahasiswa
    public function index()
    {
        $model = new MahasiswaModel();
        $mahasiswa = $model->all();

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

        $nim = trim($_POST['nim'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $prodiId = (int) ($_POST['prodi_id'] ?? 0);
        $angkatan = (int) ($_POST['angkatan'] ?? 0);
        $status = $_POST['status'] ?? '';

        $errors = [];

        if ($nim === '') {
            $errors[] = 'NIM wajib diisi.';
        }

        if ($nama === '') {
            $errors[] = 'Nama wajib diisi.';
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        }

        if ($prodiId <= 0) {
            $errors[] = 'Program studi wajib dipilih.';
        }

        if ($angkatan < 1901 || $angkatan > 2155) {
            $errors[] = 'Angkatan tidak valid.';
        }

        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            $errors[] = 'Status tidak valid.';
        }

        $prodiModel = new ProdiModel();
        $prodi = $prodiModel->all();

        if (!empty($errors)) {
            require __DIR__ . '/../Views/partials/header.php';
            require __DIR__ . '/../Views/mahasiswa/create.php';
            require __DIR__ . '/../Views/partials/footer.php';
            return;
        }

        try {
            $model = new MahasiswaModel();

            $model->create([
                'nim' => $nim,
                'nama' => $nama,
                'email' => $email,
                'prodi_id' => $prodiId,
                'angkatan' => $angkatan,
                'status' => $status
            ]);

            header('Location: /bkpm_web/acara8/si-akademik/public/mahasiswa');
            exit;

        } catch (PDOException $e) {
            $errors[] = 'Gagal menyimpan data. Periksa apakah NIM sudah digunakan.';

            require __DIR__ . '/../Views/partials/header.php';
            require __DIR__ . '/../Views/mahasiswa/create.php';
            require __DIR__ . '/../Views/partials/footer.php';
        }
    }
}
