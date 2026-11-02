
<?php

require_once __DIR__ . '/Model.php';

class MahasiswaModel extends Model
{
    protected string $table = 'mahasiswa';

    protected array $fillable = [
        'nim',
        'nama',
        'email',
        'prodi_id',
        'angkatan',
        'status'
    ];

    // Menampilkan mahasiswa beserta nama program studi
    public function all(): array
    {
        $sql = "
            SELECT
                mahasiswa.*,
                prodi.nama AS nama_prodi
            FROM mahasiswa
            INNER JOIN prodi
                ON mahasiswa.prodi_id = prodi.id
            ORDER BY mahasiswa.id ASC
        ";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }
}
