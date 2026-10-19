<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa - SI Akademik</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Mahasiswa</h2>
        <a href="form.php" class="btn btn-primary">
            + Tambah Mahasiswa
        </a>
    </div>
    <div class="card shadow">
        <div class="card-body"><?php

require_once __DIR__ . '/../../models/Mahasiswa.php';

$mahasiswa1 = new Mahasiswa(
    "E41251100",
    "Napoleon Bonaparte",
    "Teknik Informatika"
);

$mahasiswa2 = new Mahasiswa(
    "E41251101",
    "Davina Karamoy",
    "Teknik Informatika"
);

$mahasiswa3 = new Mahasiswa(
    "E41251102",
    "Casey Kaspol",
    "Sistem Informasi"
);

$mahasiswa = [
    $mahasiswa1,
    $mahasiswa2,
    $mahasiswa3
];

include __DIR__ . '/../partials/header.php';
include __DIR__ . '/../partials/navbar.php';

?>

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Data Mahasiswa</h2>
        <button class="btn btn-primary">
            + Tambah Mahasiswa
        </button>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                            <th>Angkatan</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($mahasiswa as $index => $mhs): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= $mhs->getNim() ?></td>
                            <td><?= $mhs->getNama() ?></td>
                            <td><?= $mhs->getProdi() ?></td>
                            <td><?= $mhs->getAngkatan() ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php

include __DIR__ . '/../partials/footer.php';

?>
        </div>
    </div>
</div>
</body>
</html>