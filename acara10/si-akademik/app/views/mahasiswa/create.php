<form action="/bkpm_web/acara10/si-akademik/public/mahasiswa/store" method="POST" novalidate>
    <div class="container mt-5 mb-5">
        <div class="card shadow">
            <div class="card-body">

            <h2 class="mb-4">Tambah Mahasiswa</h2>

            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= htmlspecialchars($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/bkpm_web/acara10/si-akademik/public/mahasiswa/store"
                  method="POST">

                <div class="mb-3">
                    <label class="form-label">NIM</label>
                    <input type="text"
                           name="nim"
                           class="form-control"
                           maxlength="20"
                           value="<?= htmlspecialchars($_POST['nim'] ?? '') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Mahasiswa</label>
                    <input type="text"
                           name="nama"
                           class="form-control"
                           maxlength="100"
                           value="<?= htmlspecialchars($_POST['nama'] ?? '') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email"
                           name="email"
                           class="form-control"
                           maxlength="100"
                           value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>
                    <select name="prodi_id" class="form-select" required>
                        <option value="">-- Pilih Program Studi --</option>

                        <?php foreach ($prodi as $item): ?>
                            <option value="<?= (int) $item['id'] ?>"
                                <?= (($_POST['prodi_id'] ?? '') == $item['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($item['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Angkatan</label>
                    <input type="number"
                           name="angkatan"
                           class="form-control"
                           min="1901"
                           max="2155"
                           value="<?= htmlspecialchars($_POST['angkatan'] ?? '') ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select" required>
                        <option value="">-- Pilih Status --</option>
                        <option value="aktif"
                            <?= (($_POST['status'] ?? '') === 'aktif') ? 'selected' : '' ?>>
                            Aktif
                        </option>
                        <option value="cuti"
                            <?= (($_POST['status'] ?? '') === 'cuti') ? 'selected' : '' ?>>
                            Cuti
                        </option>
                        <option value="lulus"
                            <?= (($_POST['status'] ?? '') === 'lulus') ? 'selected' : '' ?>>
                            Lulus
                        </option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="/bkpm_web/acara10/si-akademik/public/mahasiswa"
                   class="btn btn-secondary">
                    Kembali
                </a>

            </form>

        </div>
    </div>
</div>
