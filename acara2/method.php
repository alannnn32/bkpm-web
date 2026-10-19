<!DOCTYPE html>
<html>
<head>
    <title>Deteksi Method Request</title>
</head>
<body>

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