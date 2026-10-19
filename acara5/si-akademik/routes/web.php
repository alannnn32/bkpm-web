<?php

return [
    '/' => [
        'controller' => 'HomeController',
        'action' => 'index'
    ],

    '/mahasiswa' => [
        'controller' => 'MahasiswaController',
        'action' => 'index'
    ],

    '/mahasiswa/create' => [
        'controller' => 'MahasiswaController',
        'action' => 'create'
    ]
];