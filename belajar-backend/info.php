<?php
$nama = "Ahmad Maulana Al Ayyubi";
$nim = "E41251100";
$waktu_server = date("d-m-Y H:i:s");
$versi_php = phpversion();
$sistem_operasi = PHP_OS;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Informasi Server</title>
</head>
<body>
    <h2>Informasi Server</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>Informasi</th>
            <th>Keterangan</th>
        </tr>
        <tr>
            <td>Nama</td>
            <td><?php echo $nama; ?></td>
        </tr>
        <tr>
            <td>NIM</td>
            <td><?php echo $nim; ?></td>
        </tr>
        <tr>
            <td>Waktu Server</td>
            <td><?php echo $waktu_server; ?></td>
        </tr>
        <tr>
            <td>Versi PHP</td>
            <td><?php echo $versi_php; ?></td>
        </tr>
        <tr>
            <td>Sistem Operasi Server</td>
            <td><?php echo $sistem_operasi; ?></td>
        </tr>
    </table>
</body>
</html>
