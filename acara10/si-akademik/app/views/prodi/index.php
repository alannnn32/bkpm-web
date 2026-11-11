
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Data Program Studi</h2>

    <a href="/bkpm_web/acara10/si-akademik/public/prodi/create"
       class="btn btn-primary">
        + Tambah Prodi
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Kode Prodi</th>
                        <th>Nama Program Studi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($prodi)): ?>
                        <?php foreach ($prodi as $index => $p): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($p['kode']) ?></td>
                                <td><?= htmlspecialchars($p['nama']) ?></td>
                                <td>
                                    <a href="/bkpm_web/acara10/si-akademik/public/prodi/edit?id=<?= $p['id'] ?>"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="/bkpm_web/acara10/si-akademik/public/prodi/delete"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus prodi ini?')">

                                        <input type="hidden" name="id"
                                               value="<?= $p['id'] ?>">

                                        <button type="submit"
                                                class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center">
                                Belum ada data program studi.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
