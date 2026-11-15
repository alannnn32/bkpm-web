<?php

class BaseController
{
    // Menampilkan halaman: header + isi view + footer
    protected function view(string $view, array $data = []): void
    {
        // Flash message diambil sekali, lalu otomatis dihapus dari session
        $flash = $this->pullFlash();

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

    // Menyimpan pesan sementara di session (tampil di request berikutnya)
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = [
            'type'    => $type,     // 'success' atau 'danger'
            'message' => $message,
        ];
    }

    // Mengambil pesan lalu menghapusnya, jadi hanya tampil satu kali
    private function pullFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);

        return $flash;
    }
}