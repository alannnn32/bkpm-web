
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

        '/api/mahasiswa' => [
        'controller' => 'ApiMahasiswaController',
        'action' => 'index',
        'middleware' => []
    ],

        '/api/mahasiswa/{id}' => [
        'controller' => 'ApiMahasiswaController',
        'action' => 'item',
        'middleware' => []
    ],

    '/prodi' => [
    'controller' => 'ProdiController',
    'action' => 'index',
    'middleware' => ['AuthMiddleware']
],

'/prodi/create' => [
    'controller' => 'ProdiController',
    'action' => 'create',
    'middleware' => ['AuthMiddleware']
],

'/prodi/store' => [
    'controller' => 'ProdiController',
    'action' => 'store',
    'middleware' => ['AuthMiddleware']
],

'/prodi/edit' => [
    'controller' => 'ProdiController',
    'action' => 'edit',
    'middleware' => ['AuthMiddleware']
],

'/prodi/update' => [
    'controller' => 'ProdiController',
    'action' => 'update',
    'middleware' => ['AuthMiddleware']
],

'/prodi/delete' => [
    'controller' => 'ProdiController',
    'action' => 'destroy',
    'middleware' => ['AuthMiddleware']
],

'/matakuliah' => [
    'controller' => 'MataKuliahController',
    'action' => 'index',
    'middleware' => ['AuthMiddleware']
],

'/matakuliah/create' => [
    'controller' => 'MataKuliahController',
    'action' => 'create',
    'middleware' => ['AuthMiddleware']
],

'/matakuliah/store' => [
    'controller' => 'MataKuliahController',
    'action' => 'store',
    'middleware' => ['AuthMiddleware']
],

'/matakuliah/edit' => [
    'controller' => 'MataKuliahController',
    'action' => 'edit',
    'middleware' => ['AuthMiddleware']
],

'/matakuliah/update' => [
    'controller' => 'MataKuliahController',
    'action' => 'update',
    'middleware' => ['AuthMiddleware']
],

'/matakuliah/delete' => [
    'controller' => 'MataKuliahController',
    'action' => 'destroy',
    'middleware' => ['AuthMiddleware']
],

    '/logout' => [
        'controller' => 'AuthController',
        'action' => 'logout',
        'middleware' => ['AuthMiddleware']
    ]
];
