
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

    '/mahasiswa/create' => [
        'controller' => 'MahasiswaController',
        'action' => 'create',
        'middleware' => ['AuthMiddleware']
    ],

    '/mahasiswa/store' => [
        'controller' => 'MahasiswaController',
        'action' => 'store',
        'middleware' => ['AuthMiddleware']
    ],

    '/mahasiswa/edit' => [
        'controller' => 'MahasiswaController',
        'action' => 'edit',
        'middleware' => ['AuthMiddleware']
    ],

    '/mahasiswa/update' => [
        'controller' => 'MahasiswaController',
        'action' => 'update',
        'middleware' => ['AuthMiddleware']
    ],

    '/mahasiswa/delete' => [
        'controller' => 'MahasiswaController',
        'action' => 'destroy',
        'middleware' => ['AuthMiddleware']
    ],

    '/logout' => [
        'controller' => 'AuthController',
        'action' => 'logout',
        'middleware' => ['AuthMiddleware']
    ]
];
