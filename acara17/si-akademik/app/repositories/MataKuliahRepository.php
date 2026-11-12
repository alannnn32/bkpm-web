<?php

class MataKuliahRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Semua mata kuliah beserta nama prodi
    public function all(): array
    {
        $sql = "
            SELECT matakuliah.*, prodi.nama AS nama_prodi
            FROM matakuliah
            INNER JOIN prodi ON matakuliah.prodi_id = prodi.id
            ORDER BY matakuliah.id ASC
        ";

        return $this->db->query($sql)->fetchAll();
    }

    // Satu mata kuliah berdasarkan ID
    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM matakuliah WHERE id = :id");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    // Tambah data, mengembalikan ID baru
    public function create(array $data): string
    {
        $sql = "
            INSERT INTO matakuliah (kode, nama, sks, prodi_id)
            VALUES (:kode, :nama, :sks, :prodi_id)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'kode'     => $data['kode'],
            'nama'     => $data['nama'],
            'sks'      => $data['sks'],
            'prodi_id' => $data['prodi_id'],
        ]);

        return $this->db->lastInsertId();
    }

    // Ubah data
    public function update(int $id, array $data): bool
    {
        $sql = "
            UPDATE matakuliah
            SET kode = :kode, nama = :nama, sks = :sks, prodi_id = :prodi_id
            WHERE id = :id
        ";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            'kode'     => $data['kode'],
            'nama'     => $data['nama'],
            'sks'      => $data['sks'],
            'prodi_id' => $data['prodi_id'],
            'id'       => $id,
        ]);
    }

    // Hapus data
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM matakuliah WHERE id = :id");

        return $stmt->execute(['id' => $id]);
    }

    // Apakah kode sudah dipakai? $exceptId dipakai saat edit
    public function existsByKode(string $kode, ?int $exceptId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM matakuliah WHERE kode = :kode";
        $params = ['kode' => $kode];

        if ($exceptId !== null) {
            $sql .= " AND id <> :id";
            $params['id'] = $exceptId;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return (int) $stmt->fetchColumn() > 0;
    }
}