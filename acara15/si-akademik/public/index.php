<?php

session_start();
require_once __DIR__ . '/../app/core/Logger.php';

// Semua exception yang tidak ditangkap berakhir di sini
set_exception_handler(function (Throwable $e) {
    // Detail teknis masuk ke log (tanpa stack trace)
    Logger::error(
        get_class($e) . ' - ' . $e->getMessage()
        . ' (' . basename($e->getFile()) . ':' . $e->getLine() . ')'
    );

    // Pengguna hanya melihat pesan umum
    http_response_code(500);
    echo '<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Terjadi Kesalahan</title>
</head>
<body style="font-family: sans-serif; text-align: center; margin-top: 80px;">
    <h1>Terjadi kesalahan</h1>
    <p>Maaf, aplikasi sedang bermasalah. Silakan coba lagi beberapa saat lagi.</p>
</body>
</html>';
});

$routes = require __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/bkpm_web/acara15/si-akademik/public';

if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

if ($uri === '') {
    $uri = '/';
}

if (!isset($routes[$uri])) {
    http_response_code(404);
    echo '404 - Halaman Tidak Ditemukan';
    exit;
}

$route = $routes[$uri];

foreach ($route['middleware'] as $middlewareName) {
    require_once __DIR__ . '/../app/Core/Middleware/'
        . $middlewareName . '.php';

    $middleware = new $middlewareName();
    $middleware->handle();
}

$controllerName = $route['controller'];
$action = $route['action'];

require_once __DIR__ . '/../app/Controllers/'
    . $controllerName . '.php';

// Router menyiapkan dependency lalu menyuntikkannya ke controller
if ($controllerName === 'MahasiswaController') {
    require_once __DIR__ . '/../app/core/Database.php';
    require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';
    require_once __DIR__ . '/../app/repositories/ProdiRepository.php';
    require_once __DIR__ . '/../app/services/MahasiswaService.php';

    // Router menyiapkan semua dependency, lalu menyuntikkannya
    $db = Database::getInstance();
    $mahasiswaRepo = new MahasiswaRepository($db);
    $prodiRepo = new ProdiRepository($db);

    $controller = new MahasiswaController(
        $mahasiswaRepo,
        $prodiRepo,
        new MahasiswaService($mahasiswaRepo, $prodiRepo)
    );
} elseif ($controllerName === 'ApiMahasiswaController') {
    require_once __DIR__ . '/../app/core/Database.php';
    require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';
    require_once __DIR__ . '/../app/repositories/ProdiRepository.php';
    require_once __DIR__ . '/../app/services/MahasiswaService.php';

    $db = Database::getInstance();
    $mahasiswaRepo = new MahasiswaRepository($db);
    $prodiRepo = new ProdiRepository($db);

    $controller = new ApiMahasiswaController(
        $mahasiswaRepo,
        new MahasiswaService($mahasiswaRepo, $prodiRepo)
    );
} else {
    $controller = new $controllerName();
}

if ($uri === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->login();
} else {
    $controller->$action();
}