<?php

// Dipanggil otomatis oleh PHP setiap kali ada class yang belum dimuat
spl_autoload_register(function (string $class) {
    $folders = [
        'core',
        'core/Middleware',
        'models',
        'repositories',
        'services',
        'controllers',
    ];

    foreach ($folders as $folder) {
        $path = __DIR__ . "/../{$folder}/{$class}.php";

        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});