<?php

require_once __DIR__ . '/../app/core/Logger.php';

Logger::info('Uji logger: aplikasi berjalan');
Logger::error('Uji logger: contoh error');

echo '<pre>';
echo "Isi app.log:\n\n";
echo htmlspecialchars(file_get_contents(__DIR__ . '/../storage/logs/app.log'));
echo '</pre>';