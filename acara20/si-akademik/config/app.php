<?php

// 'development' = JSON dibuat rapi; 'production' = JSON ringkas
define('APP_ENV', 'development');

// Kunci rahasia untuk menandatangani token JWT.
// Siapa pun yang tahu kunci ini bisa membuat token palsu, jadi jangan dibagikan.
define('JWT_SECRET', '7401d5640326241635d49272e9be2185b8256f9f51091aff41b26d7c958ef7d7');

// Masa berlaku token dalam detik (3600 = 1 jam, 300 = 5 menit)
define('JWT_TTL', 3600);