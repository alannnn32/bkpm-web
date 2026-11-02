
<?php

require_once __DIR__ . '/../app/Models/MahasiswaModel.php';
require_once __DIR__ . '/../app/Models/ProdiModel.php';
require_once __DIR__ . '/../app/Models/MataKuliahModel.php';

try {
    $mahasiswaModel = new MahasiswaModel();
    $prodiModel = new ProdiModel();
    $mataKuliahModel = new MataKuliahModel();

    echo "<h3>Pengujian Model Berhasil!</h3>";

    echo "Jumlah Mahasiswa: " . count($mahasiswaModel->all()) . "<br>";
    echo "Jumlah Prodi: " . count($prodiModel->all()) . "<br>";
    echo "Jumlah Mata Kuliah: " . count($mataKuliahModel->all());

} catch (PDOException $e) {
    echo "Terjadi kesalahan: " . $e->getMessage();
}
