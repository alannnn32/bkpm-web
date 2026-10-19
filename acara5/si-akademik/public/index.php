<?php

$routes = require_once __DIR__ . '/../routes/web.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$basePath = '/si-akademik/public';

if (strpos($uri, $basePath) === 0) {
    $uri = substr($uri, strlen($basePath));
}

if ($uri === '') {
    $uri = '/';
}


if (preg_match('#^/mahasiswa/([0-9]+)$#', $uri, $matches)) {

    require_once __DIR__ . '/../app/controllers/MahasiswaController.php';

    $controller = new MahasiswaController();

    $id = $matches[1];

    $controller->show($id);

    exit;
}

if (isset($routes[$uri])) {

    $route = $routes[$uri];

    $controllerName = $route['controller'];
    $action = $route['action'];

    require_once __DIR__ . '/../app/controllers/' . $controllerName . '.php';

    $controller = new $controllerName();

    $controller->$action();

} else {

    http_response_code(404);

    echo "404 - Halaman Tidak Ditemukan";
}