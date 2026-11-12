<?php

class MahasiswaService
{
    private MahasiswaRepository $repo;
    private ProdiRepository $prodiRepo;

    // Dua dependency diinjeksikan lewat constructor
    public function __construct(
        MahasiswaRepository $repo,
        ProdiRepository $prodiRepo
    ) {
        $this->repo = $repo;
        $this->prodiRepo = $prodiRepo;
    }

        // Menambah mahasiswa baru
    public function create(array $input): array
    {
        [$mahasiswa, $errors] = $this->validate($input);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $id = $this->repo->create($mahasiswa->toArray());
        } catch (PDOException $e) {
            // Detail teknis hanya masuk ke log, tidak ke pengguna
            Logger::error('MahasiswaService::create - ' . $e->getMessage());

            return ['success' => false, 'errors' => ['Data gagal disimpan.']];
        }

        return ['success' => true, 'id' => $id];
    }

    // Mengubah data mahasiswa
    public function update(int $id, array $input): array
    {
        if (!$this->repo->find($id)) {
            return [
                'success' => false,
                'errors'  => ['Data mahasiswa tidak ditemukan.'],
            ];
        }

        [$mahasiswa, $errors] = $this->validate($input, $id);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $this->repo->update($id, $mahasiswa->toArray());
        } catch (PDOException $e) {
            Logger::error("MahasiswaService::update id={$id} - " . $e->getMessage());

            return ['success' => false, 'errors' => ['Data gagal diubah.']];
        }

        return ['success' => true, 'id' => $id];
    }

    // Validasi format (lewat setter) + aturan bisnis (lewat repository)
    private function validate(array $input, ?int $exceptId = null): array
    {
        $mahasiswa = new Mahasiswa();
        $errors = [];

        $isian = [
            'setNim'      => (string) ($input['nim'] ?? ''),
            'setNama'     => (string) ($input['nama'] ?? ''),
            'setEmail'    => (string) ($input['email'] ?? ''),
            'setProdiId'  => (int) ($input['prodi_id'] ?? 0),
            'setAngkatan' => (int) ($input['angkatan'] ?? 0),
            'setStatus'   => (string) ($input['status'] ?? ''),
        ];

        foreach ($isian as $setter => $nilai) {
            try {
                $mahasiswa->$setter($nilai);
            } catch (InvalidArgumentException $e) {
                $errors[] = $e->getMessage();
            }
        }

        // Aturan bisnis hanya dicek kalau formatnya sudah lolos.
        // Setter yang gagal membiarkan nilainya tetap kosong/0.
        if (
            $mahasiswa->getNim() !== ''
            && $this->repo->existsByNim($mahasiswa->getNim(), $exceptId)
        ) {
            $errors[] = 'NIM sudah terdaftar.';
        }

        if (
            $mahasiswa->getProdiId() > 0
            && !$this->prodiRepo->exists($mahasiswa->getProdiId())
        ) {
            $errors[] = 'Program studi tidak ditemukan.';
        }

        return [$mahasiswa, $errors];
    }
}