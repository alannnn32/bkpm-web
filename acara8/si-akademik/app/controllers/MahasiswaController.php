
<?php

require_once __DIR__ . '/../Models/MahasiswaModel.php';

class MahasiswaController
{
    public function index()
    {
        $model = new MahasiswaModel();
        $mahasiswa = $model->all();

        require __DIR__ . '/../Views/partials/header.php';
        ?>

        <div class="container mt-5 mb-5">
            <div class="card shadow">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h2>Daftar Mahasiswa</h2>

                        <a href="/bkpm_web/acara8/si-akademik/public/mahasiswa/create"
                           class="btn btn-primary">
                            + Tambah Mahasiswa
                        </a>
                    </div>

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
                                    <th>Aksi</th>
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

                                        <td>
                                            <a href="/bkpm_web/acara8/si-akademik/public/mahasiswa/edit?id=<?= (int) $mhs['id'] ?>"
                                               class="btn btn-warning btn-sm">
                                                Edit
                                            </a>

                                            <form action="/bkpm_web/acara8/si-akademik/public/mahasiswa/delete"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Yakin ingin menghapus mahasiswa ini?')">

                                                <input type="hidden"
                                                       name="id"
                                                       value="<?= (int) $mhs['id'] ?>">

                                                <button type="submit"
                                                        class="btn btn-danger btn-sm">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>

                                <?php if (empty($mahasiswa)): ?>
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Belum ada data mahasiswa.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>

                    <a href="/bkpm_web/acara8/si-akademik/public/dashboard"
                       class="btn btn-secondary">
                        Kembali ke Dashboard
                    </a>

                </div>
            </div>
        </div>

        <?php
        require __DIR__ . '/../Views/partials/footer.php';
    }
}
