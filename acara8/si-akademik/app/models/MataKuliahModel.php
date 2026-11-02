
<?php

require_once __DIR__ . '/Model.php';

class MataKuliahModel extends Model
{
    protected string $table = 'matakuliah';

    protected array $fillable = [
        'kode',
        'nama',
        'sks',
        'prodi_id'
    ];
}
