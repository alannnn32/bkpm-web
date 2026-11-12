<form action="/bkpm_web/acara14/si-akademik/public/mahasiswa/update" method="POST" novalidate>
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header">
            <h4>Edit Data Mahasiswa</h4>
        </div>

        <div class="card-body">
                        <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            <form action="/bkpm_web/acara14/si-akademik/public/mahasiswa/update"
                  method="POST">

                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($mahasiswa['id']) ?>">

                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text" name="nim" class="form-control"
                           value="<?= htmlspecialchars($mahasiswa['nim']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama</label>
                    <input type="text" name="nama" class="form-control"
                           value="<?= htmlspecialchars($mahasiswa['nama']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control"
                           value="<?= htmlspecialchars($mahasiswa['email']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>
                    <select name="prodi_id" class="form-select" required>
                        <?php foreach ($prodi as $p): ?>
                            <option value="<?= $p['id'] ?>"
                                <?= $mahasiswa['prodi_id'] == $p['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Angkatan</label>
                    <input type="number" name="angkatan" class="form-control"
                           value="<?= htmlspecialchars($mahasiswa['angkatan']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <?php foreach (['aktif', 'cuti', 'lulus'] as $status): ?>
                            <option value="<?= $status ?>"
                                <?= $mahasiswa['status'] === $status ? 'selected' : '' ?>>
                                <?= ucfirst($status) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="/bkpm_web/acara14/si-akademik/public/mahasiswa"
                   class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</div>
