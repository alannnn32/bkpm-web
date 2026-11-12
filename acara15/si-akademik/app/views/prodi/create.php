
<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header">
            <h4>Tambah Program Studi</h4>
        </div>

        <div class="card-body">
            <form action="/bkpm_web/acara15/si-akademik/public/prodi/store"
                  method="POST">

                <div class="mb-3">
                    <label class="form-label">Kode Prodi</label>
                    <input type="text" name="kode"
                           class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Nama Program Studi</label>
                    <input type="text" name="nama"
                           class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">
                    Simpan
                </button>

                <a href="/bkpm_web/acara15/si-akademik/public/prodi"
                   class="btn btn-secondary">
                    Kembali
                </a>
            </form>
        </div>
    </div>
</div>
