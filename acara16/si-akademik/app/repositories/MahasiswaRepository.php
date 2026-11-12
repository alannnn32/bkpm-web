<?php

class MahasiswaRepository
{
    private PDO $db;

    // Constructor injection: koneksi diberikan dari luar,
    // repository tidak membuat koneksi sendiri.
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Semua mahasiswa beserta nama prodi
    public function all(): array
    {
        $sql = "
            SELECT mahasiswa.*, prodi.nama AS nama_prodi
            FROM mahasiswa
            INNER JOIN prodi ON mahasiswa.prodi_id = prodi.id
            ORDER BY mahasiswa.id ASC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    // Cari berdasarkan NIM atau nama
    public function search(string $keyword): array
    {
        $sql = "
            SELECT mahasiswa.*, prodi.nama AS nama_prodi
            FROM mahasiswa
            INNER JOIN prodi ON mahasiswa.prodi_id = prodi.id
            WHERE mahasiswa.nim LIKE :keyword_nim
               OR mahasiswa.nama LIKE :keyword_nama
            ORDER BY mahasiswa.id ASC
        ";

        $stmt = $this->db->prepare($sql);
        $like = '%' . $keyword . '%';
        $stmt->execute([
            'keyword_nim'  => $like,
            'keyword_nama' => $like,
        ]);

        return $stmt->fetchAll();
    }

    // Satu mahasiswa berdasarkan ID
    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM mahasiswa WHERE id = :id");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

        // Apakah NIM sudah dipakai? $exceptId dipakai saat edit
    public function existsByNim(string $nim, ?int $exceptId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM mahasiswa WHERE nim = :nim";
        $params = ['nim' => $nim];

        if ($exceptId !== null) {
            $sql .= " AND id <> :id";
            $params['id'] = $exceptId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }

    // Tambah data, mengembalikan ID baru
    public function create(array $data): string
    {
        $sql = "
            INSERT INTO mahasiswa (nim, nama, email, prodi_id, angkatan, status)
            VALUES (:nim, :nama, :email, :prodi_id, :angkatan, :status)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'nim'      => $data['nim'],
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status'   => $data['status'],
        ]);

        return $this->db->lastInsertId();
    }

    // Ubah data
    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE mahasiswa
            SET nim = :nim, nama = :nama, email = :email,
                prodi_id = :prodi_id, angkatan = :angkatan, status = :status
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'nim'      => $data['nim'],
            'nama'     => $data['nama'],
            'email'    => $data['email'],
            'prodi_id' => $data['prodi_id'],
            'angkatan' => $data['angkatan'],
            'status'   => $data['status'],
            'id'       => $id,
        ]);
    }

    // Hapus data
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM mahasiswa WHERE id = :id");

        return $stmt->execute(['id' => $id]);
    }
}