
<?php

require_once __DIR__ . '/../Models/MahasiswaModel.php';

class MahasiswaController
{
    public function index()
    {
        // Mengambil data mahasiswa dari database
        $model = new MahasiswaModel();
        $mahasiswa = $model->all();

        // Menampilkan header
        require __DIR__ . '/../Views/partials/header.php';
        ?>

        <div class="container mt-5 mb-5">

            <div class="card shadow">
                <div class="card-body">

                    <h2 class="mb-4">Daftar Mahasiswa</h2>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">

                            <thead class="table-primary">
                                <tr>
                                    <th>No</th>
                                    <th>NIM</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th>Program Studi</th>
                                    <th>Angkatan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($mahasiswa as $index => $mhs): ?>
                                    <tr>
                                        <td><?= $index + 1 ?></td>
                                        <td><?= htmlspecialchars($mhs['nim']) ?></td>
                                        <td><?= htmlspecialchars($mhs['nama']) ?></td>
                                        <td><?= htmlspecialchars($mhs['email']) ?></td>
                                        <td><?= htmlspecialchars($mhs['nama_prodi']) ?></td>
                                        <td><?= htmlspecialchars((string) $mhs['angkatan']) ?></td>
                                        <td><?= htmlspecialchars($mhs['status']) ?></td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($mahasiswa)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Belum ada data mahasiswa.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>

                    <a
                        href="/bkpm_web/acara8/si-akademik/public/dashboard"
                        class="btn btn-secondary"
                    >
                        Kembali ke Dashboard
                    </a>

                </div>
            </div>

        </div>

        <?php
        // Menampilkan footer
        require __DIR__ . '/../Views/partials/footer.php';
    }
}
