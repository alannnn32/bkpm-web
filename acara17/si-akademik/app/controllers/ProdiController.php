
<?php

require_once __DIR__ . '/../Models/ProdiModel.php';

class ProdiController
{
    // Menampilkan daftar prodi
    public function index()
    {
        $model = new ProdiModel();
        $prodi = $model->all();

        require __DIR__ . '/../Views/partials/header.php';
        require __DIR__ . '/../Views/prodi/index.php';
        require __DIR__ . '/../Views/partials/footer.php';
    }

    // Menampilkan form tambah prodi
    public function create()
    {
        require __DIR__ . '/../Views/partials/header.php';
        require __DIR__ . '/../Views/prodi/create.php';
        require __DIR__ . '/../Views/partials/footer.php';
    }

    // Menyimpan prodi baru
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method tidak diizinkan.');
        }

        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');

        if ($kode === '' || $nama === '') {
            exit('Kode dan nama prodi wajib diisi.');
        }

        try {
            $model = new ProdiModel();
            $model->create([
                'kode' => $kode,
                'nama' => $nama
            ]);

            header('Location: /bkpm_web/acara17/si-akademik/public/prodi');
            exit;

        } catch (PDOException $e) {
            exit('Gagal menyimpan prodi. Periksa kembali kode prodi.');
        }
    }

    // Menampilkan form edit prodi
    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $model = new ProdiModel();
        $prodi = $model->find($id);

        if (!$prodi) {
            http_response_code(404);
            exit('Data prodi tidak ditemukan.');
        }

        require __DIR__ . '/../Views/partials/header.php';
        require __DIR__ . '/../Views/prodi/edit.php';
        require __DIR__ . '/../Views/partials/footer.php';
    }

    // Memperbarui data prodi
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method tidak diizinkan.');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');

        if ($id <= 0 || $kode === '' || $nama === '') {
            exit('Data prodi tidak valid.');
        }

        try {
            $model = new ProdiModel();

            if (!$model->find($id)) {
                http_response_code(404);
                exit('Data prodi tidak ditemukan.');
            }

            $model->update($id, [
                'kode' => $kode,
                'nama' => $nama
            ]);

            header('Location: /bkpm_web/acara17/si-akademik/public/prodi');
            exit;

        } catch (PDOException $e) {
            exit('Gagal memperbarui data prodi.');
        }
    }

    // Menghapus data prodi
    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method tidak diizinkan.');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            exit('ID prodi tidak valid.');
        }

        try {
            $model = new ProdiModel();

            if (!$model->find($id)) {
                http_response_code(404);
                exit('Data prodi tidak ditemukan.');
            }

            $model->delete($id);

            header('Location: /bkpm_web/acara17/si-akademik/public/prodi');
            exit;

        } catch (PDOException $e) {
            exit('Prodi tidak dapat dihapus. Pastikan tidak ada mahasiswa yang menggunakan prodi ini.');
        }
    }
}
