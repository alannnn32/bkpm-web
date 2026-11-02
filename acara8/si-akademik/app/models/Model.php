
<?php

require_once __DIR__ . '/../Core/Database.php';

class Model
{
    protected PDO $db;

    // Diisi oleh masing-masing Model turunan
    protected string $table = '';
    protected array $fillable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    // Mengambil seluruh data
    public function all(): array
    {
        $sql = "SELECT * FROM `{$this->table}` ORDER BY id ASC";

        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    // Mengambil satu data berdasarkan ID
    public function find(int $id): array|false
    {
        $sql = "SELECT * FROM `{$this->table}` WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch();
    }

    // Menambahkan data
    public function create(array $data): string
    {
        $data = array_intersect_key(
            $data,
            array_flip($this->fillable)
        );

        if (empty($data)) {
            throw new InvalidArgumentException('Data tidak boleh kosong.');
        }

        $columns = array_keys($data);
        $fields = implode(', ', array_map(
            fn($column) => "`$column`",
            $columns
        ));

        $placeholders = implode(', ', array_map(
            fn($column) => ":$column",
            $columns
        ));

        $sql = "INSERT INTO `{$this->table}` ($fields)
                VALUES ($placeholders)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return $this->db->lastInsertId();
    }

    // Mengubah data
    public function update(int $id, array $data): bool
    {
        $data = array_intersect_key(
            $data,
            array_flip($this->fillable)
        );

        if (empty($data)) {
            throw new InvalidArgumentException('Data tidak boleh kosong.');
        }

        $set = [];

        foreach (array_keys($data) as $column) {
            $set[] = "`$column` = :$column";
        }

        $sql = "UPDATE `{$this->table}`
                SET " . implode(', ', $set) . "
                WHERE id = :id";

        $data['id'] = $id;

        $stmt = $this->db->prepare($sql);

        return $stmt->execute($data);
    }

    // Menghapus data
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM `{$this->table}` WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute(['id' => $id]);
    }
}
