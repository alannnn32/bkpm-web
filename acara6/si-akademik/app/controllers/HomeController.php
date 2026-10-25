<?php

class HomeController
{
    public function index()
    {
        header('Location: /bkpm_web/acara6/si-akademik/public/dashboard');
        exit;
    }

    public function dashboard()
    {
        $nama = $_SESSION['user_name'] ?? 'Admin';

        require __DIR__ . '/../Views/partials/header.php';
        ?>

        <div class="container mt-5">

            <?php if (!empty($_SESSION['flash'])): ?>
                <?php $flash = $_SESSION['flash']; ?>

                <div class="alert alert-<?= htmlspecialchars($flash['type']) ?>">
                    <?= htmlspecialchars($flash['message']) ?>
                </div>

                <?php unset($_SESSION['flash']); ?>
            <?php endif; ?>

            <div class="card shadow">
                <div class="card-body">

                    <h2>Dashboard SI Akademik</h2>

                    <p>
                        Selamat datang,
                        <?= htmlspecialchars($nama) ?>!
                    </p>

                    <a
                        href="/bkpm_web/acara6/si-akademik/public/mahasiswa"
                        class="btn btn-primary"
                    >
                        Daftar Mahasiswa
                    </a>

                    <form
                        action="/bkpm_web/acara6/si-akademik/public/logout"
                        method="POST"
                        class="d-inline"
                    >
                        <button type="submit" class="btn btn-danger">
                            Logout
                        </button>
                    </form>

                </div>
            </div>

        </div>

        <?php
        require __DIR__ . '/../Views/partials/footer.php';
    }
}