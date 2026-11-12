
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header">
            <h4>Tambah Mata Kuliah</h4>
        </div>

        <div class="card-body">
            <form action="/bkpm_web/acara17/si-akademik/public/matakuliah/store"
                  method="POST">

                <div class="mb-3">
                    <label class="form-label">Kode Mata Kuliah</label>
                    <input type="text" name="kode"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Mata Kuliah</label>
                    <input type="text" name="nama"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah SKS</label>
                    <input type="number" name="sks"
                           class="form-control" min="1" max="255" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Program Studi</label>
                    <select name="prodi_id" class="form-select" required>
                        <option value="">-- Pilih Program Studi --</option>

                        <?php foreach ($prodi as $p): ?>
                            <option value="<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['nama']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="/bkpm_web/acara17/si-akademik/public/matakuliah"
                   class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</div>
