<?php

class Mahasiswa
{
    // Semua atribut private: tidak bisa diakses langsung dari luar class
    private ?int $id = null;
    private string $nim = '';
    private string $nama = '';
    private string $email = '';
    private int $prodiId = 0;
    private int $angkatan = 0;
    private string $status = 'aktif';

    // ===== Getter =====
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNim(): string
    {
        return $this->nim;
    }

    public function getNama(): string
    {
        return $this->nama;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getProdiId(): int
    {
        return $this->prodiId;
    }

    public function getAngkatan(): int
    {
        return $this->angkatan;
    }

    public function getStatus(): string
    {
        return $this->status;
    }

        // ===== Setter dengan validasi =====
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function setNim(string $nim): void
    {
        $nim = trim($nim);

        if ($nim === '') {
            throw new InvalidArgumentException('NIM wajib diisi.');
        }

        if (!preg_match('/^[A-Za-z0-9]{5,20}$/', $nim)) {
            throw new InvalidArgumentException(
                'NIM hanya boleh huruf dan angka (5-20 karakter).'
            );
        }

        $this->nim = $nim;
    }

    public function setNama(string $nama): void
    {
        $nama = trim($nama);

        if ($nama === '') {
            throw new InvalidArgumentException('Nama wajib diisi.');
        }

        if (mb_strlen($nama) > 100) {
            throw new InvalidArgumentException('Nama maksimal 100 karakter.');
        }

        $this->nama = $nama;
    }

    public function setEmail(string $email): void
    {
        $email = trim($email);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Format email tidak valid.');
        }

        if (mb_strlen($email) > 100) {
            throw new InvalidArgumentException('Email maksimal 100 karakter.');
        }

        $this->email = $email;
    }

    public function setProdiId(int $prodiId): void
    {
        if ($prodiId <= 0) {
            throw new InvalidArgumentException('Program studi wajib dipilih.');
        }

        $this->prodiId = $prodiId;
    }

    public function setAngkatan(int $angkatan): void
    {
        if ($angkatan < 1901 || $angkatan > 2155) {
            throw new InvalidArgumentException('Angkatan tidak valid.');
        }

        $this->angkatan = $angkatan;
    }

    public function setStatus(string $status): void
    {
        if (!in_array($status, ['aktif', 'cuti', 'lulus'], true)) {
            throw new InvalidArgumentException('Status tidak valid.');
        }

        $this->status = $status;
    }

    // ===== Pembantu =====

    // Membuat objek Mahasiswa dari satu baris hasil query database
    public static function fromArray(array $row): self
    {
        $mahasiswa = new self();

        $mahasiswa->setId(isset($row['id']) ? (int) $row['id'] : null);
        $mahasiswa->setNim($row['nim'] ?? '');
        $mahasiswa->setNama($row['nama'] ?? '');
        $mahasiswa->setEmail($row['email'] ?? '');
        $mahasiswa->setProdiId((int) ($row['prodi_id'] ?? 0));
        $mahasiswa->setAngkatan((int) ($row['angkatan'] ?? 0));
        $mahasiswa->setStatus($row['status'] ?? 'aktif');

        return $mahasiswa;
    }

    // Mengubah objek menjadi array (format yang diterima repository)
    public function toArray(): array
    {
        return [
            'nim'      => $this->nim,
            'nama'     => $this->nama,
            'email'    => $this->email,
            'prodi_id' => $this->prodiId,
            'angkatan' => $this->angkatan,
            'status'   => $this->status,
        ];
    }
}