<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Mahasiswa - SI Akademik</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Mahasiswa</h2>
        <a href="form.php" class="btn btn-primary">
            + Tambah Mahasiswa
        </a>
    </div>
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-primary">
                        <tr>
                            <th>No</th>
                            <th>NIM</th>
                            <th>Nama</th>
                            <th>Program Studi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td>E41251100</td>
                            <td>Napoleon Bonaparte</td>
                            <td>Teknik Informatika</td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td>E41251101</td>
                            <td>Davina Karamoy</td>
                            <td>Teknik Informatika</td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td>E41251102</td>
                            <td>Casey Kaspol</td>
                            <td>Sistem Informasi</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
