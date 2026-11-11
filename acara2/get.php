<!DOCTYPE html>
<html>
<head>
    <title>Pencarian Mahasiswa</title>
</head>
<body>

    <h2>Pencarian Mahasiswa</h2>

    <form method="GET" action="">
        <label>Masukkan NIM atau Nama:</label>
        <input type="text" name="kunci">
        <button type="submit">Cari</button>
    </form>

    <?php
    if (isset($_GET['kunci'])) {
        echo "<p>Kunci yang dicari: " . $_GET['kunci'] . "</p>";
    }
    ?>

</body>
</html>