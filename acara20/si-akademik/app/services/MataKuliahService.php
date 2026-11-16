<?php

class MataKuliahService
{
    private MataKuliahRepository $repo;
    private ProdiRepository $prodiRepo;

    public function __construct(
        MataKuliahRepository $repo,
        ProdiRepository $prodiRepo
    ) {
        $this->repo = $repo;
        $this->prodiRepo = $prodiRepo;
    }

    // Menambah mata kuliah baru
    public function create(array $input): array
    {
        [$data, $errors] = $this->validate($input);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $id = $this->repo->create($data);
        } catch (PDOException $e) {
            Logger::error('MataKuliahService::create - ' . $e->getMessage());

            return ['success' => false, 'errors' => ['Data gagal disimpan.']];
        }

        return ['success' => true, 'id' => $id];
    }

    // Mengubah mata kuliah
    public function update(int $id, array $input): array
    {
        if (!$this->repo->find($id)) {
            return [
                'success' => false,
                'errors'  => ['Data mata kuliah tidak ditemukan.'],
            ];
        }

        [$data, $errors] = $this->validate($input, $id);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $this->repo->update($id, $data);
        } catch (PDOException $e) {
            Logger::error("MataKuliahService::update id={$id} - " . $e->getMessage());

            return ['success' => false, 'errors' => ['Data gagal diubah.']];
        }

        return ['success' => true, 'id' => $id];
    }

    // Validasi format dan aturan bisnis; mengembalikan [data bersih, daftar error]
    private function validate(array $input, ?int $exceptId = null): array
    {
        $errors = [];

        $kode = trim((string) ($input['kode'] ?? ''));
        $nama = trim((string) ($input['nama'] ?? ''));
        $sks = filter_var($input['sks'] ?? null, FILTER_VALIDATE_INT);
        $prodiId = filter_var($input['prodi_id'] ?? null, FILTER_VALIDATE_INT);

        if ($kode === '') {
            $errors[] = 'Kode wajib diisi.';
        } elseif (!preg_match('/^[A-Za-z0-9]{3,10}$/', $kode)) {
            $errors[] = 'Kode hanya boleh huruf dan angka (3-10 karakter).';
        } elseif ($this->repo->existsByKode($kode, $exceptId)) {
            $errors[] = 'Kode sudah terdaftar.';
        }

        if ($nama === '') {
            $errors[] = 'Nama wajib diisi.';
        } elseif (mb_strlen($nama) > 150) {
            $errors[] = 'Nama maksimal 150 karakter.';
        }

        if ($sks === false || $sks < 1 || $sks > 6) {
            $errors[] = 'SKS harus berupa angka 1 sampai 6.';
        }

        if ($prodiId === false || $prodiId <= 0) {
            $errors[] = 'Program studi wajib dipilih.';
        } elseif (!$this->prodiRepo->exists($prodiId)) {
            $errors[] = 'Program studi tidak ditemukan.';
        }

        $data = [
            'kode'     => $kode,
            'nama'     => $nama,
            'sks'      => (int) $sks,
            'prodi_id' => (int) $prodiId,
        ];

        return [$data, $errors];
    }
}