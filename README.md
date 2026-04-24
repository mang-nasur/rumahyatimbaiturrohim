# Sistem Data Anak Yatim

Aplikasi web berbasis Laravel untuk mengelola data anak yatim di Rumah Yatim Baiturrohim.

## Requirements

- PHP 8.1 atau lebih tinggi
- Composer
- MySQL 8.0 atau lebih tinggi
- Web Server (Apache/Nginx) atau PHP Development Server

## Instalasi

1. Clone repository ini:
```bash
git clone <repository-url>
cd sistem-data-anak-yatim
```

2. Install dependencies dengan Composer:
```bash
composer install
```

3. Copy file `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

4. Generate application key:
```bash
php artisan key:generate
```

5. Buat database MySQL dengan nama `sistem_anak_yatim`:
```sql
CREATE DATABASE sistem_anak_yatim CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

6. Konfigurasi database di file `.env`:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sistem_anak_yatim
DB_USERNAME=root
DB_PASSWORD=your_password
```

7. Jalankan migration untuk membuat tabel database:
```bash
php artisan migrate
```

8. Jalankan seeder untuk membuat user default dan data dummy:
```bash
php artisan db:seed
```

**Default User Credentials:**
- **Admin**: admin@baiturrohim.com / password
- **Bendahara**: bendahara@baiturrohim.com / password
- **Staff**: staff@baiturrohim.com / password

9. Buat symbolic link untuk storage:
```bash
php artisan storage:link
```

## Menjalankan Aplikasi

Jalankan development server:
```bash
php artisan serve
```

Aplikasi akan berjalan di `http://localhost:8000`

**Login ke Aplikasi:**
1. Buka browser dan akses `http://localhost:8000`
2. Anda akan diarahkan ke halaman login
3. Gunakan salah satu kredensial default di atas untuk login
4. Setelah login, Anda akan diarahkan ke dashboard

## Testing

Jalankan test suite:
```bash
php artisan test
```

Jalankan test dengan coverage:
```bash
php artisan test --coverage
```

## Fitur Utama

- **Authentication & Authorization**: Sistem login dengan role-based access control (Admin, Bendahara, Staff)
- **CRUD Data Anak Yatim**: Tambah, lihat, edit, dan hapus data anak yatim
- **Manajemen Transaksi Keuangan**: Kelola transaksi pemasukan dan pengeluaran
- **Upload Foto**: Upload dan kelola foto anak yatim
- **Pencarian & Filter**: Cari berdasarkan nama, filter berdasarkan usia, pendidikan, dan tahun masuk
- **Dashboard**: Statistik dan ringkasan data anak yatim dan keuangan
- **Laporan**: Generate laporan dalam format PDF dan Excel
- **Manajemen User**: Admin dapat mengelola akun pengguna dan reset password
- **Profile Management**: Pengguna dapat mengubah profil dan password mereka
- **Validasi & Keamanan**: Validasi input lengkap, proteksi CSRF, rate limiting, dan password hashing

## Teknologi

- **Backend**: Laravel 10.x (PHP 8.1+)
- **Database**: MySQL 8.0+
- **Frontend**: Blade Templates + Bootstrap 5
- **PDF Generation**: DomPDF
- **Excel Export**: Maatwebsite/Laravel-Excel

## Struktur Folder

```
app/
├── Http/
│   ├── Controllers/     # Controllers untuk Auth, User, Profile, CRUD, Dashboard, Laporan
│   ├── Middleware/      # Custom middleware untuk role-based access control
│   └── Requests/        # Form Request untuk validasi
├── Models/              # Eloquent models (User, AnakYatim, Transaksi)
└── Services/            # Business logic services (Auth, User, Role)

resources/
└── views/
    ├── layouts/         # Layout templates
    ├── auth/            # Login views
    ├── users/           # User management views
    ├── profile/         # Profile management views
    ├── anak-yatim/      # Views untuk CRUD anak yatim
    ├── transaksi/       # Views untuk transaksi keuangan
    ├── keuangan/        # Dashboard keuangan views
    ├── dashboard/       # Dashboard views
    └── laporan/         # Laporan views

database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders

storage/
└── app/
    └── public/
        └── photos/      # Uploaded photos
```

## Lisensi

MIT License

## Kontak

Rumah Yatim Baiturrohim
