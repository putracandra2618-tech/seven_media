# Task Manager

Aplikasi web manajemen task (to-do list) — project pembelajaran Laravel dari nol.

Setiap user bisa mendaftar, login, lalu mengelola task dan kategori miliknya sendiri.

## Fitur

- **Authentication** — Register, login, logout (implementasi manual)
- **CRUD Task** — Tambah, lihat, edit, hapus task dengan validasi
- **Kategori** — Organisasi task per kategori dengan warna badge
- **Dashboard** — Statistik total, selesai, belum selesai
- **Search & Filter** — Cari task by judul, filter kategori & status
- **Responsif** — Bootstrap 5 via CDN, mobile-friendly

## Tech Stack

| Layer | Teknologi |
|---|---|
| Backend | Laravel 12, PHP 8.2+ |
| Database | MySQL 8 |
| Frontend | Blade Template + Bootstrap 5 (CDN) |
| Auth | Manual (Auth facade bawaan Laravel) |
| Version Control | Git + GitHub |

> **Catatan:** Project ini **tidak memerlukan npm, Node.js, Vite, atau Tailwind**.
> Bootstrap di-load via CDN. Tidak ada build step frontend.

## Prasyarat

| Software | Versi Minimum |
|---|---|
| PHP | 8.2+ dengan extension: pdo_mysql, mbstring, xml, curl, zip, bcmath |
| Composer | 2.x |
| MySQL | 8.x |
| Git | Terbaru |

Cek versi:

\`\`\`bash
php -v
composer -V
mysql --version
\`\`\`

## Instalasi & Menjalankan

### 1. Clone repository

\`\`\`bash
git clone https://github.com/USERNAME/task-manager.git
cd task-manager
\`\`\`

### 2. Install dependency PHP

\`\`\`bash
composer install
\`\`\`

### 3. Setup environment

\`\`\`bash
cp .env.example .env
php artisan key:generate
\`\`\`

### 4. Konfigurasi database MySQL

Edit file `.env`:

\`\`\`env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=task_manager
DB_USERNAME=root
DB_PASSWORD=
\`\`\`

Buat database di MySQL:

\`\`\`bash
mysql -u root -p
\`\`\`

\`\`\`sql
CREATE DATABASE task_manager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
\`\`\`

### 5. Migration & seed data

\`\`\`bash
php artisan migrate --seed
\`\`\`

### 6. Jalankan server

\`\`\`bash
php artisan serve
\`\`\`

Buka browser: **http://localhost:8000**

### 7. Akun demo (dari seeder)

| Email | Password |
|---|---|
| demo@taskmanager.test | password123 |

Atau register akun baru di `/register`.

## Struktur Database

\`\`\`
users
├── id, name, email, password, timestamps

categories
├── id, name, color, user_id (FK), timestamps

tasks
├── id, user_id (FK), category_id (FK, nullable)
├── title, description, is_done, timestamps
\`\`\`

## Alur Demo

1. Buka `/register` → buat akun baru
2. Setelah login → buat beberapa task di `/tasks`
3. Buat kategori di `/categories` (Work, Personal, Study)
4. Assign kategori ke task via form edit
5. Lihat dashboard di `/dashboard` — statistik & task terbaru
6. Coba search & filter di halaman tasks

## Matrix Akses (Guest vs Auth vs Admin)

| Halaman | Guest | Auth biasa | Auth + Admin |
|---|---|---|---|
| `/` | ✅ | ✅ | ✅ |
| `/register` | ✅ | ❌ | ❌ |
| `/login` | ✅ | ❌ | ❌ |
| `/dashboard` | ❌ | ✅ | ✅ |
| `/tasks` | ❌ | ✅ | ✅ |
| `/categories` | ❌ | ✅ (milik sendiri) | ✅ |
| `/tags` | ❌ | ✅ (milik sendiri) | ✅ |
| `/tasks/trashed` | ❌ | ✅ (milik sendiri) | ✅ (lihat semua) |
| `/admin/dashboard` | ❌ | ❌ | ✅ |
| `/admin/users` | ❌ | ❌ | ✅ |

## Troubleshooting

| Masalah | Solusi |
|---|---|
| `could not find driver` | Install/aktifkan PHP extension `pdo_mysql` |
| `Access denied for user` | Cek `DB_USERNAME` dan `DB_PASSWORD` di `.env` |
| `Unknown database task_manager` | Buat database: `CREATE DATABASE task_manager;` |
| `No application encryption key` | Jalankan: `php artisan key:generate` |
| Halaman tanpa style | Pastikan koneksi internet aktif (Bootstrap CDN) |
| Port 8000 sudah dipakai | `php artisan serve --port=8080` |

## Lisensi

Project pembelajaran — bebas digunakan untuk belajar.

## Author

[Nama Kamu] — [GitHub Profile URL]