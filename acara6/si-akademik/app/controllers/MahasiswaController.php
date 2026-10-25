<?php

class MahasiswaController
{
    public function index()
    {
        require __DIR__ . '/../Views/partials/header.php';
        ?>

        <div class="container mt-5">
            <div class="card shadow">
                <div class="card-body">
                    <h2>Daftar Mahasiswa</h2>

                    <p>
                        Halaman ini hanya dapat diakses setelah login.
                    </p>

                    <a
                        href="/bkpm_web/acara6/si-akademik/public/dashboard"
                        class="btn btn-secondary"
                    >
                        Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>

        <?php
        require __DIR__ . '/../Views/partials/footer.php';
    }
}