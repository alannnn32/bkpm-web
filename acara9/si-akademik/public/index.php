<?php

session_start();

$routes = require __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/bkpm_web/acara9/si-akademik/public';

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
    require_once __DIR__ . '/../app/repositories/MahasiswaRepository.php';

    $controller = new MahasiswaController(
        new MahasiswaRepository(Database::getInstance())
    );
} else {
    $controller = new $controllerName();
}

if ($uri === '/login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller->login();
} else {
    $controller->$action();
}