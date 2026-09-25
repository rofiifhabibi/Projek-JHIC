# Panduan Setup Projek JHIC (Docker)

Panduan lengkap, urut, dan terstruktur untuk menjalankan proyek Backend (Laravel) dan Frontend (Vue + Vite) menggunakan Docker dan Docker Compose.

---

## Prasyarat
Pastikan Anda dan anggota tim sudah menginstal:
* [Docker Desktop](https://www.docker.com/products/docker-desktop/) (Windows / macOS) atau **Docker Engine + Docker Compose Plugin** (Linux).
* Pastikan Docker Desktop dalam keadaan aktif/berjalan sebelum mengetikkan perintah di bawah.

---

## Langkah-langkah Menjalankan Proyek (Urut)

### 1. Clone Repository
Buka terminal dan clone repository ini ke komputer lokal Anda:
```bash
git clone <URL_REPO_GITHUB_ANDA>
cd Projek-JHIC
```

---

### 2. Build Image Docker (Backend & Frontend)
Build image secara manual dengan nama tag (`-t`) yang sesuai dan terhubung dengan konfigurasi di `docker-compose.yml`.

#### a. Build Backend Image
Masuk ke folder `Backend` lalu jalankan perintah build:
```bash
cd Backend
docker build -t jhic-backend:latest .
```
> **Catatan:** Tunggu proses hingga selesai. Tahap ini akan menyiapkan environment PHP 8.3 Apache, ekstensi database, dan Composer.

#### b. Build Frontend Image
Pindah ke folder `Front-end` lalu jalankan perintah build:
```bash
cd ../Front-end
docker build -t jhic-frontend:latest .
```
> **Catatan:** Tahap ini akan memasang dependensi Node.js (Vite, Vue, dsb.).

---

### 3. Jalankan Semua Service Menggunakan Docker Compose
Kembali ke parent folder (root project):
```bash
cd ..
```

Jalankan seluruh service (Database MariaDB, Backend Laravel, dan Frontend Vite) secara bersamaan:
```bash
docker compose up -d
```
> Opsi `-d` (*detached mode*) menjalankan container di latar belakang. Jika ingin melihat log langsung di terminal, jalankan tanpa `-d`: `docker compose up`.

---

### 4. Alternatif 1 Perintah Langsung (Build + Run Otomatis)
Jika Anda atau rekan tim ingin langsung mem-build dan menjalankan tanpa perlu masuk ke masing-masing folder satu per satu:
```bash
# Pastikan berada di root folder Projek-JHIC
docker compose up --build -d
```
Perintah ini akan otomatis menjalankan build untuk `jhic-backend:latest` dan `jhic-frontend:latest` sesuai konfigurasi `docker-compose.yml`.

---

### 5. Akses Aplikasi
Setelah container aktif, buka browser Anda:

| Layanan | URL | Keterangan |
|---|---|---|
| **Frontend** | [http://localhost:5173](http://localhost:5173) | Vue + Vite dev server (live reload aktif) |
| **Backend** | [http://localhost:8000](http://localhost:8000) | Laravel API / Web server |
| **Database** | `localhost:3306` | MariaDB (`user`: `laravel_user`, `password`: `laravel_password`, `db`: `laravel`) |

---

## Cara Kerja Otomatisasi (Tidak Perlu Setup Manual)

Saat container backend dijalankan pertama kali, file `Backend/docker-entrypoint.sh` akan otomatis:
1. Menyalin `.env.example` ke `.env` dan menyesuaikan kredensial koneksi ke container database `db`.
2. Menjalankan `composer install` jika folder `vendor` belum tersedia.
3. Menjalankan `php artisan key:generate` untuk mengisi `APP_KEY`.
4. Menunggu hingga MariaDB siap menerima koneksi, lalu menjalankan `php artisan migrate --force`.
5. Mengatur hak akses folder `storage` dan `bootstrap/cache`.

---

## Perintah Bermanfaat Lainnya

### Melihat Status Container
```bash
docker compose ps
```

### Melihat Log Realtime
```bash
# Semua service:
docker compose logs -f

# Khusus backend:
docker compose logs -f backend

# Khusus frontend:
docker compose logs -f frontend
```

### Menjalankan Perintah Artisan / PHP
```bash
# Contoh menjalankan migrasi manual atau seeder:
docker compose exec backend php artisan migrate
docker compose exec backend php artisan db:seed
```

### Menghentikan Container
```bash
# Menghentikan tanpa menghapus data database:
docker compose down

# Menghentikan dan mereset total database (menghapus volume data):
docker compose down -v
```
