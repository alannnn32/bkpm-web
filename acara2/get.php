<!DOCTYPE html>
<html>
<head>
    <title>Pencarian Mahasiswa</title>
</head>
<body>

    <h2>Pencarian Mahasiswa</h2>

    <form method="GET" action="">
        <label>Masukkan NIM atau Nama:</label>
        <input type="text" name="keyword">
        <button type="submit">Cari</button>
    </form>

    <?php
    if (isset($_GET['keyword'])) {
        echo "<p>Keyword yang dicari: " . $_GET['keyword'] . "</p>";
    }
    ?>

</body>
</html>