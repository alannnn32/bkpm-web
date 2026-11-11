
<?php

require_once __DIR__ . '/Model.php';

class ProdiModel extends Model
{
    protected string $table = 'prodi';

    protected array $fillable = [
        'kode',
        'nama'
    ];
}
