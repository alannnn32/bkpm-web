# Dokumentasi API SI Akademik

REST API untuk data mahasiswa dan mata kuliah, lengkap dengan registrasi dan login berbasis token (JWT).

- **Base URL:** `http://localhost/bkpm_web/acara21/si-akademik/public/api`
- **Format data:** JSON (`Content-Type: application/json`)
- **Autentikasi:** Bearer Token (JWT) untuk endpoint yang menulis data

Semua alamat di bawah ditulis relatif terhadap Base URL.

---

## 1. Ringkasan seluruh endpoint

| Method | Endpoint | Controller | Autentikasi | Keterangan |
|---|---|---|---|---|
| POST | `/register` | `ApiAuthController::register()` | Publik | Mendaftarkan pengguna baru |
| POST | `/login` | `ApiAuthController::login()` | Publik | Login, menghasilkan token |
| GET | `/mahasiswa` | `ApiMahasiswaController::index()` | Publik | Daftar semua mahasiswa |
| POST | `/mahasiswa` | `ApiMahasiswaController::index()` | **Token** | Menambah mahasiswa |
| GET | `/mahasiswa/{id}` | `ApiMahasiswaController::item()` | Publik | Satu mahasiswa berdasarkan id |
| PUT | `/mahasiswa/{id}` | `ApiMahasiswaController::item()` | **Token** | Mengubah mahasiswa |
| DELETE | `/mahasiswa/{id}` | `ApiMahasiswaController::item()` | **Token** | Menghapus mahasiswa |
| GET | `/matakuliah` | `ApiMataKuliahController::index()` | Publik | Daftar semua mata kuliah |
| POST | `/matakuliah` | `ApiMataKuliahController::index()` | **Token** | Menambah mata kuliah |
| GET | `/matakuliah/{id}` | `ApiMataKuliahController::item()` | Publik | Satu mata kuliah berdasarkan id |
| PUT | `/matakuliah/{id}` | `ApiMataKuliahController::item()` | **Token** | Mengubah mata kuliah |
| DELETE | `/matakuliah/{id}` | `ApiMataKuliahController::item()` | **Token** | Menghapus mata kuliah |

Catatan: `GET /mahasiswa?id=1` juga diterima dan sama dengan `GET /mahasiswa/1`.

### Peta router

| Pola URL | Controller | Action | Dipakai oleh |
|---|---|---|---|
| `/api/register` | `ApiAuthController` | `register` | POST |
| `/api/login` | `ApiAuthController` | `login` | POST |
| `/api/mahasiswa` | `ApiMahasiswaController` | `index` | GET, POST |
| `/api/mahasiswa/{id}` | `ApiMahasiswaController` | `item` | GET, PUT, DELETE |
| `/api/matakuliah` | `ApiMataKuliahController` | `index` | GET, POST |
| `/api/matakuliah/{id}` | `ApiMataKuliahController` | `item` | GET, PUT, DELETE |

Method HTTP menentukan aksi di dalam action yang sama. Method di luar daftar dibalas 405.

---

## 2. Ketentuan umum

### Format response

Setiap response memakai format yang sama:

```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": {},
    "timestamp": "2026-11-18T10:15:30+07:00"
}
```

| Field | Keterangan |
|---|---|
| `success` | `true` kalau berhasil, `false` kalau gagal |
| `message` | Pesan singkat yang bisa dibaca manusia |
| `data` | Isi data, atau `null` kalau tidak ada |
| `errors` | Daftar pesan kesalahan validasi. Hanya muncul pada response 422 |
| `timestamp` | Waktu response (ISO 8601, zona WIB) |

### Autentikasi

Endpoint bertanda **Token** wajib menyertakan header:

```
Authorization: Bearer <token>
```

Token didapat dari `POST /login`. Masa berlaku token diatur oleh `JWT_TTL` di `config/app.php` (bawaan 3600 detik, yaitu 1 jam). Endpoint GET bersifat publik.

### Kode status

| Kode | Arti | Contoh penyebab |
|---|---|---|
| 200 | OK | Data berhasil diambil, diubah, atau dihapus |
| 201 | Created | Data atau pengguna berhasil dibuat |
| 400 | Bad Request | Body bukan JSON yang valid, atau id bukan angka positif |
| 401 | Unauthorized | Token tidak ada, rusak, atau kedaluwarsa; atau login salah |
| 404 | Not Found | Data dengan id tersebut tidak ada, atau endpoint tidak ada |
| 405 | Method Not Allowed | Method HTTP tidak didukung endpoint itu |
| 422 | Unprocessable Entity | Body terbaca, tetapi data tidak lolos validasi |
| 500 | Internal Server Error | Kesalahan tak terduga di server (detail dicatat di log, tidak ditampilkan) |

---

## 3. Autentikasi

### POST /register

Controller: `ApiAuthController::register()` | Akses: Publik

**Headers:** `Content-Type: application/json`

**Request Body:**

```json
{
    "username": "andi_01",
    "email": "andi@gmail.com",
    "password": "rahasia123"
}
```

| Field | Tipe | Aturan |
|---|---|---|
| `username` | string | Wajib. Huruf, angka, garis bawah; 3-50 karakter; belum dipakai |
| `email` | string | Wajib. Format email valid; maksimal 100 karakter; belum dipakai |
| `password` | string | Wajib. 8-72 karakter. Disimpan sebagai hash, bukan teks asli |

Field lain (termasuk `role`) diabaikan. Pengguna baru selalu berperan `user`.

**Response 201 Created:**

```json
{
    "success": true,
    "message": "Registrasi berhasil",
    "data": { "id": 4 },
    "timestamp": "2026-11-16T10:21:08+07:00"
}
```

**Response 422 Unprocessable Entity** (contoh body `{}`):

```json
{
    "success": false,
    "message": "Registrasi gagal.",
    "data": null,
    "errors": [
        "Username wajib diisi.",
        "Email wajib diisi.",
        "Password wajib diisi."
    ],
    "timestamp": "2026-11-16T09:26:10+07:00"
}
```

Pesan validasi lain: `Username sudah digunakan.`, `Email sudah terdaftar.`, `Format email tidak valid.`, `Password minimal 8 karakter.`, `Password maksimal 72 karakter.`

**Response lain:** 400 (`Body request harus berupa JSON yang valid.`), 405 (`Method tidak diizinkan.`).

---

### POST /login

Controller: `ApiAuthController::login()` | Akses: Publik

**Headers:** `Content-Type: application/json`

**Request Body:**

```json
{
    "username": "andi_01",
    "password": "rahasia123"
}
```

**Response 200 OK:**

```json
{
    "success": true,
    "message": "Login berhasil",
    "data": {
        "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...",
        "token_type": "Bearer",
        "expires_in": 3600,
        "user": {
            "id": 4,
            "username": "andi_01",
            "role": "user"
        }
    },
    "timestamp": "2026-11-16T10:21:59+07:00"
}
```

**Response 401 Unauthorized** (username tidak ada atau password salah, pesannya sengaja sama):

```json
{
    "success": false,
    "message": "Username atau password salah.",
    "data": null,
    "timestamp": "2026-11-16T10:22:38+07:00"
}
```

**Response 422** (body `{}`): `errors` berisi `Username wajib diisi.` dan `Password wajib diisi.`

**Response lain:** 400 (JSON tidak valid), 405 (method selain POST).

---

## 4. Mahasiswa

Data mahasiswa memiliki field berikut.

| Field | Tipe | Keterangan |
|---|---|---|
| `id` | integer | Dibuat otomatis |
| `nim` | string | Unik |
| `nama` | string | |
| `email` | string | |
| `prodi_id` | integer | Id program studi |
| `angkatan` | string | Tahun angkatan |
| `status` | string | `aktif`, `cuti`, atau `lulus` |
| `created_at`, `updated_at` | string | Dibuat otomatis |
| `nama_prodi` | string | Hanya muncul pada daftar (`GET /mahasiswa`) |

### GET /mahasiswa

Controller: `ApiMahasiswaController::index()` | Akses: Publik

**Response 200 OK:**

```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": [
        {
            "id": 1,
            "nim": "2401001",
            "nama": "Budi Santoso",
            "email": "budi@email.com",
            "prodi_id": 1,
            "angkatan": "2025",
            "created_at": "2026-11-01 09:50:59",
            "updated_at": "2026-11-05 23:09:23",
            "status": "aktif",
            "nama_prodi": "Teknik Informatika"
        }
    ],
    "timestamp": "2026-11-16T00:55:21+07:00"
}
```

### GET /mahasiswa/{id}

Controller: `ApiMahasiswaController::item()` | Akses: Publik

**Response 200 OK:** `data` berisi satu objek mahasiswa (tanpa `nama_prodi`).

**Response 404** (id tidak ada): `"message": "Data mahasiswa tidak ditemukan."`, `"data": null`

**Response 400** (id bukan angka positif, misalnya `abc`): `"message": "ID tidak valid."`

### POST /mahasiswa

Controller: `ApiMahasiswaController::index()` | Akses: **Token**

**Headers:**

```
Content-Type: application/json
Authorization: Bearer <token>
```

**Request Body:**

```json
{
    "nim": "E4123999",
    "nama": "Dewi",
    "email": "dewi@gmail.com",
    "prodi_id": 1,
    "angkatan": 2026,
    "status": "aktif"
}
```

| Field | Tipe | Aturan |
|---|---|---|
| `nim` | string | Wajib. Huruf dan angka, 5-20 karakter; belum dipakai |
| `nama` | string | Wajib. Maksimal 100 karakter |
| `email` | string | Wajib. Format email valid; maksimal 100 karakter |
| `prodi_id` | integer | Wajib. Program studi dengan id itu harus ada |
| `angkatan` | integer | Wajib. 1901 sampai 2155 |
| `status` | string | Wajib. `aktif`, `cuti`, atau `lulus` |

**Response 201 Created:**

```json
{
    "success": true,
    "message": "Data mahasiswa berhasil ditambahkan",
    "data": {
        "id": 20,
        "nim": "E4123999",
        "nama": "Dewi",
        "email": "dewi@gmail.com",
        "prodi_id": 1,
        "angkatan": "2026",
        "created_at": "2026-11-12 10:56:59",
        "updated_at": "2026-11-12 10:56:59",
        "status": "aktif"
    },
    "timestamp": "2026-11-12T10:56:59+07:00"
}
```

**Response 422** (contoh body `{"nama":"X"}`):

```json
{
    "success": false,
    "message": "Data tidak valid atau gagal disimpan.",
    "data": null,
    "errors": [
        "NIM wajib diisi.",
        "Format email tidak valid.",
        "Program studi wajib dipilih.",
        "Angkatan tidak valid.",
        "Status tidak valid."
    ],
    "timestamp": "2026-11-16T01:01:33+07:00"
}
```

Pesan lain: `NIM sudah terdaftar.`, `Program studi tidak ditemukan.`, `NIM hanya boleh huruf dan angka (5-20 karakter).`

**Response lain:** 401 (token bermasalah), 400 (JSON tidak valid).

### PUT /mahasiswa/{id}

Controller: `ApiMahasiswaController::item()` | Akses: **Token**

**Headers:** sama dengan `POST /mahasiswa`.

**Request Body:** sama dengan `POST /mahasiswa`, dan **semua field wajib dikirim**. NIM boleh sama dengan NIM milik data itu sendiri, tetapi tidak boleh sama dengan NIM mahasiswa lain.

**Response 200 OK:** `"message": "Data mahasiswa berhasil diubah"`, `data` berisi data terbaru.

**Response lain:** 404 (`Data mahasiswa tidak ditemukan.`), 422 (`Data tidak valid atau gagal diubah.` beserta `errors`), 400, 401.

### DELETE /mahasiswa/{id}

Controller: `ApiMahasiswaController::item()` | Akses: **Token**

**Headers:** `Authorization: Bearer <token>`

**Response 200 OK:**

```json
{
    "success": true,
    "message": "Data mahasiswa berhasil dihapus",
    "data": null,
    "timestamp": "2026-11-12T11:20:00+07:00"
}
```

**Response lain:** 404 (id tidak ada), 400 (id tidak valid), 401 (token bermasalah), 500 (`Data gagal dihapus.`).

---

## 5. Mata kuliah

Data mata kuliah memiliki field `id`, `kode`, `nama`, `sks`, `prodi_id`, `created_at`, `updated_at`, dan `nama_prodi` (hanya pada daftar).

### GET /matakuliah

Controller: `ApiMataKuliahController::index()` | Akses: Publik

**Response 200 OK:**

```json
{
    "success": true,
    "message": "Data berhasil diambil",
    "data": [
        {
            "id": 1,
            "kode": "TI101",
            "nama": "Pemrograman Dasar",
            "sks": 3,
            "prodi_id": 1,
            "created_at": "2026-11-01 09:50:59",
            "updated_at": "2026-11-01 09:50:59",
            "nama_prodi": "Teknik Informatika"
        }
    ],
    "timestamp": "2026-11-16T00:57:51+07:00"
}
```

### GET /matakuliah/{id}

Controller: `ApiMataKuliahController::item()` | Akses: Publik

**Response 200 OK:** `data` berisi satu objek mata kuliah. **404:** `Data mata kuliah tidak ditemukan.` **400:** `ID tidak valid.`

### POST /matakuliah

Controller: `ApiMataKuliahController::index()` | Akses: **Token**

**Headers:**

```
Content-Type: application/json
Authorization: Bearer <token>
```

**Request Body:**

```json
{
    "kode": "TEST101",
    "nama": "Mata Kuliah Uji",
    "sks": 3,
    "prodi_id": 1
}
```

| Field | Tipe | Aturan |
|---|---|---|
| `kode` | string | Wajib. Huruf dan angka, 3-10 karakter; belum dipakai |
| `nama` | string | Wajib. Maksimal 150 karakter |
| `sks` | integer | Wajib. Angka 1 sampai 6 |
| `prodi_id` | integer | Wajib. Program studi dengan id itu harus ada |

**Response 201 Created:** `"message": "Data mata kuliah berhasil ditambahkan"`, `data` berisi data baru.

**Response 422:** `errors` dapat berisi `Kode wajib diisi.`, `Kode sudah terdaftar.`, `Nama wajib diisi.`, `SKS harus berupa angka 1 sampai 6.`, `Program studi wajib dipilih.`, atau `Program studi tidak ditemukan.`

### PUT /matakuliah/{id}

Controller: `ApiMataKuliahController::item()` | Akses: **Token**

Body sama dengan `POST /matakuliah` (semua field wajib; kode boleh sama dengan kode milik data itu sendiri).

**Response 200 OK:** `"message": "Data mata kuliah berhasil diubah"`. **Lainnya:** 404, 422, 400, 401.

### DELETE /matakuliah/{id}

Controller: `ApiMataKuliahController::item()` | Akses: **Token**

**Response 200 OK:** `"message": "Data mata kuliah berhasil dihapus"`. **Lainnya:** 404, 400, 401, 500.

---

## 6. Response error umum

**401 Unauthorized** (header token tidak ada):

```json
{
    "success": false,
    "message": "Token tidak ditemukan.",
    "data": null,
    "timestamp": "2026-11-16T11:00:00+07:00"
}
```

**401 Unauthorized** (token rusak atau kedaluwarsa): `"message": "Token tidak valid atau kedaluwarsa."`

**404 Not Found** (alamat tidak dikenal, misalnya `/xyz`): `"message": "Endpoint tidak ditemukan."`

**405 Method Not Allowed:** `"message": "Method tidak diizinkan."`

**500 Internal Server Error:** `"message": "Terjadi kesalahan pada server."` (detail teknis hanya tercatat di `storage/logs/app.log`).
