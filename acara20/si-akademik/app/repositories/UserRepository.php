<?php

class UserRepository
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    // Tambah pengguna; role selalu memakai nilai bawaan database ('user')
    public function create(array $data): string
    {
        $sql = "
            INSERT INTO users (username, email, password)
            VALUES (:username, :email, :password)
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            'username' => $data['username'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        return $this->db->lastInsertId();
    }

    public function findByUsername(string $username): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);

        return $stmt->fetch();
    }

    public function findByEmail(string $email): array|false
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);

        return $stmt->fetch();
    }

    public function existsByUsername(string $username): bool
    {
        return $this->findByUsername($username) !== false;
    }

    public function existsByEmail(string $email): bool
    {
        return $this->findByEmail($email) !== false;
    }
}