<?php

class BaseController
{
    // Menampilkan halaman: header + isi view + footer
    protected function view(string $view, array $data = []): void
    {
        // Mengubah ['title' => 'X'] menjadi variabel $title di dalam view
        extract($data);

        require __DIR__ . '/../views/partials/header.php';
        require __DIR__ . '/../views/' . $view . '.php';
        require __DIR__ . '/../views/partials/footer.php';
    }

    // Mengarahkan pengguna ke halaman lain
    protected function redirect(string $url): void
    {
        header("Location: {$url}");
        exit;
    }
}