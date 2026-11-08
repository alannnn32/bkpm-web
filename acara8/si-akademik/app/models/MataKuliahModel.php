
<?php

require_once __DIR__ . '/Model.php';

class MataKuliahModel extends Model
{
    protected string $table = 'matakuliah';

    protected array $fillable = [
        'kode', 'nama', 'sks', 'prodi_id'
    ];

    public function all(): array
    {
        $sql = "
            SELECT
                matakuliah.*,
                prodi.nama AS nama_prodi
            FROM matakuliah
            INNER JOIN prodi
                ON matakuliah.prodi_id = prodi.id
            ORDER BY matakuliah.id ASC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }
}
