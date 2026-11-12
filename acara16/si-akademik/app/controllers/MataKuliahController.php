
<?php

require_once __DIR__ . '/../Models/MataKuliahModel.php';
require_once __DIR__ . '/../Models/ProdiModel.php';

class MataKuliahController
{
    // Menampilkan daftar mata kuliah
    public function index()
    {
        $model = new MataKuliahModel();
        $matakuliah = $model->all();

        require __DIR__ . '/../Views/partials/header.php';
        require __DIR__ . '/../Views/matakuliah/index.php';
        require __DIR__ . '/../Views/partials/footer.php';
    }

    // Menampilkan form tambah
    public function create()
    {
        $prodiModel = new ProdiModel();
        $prodi = $prodiModel->all();

        require __DIR__ . '/../Views/partials/header.php';
        require __DIR__ . '/../Views/matakuliah/create.php';
        require __DIR__ . '/../Views/partials/footer.php';
    }

    // Menyimpan mata kuliah baru
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method tidak diizinkan.');
        }

        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $sks = (int) ($_POST['sks'] ?? 0);
        $prodiId = (int) ($_POST['prodi_id'] ?? 0);

        if ($kode === '' || $nama === '' ||
            $sks < 1 || $sks > 255 || $prodiId <= 0) {
            exit('Data mata kuliah tidak valid.');
        }

        try {
            $model = new MataKuliahModel();

            $model->create([
                'kode' => $kode,
                'nama' => $nama,
                'sks' => $sks,
                'prodi_id' => $prodiId
            ]);

            header('Location: /bkpm_web/acara16/si-akademik/public/matakuliah');
            exit;

        } catch (PDOException $e) {
            exit('Gagal menyimpan mata kuliah. Periksa kembali data.');
        }
    }

    // Menampilkan form edit
    public function edit()
    {
        $id = (int) ($_GET['id'] ?? 0);

        $model = new MataKuliahModel();
        $matakuliah = $model->find($id);

        if (!$matakuliah) {
            http_response_code(404);
            exit('Data mata kuliah tidak ditemukan.');
        }

        $prodiModel = new ProdiModel();
        $prodi = $prodiModel->all();

        require __DIR__ . '/../Views/partials/header.php';
        require __DIR__ . '/../Views/matakuliah/edit.php';
        require __DIR__ . '/../Views/partials/footer.php';
    }

    // Memperbarui mata kuliah
    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method tidak diizinkan.');
        }

        $id = (int) ($_POST['id'] ?? 0);
        $kode = trim($_POST['kode'] ?? '');
        $nama = trim($_POST['nama'] ?? '');
        $sks = (int) ($_POST['sks'] ?? 0);
        $prodiId = (int) ($_POST['prodi_id'] ?? 0);

        if ($id <= 0 || $kode === '' || $nama === '' ||
            $sks < 1 || $sks > 255 || $prodiId <= 0) {
            exit('Data mata kuliah tidak valid.');
        }

        try {
            $model = new MataKuliahModel();

            if (!$model->find($id)) {
                http_response_code(404);
                exit('Data mata kuliah tidak ditemukan.');
            }

            $model->update($id, [
                'kode' => $kode,
                'nama' => $nama,
                'sks' => $sks,
                'prodi_id' => $prodiId
            ]);

            header('Location: /bkpm_web/acara16/si-akademik/public/matakuliah');
            exit;

        } catch (PDOException $e) {
            exit('Gagal memperbarui data mata kuliah.');
        }
    }

    // Menghapus mata kuliah
    public function destroy()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Method tidak diizinkan.');
        }

        $id = (int) ($_POST['id'] ?? 0);

        if ($id <= 0) {
            exit('ID mata kuliah tidak valid.');
        }

        try {
            $model = new MataKuliahModel();

            if (!$model->find($id)) {
                http_response_code(404);
                exit('Data mata kuliah tidak ditemukan.');
            }

            $model->delete($id);

            header('Location: /bkpm_web/acara16/si-akademik/public/matakuliah');
            exit;

        } catch (PDOException $e) {
            exit('Mata kuliah tidak dapat dihapus karena masih digunakan oleh data lain.');
        }
    }
}
