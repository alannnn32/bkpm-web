<?php

class MahasiswaController
{
    public function index()
    {
        echo "Halaman Daftar Mahasiswa";
    }

    public function create()
    {
        echo "Halaman Tambah Mahasiswa";
    }

    public function show($id)
    {
        echo "Menampilkan data mahasiswa dengan ID: " . $id;
    }
}