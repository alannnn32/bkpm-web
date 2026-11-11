
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Data Mata Kuliah</h2>

    <a href="/bkpm_web/acara10/si-akademik/public/matakuliah/create"
       class="btn btn-primary">
        + Tambah Mata Kuliah
    </a>
</div>

<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-primary">
                    <tr>
                        <th>No</th>
                        <th>Kode</th>
                        <th>Nama Mata Kuliah</th>
                        <th>SKS</th>
                        <th>Program Studi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    <?php if (!empty($matakuliah)): ?>
                        <?php foreach ($matakuliah as $index => $mk): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($mk['kode']) ?></td>
                                <td><?= htmlspecialchars($mk['nama']) ?></td>
                                <td><?= htmlspecialchars($mk['sks']) ?></td>
                                <td><?= htmlspecialchars($mk['nama_prodi']) ?></td>
                                <td>
                                    <a href="/bkpm_web/acara10/si-akademik/public/matakuliah/edit?id=<?= $mk['id'] ?>"
                                       class="btn btn-warning btn-sm">
                                        Edit
                                    </a>

                                    <form action="/bkpm_web/acara10/si-akademik/public/matakuliah/delete"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?')">

                                        <input type="hidden" name="id"
                                               value="<?= $mk['id'] ?>">

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
                            <td colspan="6" class="text-center">
                                Belum ada data mata kuliah.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
