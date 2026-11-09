
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Data Mahasiswa</h2>

    <a href="/bkpm_web/acara9/si-akademik/public/mahasiswa/create"
        class="btn btn-primary">
        + Tambah Mahasiswa
    </a>
</div>

<form action="/bkpm_web/acara9/si-akademik/public/mahasiswa"
        method="GET"
        class="mb-4">

    <div class="input-group">
        <input type="text"
                name="keyword"
                class="form-control"
                placeholder="Cari berdasarkan NIM atau nama..."
                value="<?= htmlspecialchars($keyword) ?>">

        <button type="submit" class="btn btn-primary">
            Cari
        </button>

        <a href="/bkpm_web/acara9/si-akademik/public/mahasiswa"
            class="btn btn-secondary">
            Reset
        </a>
    </div>
</form>


<div class="card shadow">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
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
                    <?php if (!empty($mahasiswa)): ?>
                        <?php foreach ($mahasiswa as $index => $mhs): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= htmlspecialchars($mhs['nim']) ?></td>
                                <td><?= htmlspecialchars($mhs['nama']) ?></td>
                                <td><?= htmlspecialchars($mhs['email']) ?></td>
                                <td><?= htmlspecialchars($mhs['nama_prodi']) ?></td>
                                <td><?= htmlspecialchars($mhs['angkatan']) ?></td>
                                <td><?= htmlspecialchars($mhs['status']) ?></td>
                                <td>
                                    <a href="/bkpm_web/acara9/si-akademik/public/mahasiswa/edit?id=<?= $mhs['id'] ?>"
                                        class="btn btn-warning btn-sm">
                                            Edit
                                    </a>

                                    <form action="/bkpm_web/acara9/si-akademik/public/mahasiswa/delete"
                                    method="POST"
                                    class="d-inline"
                                    onsubmit="return confirm('Apakah kamu yakin ingin menghapus mahasiswa ini?')">

                                    <input type="hidden" name="id"
                                        value="<?= $mhs['id'] ?>">

                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Hapus
                                        </button>
                                            </form>
                                    </td>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">
                                Belum ada data mahasiswa.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
