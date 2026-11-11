<!DOCTYPE html>
<html>
<head>
    <title>SI Akademik</title>
</head>
<body>

    <h1>Selamat datang di SI Akademik</h1>

    <p>Ini adalah halaman utama Sistem Informasi Akademik.</p>

    <hr>

    <!-- post -->
    <h2>Login SI Akademik</h2>

    <form method="POST" action="">
        <label>Username:</label>
        <input type="text" name="username">
        <br><br>

        <label>Password:</label>
        <input type="password" name="password">
        <br><br>

        <button type="submit">Login</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['username'])) {
        echo "<p>Username diterima: " . htmlspecialchars($_POST['username']) . "</p>";
        echo "<p>Password telah dikirim menggunakan POST.</p>";
    }
    ?>

    <hr>

    <!-- get -->
    <h2>Pencarian Mahasiswa</h2>

    <form method="GET" action="">
        <label>Masukkan NIM atau Nama:</label>
        <input type="text" name="kunci">
        <button type="submit">Cari</button>
    </form>

    <?php
    if (isset($_GET['kunci'])) {
        echo "<p>Keyword yang dicari: " . htmlspecialchars($_GET['kunci']) . "</p>";
    }
    ?>

    <hr>

    <!-- ========== 3. Deteksi Method Request ========== -->
    <h2>Deteksi Method Request</h2>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        echo "<p>Request menggunakan method GET.</p>";
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "<p>Request menggunakan method POST.</p>";
    }
    ?>

    <form method="POST">
        <button type="submit">Kirim POST</button>
    </form>

</body>
</html>