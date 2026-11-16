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
}