<?php

class ProdiRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Semua program studi
    public function all(): array
    {
        return $this->db->query("SELECT * FROM prodi ORDER BY id ASC")->fetchAll();
    }

    // Satu program studi berdasarkan ID
    public function find(int $id): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM prodi WHERE id = :id");
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    // Apakah prodi dengan ID ini ada?
    public function exists(int $id): bool
    {
        return $this->find($id) !== false;
    }
}