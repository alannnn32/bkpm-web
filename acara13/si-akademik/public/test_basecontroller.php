<?php

require_once __DIR__ . '/../app/core/BaseController.php';

// Class turunan kosong: otomatis mewarisi semua method BaseController
class ContohController extends BaseController
{
}

echo '<pre>';

$contoh = new ContohController();

echo "ContohController adalah turunan BaseController? ";
var_dump($contoh instanceof BaseController);

echo "\n";

$ref = new ReflectionClass('BaseController');

foreach (['view', 'redirect'] as $nama) {
    $m = $ref->getMethod($nama);
    $akses = $m->isProtected() ? 'protected' : ($m->isPublic() ? 'public' : 'private');
    echo "Method {$nama}() : {$akses}\n";
}

echo '</pre>';