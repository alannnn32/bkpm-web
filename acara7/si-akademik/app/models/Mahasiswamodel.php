<?php

require_once __DIR__ . '/Model.php';

class MahasiswaModel extends Model
{
    public function all(): array
    {
        $sql = "
            SELECT
                mahasiswa.id,
                mahasiswa.nim,
                mahasiswa.nama,
                mahasiswa.email,
                mahasiswa.angkatan,
                mahasiswa.status,
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