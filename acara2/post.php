<!DOCTYPE html>
<html>
<head>
    <title>Login SI Akademik</title>
</head>
<body>

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
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "<p>Username diterima: " . $_POST['username'] . "</p>";
        echo "<p>Password telah dikirim menggunakan POST.</p>";
    }
    ?>

</body>
</html>
