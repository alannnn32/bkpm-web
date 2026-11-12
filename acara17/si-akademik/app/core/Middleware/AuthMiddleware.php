<?php

class AuthMiddleware
{
    public function handle()
    {
        if (
            empty($_SESSION['logged_in']) ||
            $_SESSION['logged_in'] !== true
        ) {
            header('Location: /bkpm_web/acara17/si-akademik/public/login');
            exit;
        }
    }
}