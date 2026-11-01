<?php

return [
    '/' => [
        'controller' => 'HomeController',
        'action' => 'index',
        'middleware' => []
    ],

    '/login' => [
        'controller' => 'AuthController',
        'action' => 'loginForm',
        'middleware' => []
    ],

    '/dashboard' => [
        'controller' => 'HomeController',
        'action' => 'dashboard',
        'middleware' => ['AuthMiddleware']
    ],

    '/mahasiswa' => [
        'controller' => 'MahasiswaController',
        'action' => 'index',
        'middleware' => ['AuthMiddleware']
    ],

    '/logout' => [
        'controller' => 'AuthController',
        'action' => 'logout',
        'middleware' => ['AuthMiddleware']
    ]
];