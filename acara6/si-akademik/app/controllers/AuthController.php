<?php

class AuthController
{
    public function loginForm()
    {
        require __DIR__ . '/../Views/auth/login.php';
    }

    public function login()
    {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        //akun dummy
        if ($username === 'admin' && $password === 'admin123') {
            session_regenerate_id(true);

            $_SESSION['logged_in'] = true;
            $_SESSION['user_id'] = 1;
            $_SESSION['user_name'] = 'Admin';

            $_SESSION['flash'] = [
                'type' => 'success',
                'message' => 'Selamat datang, Admin'
            ];

            header('Location: /bkpm_web/acara6/si-akademik/public/dashboard');
            exit;
        }

        $_SESSION['flash'] = [
            'type' => 'danger',
            'message' => 'Username atau password salah.'
        ];

        header('Location: /bkpm_web/acara6/si-akademik/public/login');
        exit;
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();

        session_start();

        $_SESSION['flash'] = [
            'type' => 'success',
            'message' => 'Anda telah logout'
        ];

        header('Location: /bkpm_web/acara6/si-akademik/public/login');
        exit;
    }
}