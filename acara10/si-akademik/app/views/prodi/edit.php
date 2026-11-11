
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header">
            <h4>Edit Program Studi</h4>
        </div>

        <div class="card-body">
            <form action="/bkpm_web/acara10/si-akademik/public/prodi/update"
                  method="POST">

                <input type="hidden" name="id"
                       value="<?= htmlspecialchars($prodi['id']) ?>">

                <div class="mb-3">
                    <label class="form-label">Kode Prodi</label>
                    <input type="text" name="kode"
                           class="form-control"
                           value="<?= htmlspecialchars($prodi['kode']) ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Program Studi</label>
                    <input type="text" name="nama"
                           class="form-control"
                           value="<?= htmlspecialchars($prodi['nama']) ?>"
                           required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan Perubahan
                </button>

                <a href="/bkpm_web/acara10/si-akademik/public/prodi"
                   class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</div>
