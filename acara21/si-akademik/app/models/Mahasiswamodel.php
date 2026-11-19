
<?php


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

    
    // Mencari mahasiswa berdasarkan NIM atau nama
    public function search(string $keyword): array
    {
        $sql = "
            SELECT
                mahasiswa.*,
                prodi.nama AS nama_prodi
            FROM mahasiswa
            INNER JOIN prodi
                ON mahasiswa.prodi_id = prodi.id
            WHERE mahasiswa.nim LIKE :keyword_nim
                OR mahasiswa.nama LIKE :keyword_nama
            ORDER BY mahasiswa.id ASC
        ";

        $stmt = $this->db->prepare($sql);

        $search = '%' . $keyword . '%';

        $stmt->execute([
            'keyword_nim' => $search,
            'keyword_nama' => $search
        ]);

        return $stmt->fetchAll();
    }

}
