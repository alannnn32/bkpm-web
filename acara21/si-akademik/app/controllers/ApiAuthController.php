<?php

class ApiAuthController extends BaseApiController
{
    private AuthService $service;

    public function __construct(AuthService $service)
    {
        $this->service = $service;
    }

    // POST /api/register  (body: JSON berisi username, email, password)
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->methodTidakDiizinkan();
        }

        $hasil = $this->service->register($this->bacaJson());

        if (!$hasil['success']) {
            Response::json(
                422,
                false,
                'Registrasi gagal.',
                null,
                $hasil['errors']
            );
        }

        // Yang dikembalikan hanya id; password dan hash tidak pernah ikut
        Response::json(201, true, 'Registrasi berhasil', ['id' => $hasil['id']]);
    }

    // POST /api/login  (body: JSON berisi username, password)
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->methodTidakDiizinkan();
        }

        $hasil = $this->service->login($this->bacaJson());

        if (!$hasil['success']) {
            if ($hasil['jenis'] === 'validasi') {
                Response::json(422, false, 'Login gagal.', null, $hasil['errors']);
            }

            Response::json(401, false, 'Username atau password salah.');
        }

        $user = $hasil['user'];

        // Payload token hanya berisi id dan role
        $token = Auth::generateToken([
            'id'   => $user['id'],
            'role' => $user['role'],
        ]);

        Response::json(200, true, 'Login berhasil', [
            'token'      => $token,
            'token_type' => 'Bearer',
            'expires_in' => JWT_TTL,
            'user'       => $user,
        ]);
    }
}