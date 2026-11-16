<?php

class AuthService
{
    private UserRepository $users;

    public function __construct(UserRepository $users)
    {
        $this->users = $users;
    }

    // Mendaftarkan pengguna baru; role selalu 'user'
    public function register(array $input): array
    {
        $username = trim((string) ($input['username'] ?? ''));
        $email = trim((string) ($input['email'] ?? ''));
        $password = (string) ($input['password'] ?? '');

        $errors = $this->validate($username, $email, $password);

        if (!empty($errors)) {
            return ['success' => false, 'errors' => $errors];
        }

        try {
            $id = $this->users->create([
                'username' => $username,
                'email'    => $email,
                // Yang disimpan hanya hash, tidak pernah password aslinya
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);
        } catch (PDOException $e) {
            // 23000 = pelanggaran UNIQUE (dua pendaftar bersamaan)
            if ($e->getCode() === '23000') {
                return [
                    'success' => false,
                    'errors'  => ['Username atau email sudah digunakan.'],
                ];
            }

            Logger::error('AuthService::register - ' . $e->getMessage());

            return ['success' => false, 'errors' => ['Registrasi gagal.']];
        }

        return ['success' => true, 'id' => (int) $id];
    }

    // Mengembalikan daftar pesan error (kosong kalau semuanya valid)
    private function validate(string $username, string $email, string $password): array
    {
        $errors = [];

        if ($username === '') {
            $errors[] = 'Username wajib diisi.';
        } elseif (!preg_match('/^[A-Za-z0-9_]{3,50}$/', $username)) {
            $errors[] = 'Username hanya boleh huruf, angka, dan garis bawah (3-50 karakter).';
        } elseif ($this->users->existsByUsername($username)) {
            $errors[] = 'Username sudah digunakan.';
        }

        if ($email === '') {
            $errors[] = 'Email wajib diisi.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Format email tidak valid.';
        } elseif (mb_strlen($email) > 100) {
            $errors[] = 'Email maksimal 100 karakter.';
        } elseif ($this->users->existsByEmail($email)) {
            $errors[] = 'Email sudah terdaftar.';
        }

        if ($password === '') {
            $errors[] = 'Password wajib diisi.';
        } elseif (strlen($password) < 8) {
            $errors[] = 'Password minimal 8 karakter.';
        } elseif (strlen($password) > 72) {
            $errors[] = 'Password maksimal 72 karakter.';
        }

        return $errors;
    }
}