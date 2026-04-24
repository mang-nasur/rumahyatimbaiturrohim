# Panduan Penggunaan Sistem Data Anak Yatim

Dokumen ini menjelaskan cara menggunakan fitur-fitur utama dalam Sistem Data Anak Yatim.

## Daftar Isi

1. [Login dan Autentikasi](#login-dan-autentikasi)
2. [Role dan Hak Akses](#role-dan-hak-akses)
3. [Dashboard](#dashboard)
4. [Menambah Data Anak Yatim](#menambah-data-anak-yatim)
5. [Melihat Daftar Anak Yatim](#melihat-daftar-anak-yatim)
6. [Mencari dan Memfilter Data](#mencari-dan-memfilter-data)
7. [Melihat Detail Anak Yatim](#melihat-detail-anak-yatim)
8. [Mengubah Data Anak Yatim](#mengubah-data-anak-yatim)
9. [Menghapus Data Anak Yatim](#menghapus-data-anak-yatim)
10. [Manajemen Transaksi Keuangan](#manajemen-transaksi-keuangan)
11. [Dashboard Keuangan](#dashboard-keuangan)
12. [Membuat Laporan](#membuat-laporan)
13. [Export PDF dan Excel](#export-pdf-dan-excel)
14. [Manajemen User (Admin Only)](#manajemen-user-admin-only)
15. [Profile Management](#profile-management)

---

## Login dan Autentikasi

Semua fitur aplikasi memerlukan autentikasi. Anda harus login terlebih dahulu untuk mengakses aplikasi.

**Langkah-langkah Login:**

1. Buka aplikasi di browser: `http://localhost:8000`
2. Anda akan diarahkan ke halaman login
3. Masukkan email dan password Anda
4. (Opsional) Centang "Ingat Saya" untuk tetap login selama 30 hari
5. Klik tombol **"Login"**
6. Jika berhasil, Anda akan diarahkan ke dashboard

**Default User Credentials:**

Setelah instalasi, gunakan kredensial berikut untuk login:

- **Admin**
  - Email: `admin@baiturrohim.com`
  - Password: `password`

- **Bendahara**
  - Email: `bendahara@baiturrohim.com`
  - Password: `password`

- **Staff**
  - Email: `staff@baiturrohim.com`
  - Password: `password`

**Catatan Keamanan:**
- Segera ubah password default setelah login pertama kali
- Password minimal 8 karakter
- Setelah 5 kali percobaan login gagal, akun akan diblokir sementara selama 1 menit

**Logout:**
1. Klik menu **"Logout"** di navigasi atas
2. Anda akan diarahkan kembali ke halaman login

---

## Role dan Hak Akses

Sistem menggunakan Role-Based Access Control (RBAC) dengan 3 role:

### 1. Admin
**Hak Akses Penuh:**
- ✓ Semua fitur Data Anak Yatim (Create, Read, Update, Delete)
- ✓ Semua fitur Transaksi Keuangan (Create, Read, Update, Delete)
- ✓ Laporan Keuangan (Read, Export)
- ✓ Manajemen User (Create, Read, Update, Delete, Reset Password)
- ✓ Dashboard (Read)
- ✓ Profile Management

### 2. Bendahara
**Fokus Keuangan:**
- ✓ Semua fitur Transaksi Keuangan (Create, Read, Update, Delete)
- ✓ Laporan Keuangan (Read, Export)
- ✓ Dashboard Keuangan (Read)
- ✓ Data Anak Yatim (Read Only - hanya lihat, tidak bisa edit/hapus)
- ✓ Profile Management
- ✗ Manajemen User (Tidak ada akses)

### 3. Staff
**Fokus Data Anak Yatim:**
- ✓ Semua fitur Data Anak Yatim (Create, Read, Update, Delete)
- ✓ Laporan Keuangan (Read Only - hanya lihat)
- ✓ Dashboard (Read)
- ✓ Profile Management
- ✗ Transaksi Keuangan (Tidak ada akses CRUD, hanya bisa lihat laporan)
- ✗ Manajemen User (Tidak ada akses)

**Tabel Hak Akses:**

| Fitur | Admin | Bendahara | Staff |
|-------|-------|-----------|-------|
| Data Anak Yatim (CRUD) | ✓ | Read Only | ✓ |
| Transaksi Keuangan (CRUD) | ✓ | ✓ | ✗ |
| Laporan Keuangan | ✓ | ✓ | Read Only |
| Manajemen User | ✓ | ✗ | ✗ |
| Dashboard | ✓ | ✓ | ✓ |
| Profile Management | ✓ | ✓ | ✓ |

**Catatan:**
- Jika Anda mencoba mengakses fitur yang tidak diizinkan, akan muncul pesan error "Anda tidak memiliki akses ke halaman ini"
- Menu navigasi akan otomatis menyesuaikan dengan role Anda

---

## Dashboard

Dashboard adalah halaman utama yang menampilkan ringkasan statistik data anak yatim dan keuangan.

**Cara Mengakses:**
1. Setelah login, Anda akan langsung diarahkan ke dashboard
2. Atau klik menu **"Dashboard"** di navigasi

**Informasi yang Ditampilkan:**
- Total jumlah anak yatim
- Jumlah anak berdasarkan jenis kelamin (Laki-laki dan Perempuan)
- Jumlah jenjang pendidikan yang berbeda
- Statistik keuangan (untuk Admin dan Bendahara)
- Grafik distribusi kelompok usia
- Grafik distribusi pendidikan
- Tabel anak yang baru masuk (5 terakhir)

**Navigasi Cepat:**
- Tombol "Tambah Data" untuk menambah anak yatim baru (Admin dan Staff)
- Tombol "Lihat Daftar" untuk melihat semua data
- Tombol "Buat Laporan" untuk membuat laporan

**Catatan:**
- Konten dashboard akan menyesuaikan dengan role Anda
- Admin dan Bendahara akan melihat informasi keuangan
- Staff hanya melihat informasi data anak yatim

---

## Menambah Data Anak Yatim

**Hak Akses:** Admin dan Staff

**Langkah-langkah:**

1. Dari Dashboard, klik tombol **"Tambah Data"** atau navigasi ke menu **"Anak Yatim"** > **"Tambah Data"**

2. Isi form biodata lengkap:
   - **Nama Lengkap** (wajib)
   - **Tempat Lahir** (wajib)
   - **Tanggal Lahir** (wajib, tidak boleh tanggal masa depan)
   - **Jenis Kelamin** (wajib, pilih Laki-laki atau Perempuan)
   - **Alamat** (opsional)
   - **Nama Ayah** (opsional)
   - **Status Ayah** (opsional)
   - **Nama Ibu** (opsional)
   - **Status Ibu** (opsional)
   - **Nomor Telepon Wali** (opsional, hanya angka, +, dan -)
   - **Tanggal Masuk** (wajib)
   - **Pendidikan Terakhir** (opsional)
   - **Sekolah Saat Ini** (opsional)
   - **Foto** (opsional, format: jpg/jpeg/png, maksimal 2MB)

3. Klik tombol **"Simpan"**

4. Jika berhasil, akan muncul pesan sukses dan data akan tersimpan di database

**Catatan:**
- Field yang bertanda bintang (*) wajib diisi
- Foto akan disimpan di folder `storage/app/public/photos`
- Jika ada error validasi, pesan error akan ditampilkan di bawah field yang bermasalah

---

## Melihat Daftar Anak Yatim

**Langkah-langkah:**

1. Dari Dashboard, klik tombol **"Lihat Daftar"** atau navigasi ke menu **"Anak Yatim"**

2. Tabel akan menampilkan data dengan kolom:
   - Nama Lengkap
   - Usia (dihitung otomatis dari tanggal lahir)
   - Jenis Kelamin
   - Pendidikan Terakhir
   - Tanggal Masuk
   - Aksi (Lihat Detail, Edit, Hapus)

3. Data ditampilkan dengan pagination (10 data per halaman)

4. Gunakan navigasi pagination di bawah tabel untuk berpindah halaman

---

## Mencari dan Memfilter Data

**Fitur Pencarian:**

1. Di halaman daftar anak yatim, gunakan kolom pencarian di atas tabel
2. Ketik kata kunci (nama anak, nama ayah, atau nama ibu)
3. Klik tombol **"Cari"** atau tekan Enter
4. Hasil pencarian akan ditampilkan di tabel

**Fitur Filter:**

1. **Filter Rentang Usia:**
   - Pilih usia minimum dan maksimum
   - Klik tombol **"Filter"**

2. **Filter Jenjang Pendidikan:**
   - Pilih jenjang pendidikan dari dropdown (TK, SD, SMP, SMA, SMK, Kuliah, Belum Sekolah)
   - Klik tombol **"Filter"**

3. **Filter Tahun Masuk:**
   - Pilih tahun masuk dari dropdown
   - Klik tombol **"Filter"**

**Kombinasi Filter:**
- Anda dapat menggunakan pencarian dan multiple filter secara bersamaan
- Filter yang aktif akan ditampilkan sebagai badge di atas tabel

**Reset Filter:**
- Klik tombol **"Reset Filter"** untuk menghapus semua filter dan menampilkan semua data

---

## Melihat Detail Anak Yatim

**Langkah-langkah:**

1. Di halaman daftar, klik tombol **"Lihat Detail"** (ikon mata) pada baris data yang ingin dilihat

2. Halaman detail akan menampilkan:
   - Foto anak (jika ada)
   - Semua informasi biodata lengkap
   - Usia yang dihitung otomatis

3. Dari halaman detail, Anda dapat:
   - Klik **"Edit"** untuk mengubah data
   - Klik **"Hapus"** untuk menghapus data
   - Klik **"Kembali"** untuk kembali ke daftar

---

## Mengubah Data Anak Yatim

**Hak Akses:** Admin dan Staff

**Langkah-langkah:**

1. Di halaman daftar atau detail, klik tombol **"Edit"** (ikon pensil)

2. Form edit akan ditampilkan dengan data existing sudah terisi

3. Ubah data yang ingin diubah

4. Untuk mengubah foto:
   - Foto lama akan ditampilkan (jika ada)
   - Upload foto baru jika ingin mengganti
   - Foto lama akan otomatis dihapus jika foto baru diupload

5. Klik tombol **"Update"**

6. Jika berhasil, akan muncul pesan sukses dan data akan diperbarui

**Catatan:**
- Jika tidak mengupload foto baru, foto lama akan tetap dipertahankan
- Validasi sama seperti saat menambah data

---

## Menghapus Data Anak Yatim

**Hak Akses:** Admin dan Staff

**Langkah-langkah:**

1. Di halaman daftar atau detail, klik tombol **"Hapus"** (ikon tempat sampah)

2. Dialog konfirmasi akan muncul: "Apakah Anda yakin ingin menghapus data ini?"

3. Klik **"OK"** untuk menghapus atau **"Cancel"** untuk membatalkan

4. Jika dikonfirmasi, data akan dihapus dari database dan foto akan dihapus dari storage

5. Pesan sukses akan ditampilkan

**Peringatan:**
- Penghapusan bersifat permanen dan tidak dapat dibatalkan
- Pastikan data yang dihapus sudah benar

---

## Manajemen Transaksi Keuangan

**Hak Akses:** Admin dan Bendahara

Fitur ini memungkinkan pengelolaan transaksi pemasukan dan pengeluaran keuangan panti.

**Langkah-langkah:**

1. Klik menu **"Transaksi Keuangan"** di navigasi

2. Untuk menambah transaksi baru:
   - Klik tombol **"Tambah Transaksi"**
   - Pilih jenis transaksi (Pemasukan atau Pengeluaran)
   - Isi jumlah nominal
   - Isi tanggal transaksi
   - Isi keterangan/deskripsi
   - Klik **"Simpan"**

3. Untuk melihat detail transaksi:
   - Klik tombol **"Lihat Detail"** pada baris transaksi

4. Untuk mengubah transaksi:
   - Klik tombol **"Edit"** pada baris transaksi
   - Ubah data yang diperlukan
   - Klik **"Update"**

5. Untuk menghapus transaksi:
   - Klik tombol **"Hapus"** pada baris transaksi
   - Konfirmasi penghapusan

**Catatan:**
- Staff tidak memiliki akses ke fitur ini
- Transaksi akan mempengaruhi saldo dan statistik keuangan

---

## Dashboard Keuangan

**Hak Akses:** Admin dan Bendahara

Dashboard keuangan menampilkan ringkasan dan statistik keuangan panti.

**Cara Mengakses:**
1. Klik menu **"Dashboard Keuangan"** di navigasi

**Informasi yang Ditampilkan:**
- Total pemasukan
- Total pengeluaran
- Saldo saat ini
- Grafik transaksi bulanan
- Transaksi terbaru

**Fitur:**
- Filter berdasarkan periode (bulan/tahun)
- Export laporan keuangan ke PDF dan Excel

---

## Membuat Laporan

**Hak Akses:** Semua role (dengan batasan berbeda)

**Langkah-langkah:**

1. Dari Dashboard, klik tombol **"Buat Laporan"** atau navigasi ke menu **"Laporan"**

2. Pilih jenis laporan:
   - **Semua Anak Yatim**: Laporan semua data tanpa filter
   - **Berdasarkan Rentang Usia**: Laporan anak dalam rentang usia tertentu
   - **Berdasarkan Jenjang Pendidikan**: Laporan anak dengan pendidikan tertentu
   - **Berdasarkan Tahun Masuk**: Laporan anak yang masuk pada tahun tertentu

3. Isi parameter sesuai jenis laporan yang dipilih:
   - Untuk laporan usia: isi usia minimum dan maksimum
   - Untuk laporan pendidikan: pilih jenjang pendidikan
   - Untuk laporan tahun masuk: pilih tahun

4. Klik tombol **"Preview Laporan"** untuk melihat preview di browser

5. Dari halaman preview, Anda dapat:
   - Melihat data laporan dalam format tabel
   - Export ke PDF atau Excel
   - Kembali untuk mengubah parameter

---

## Export PDF dan Excel

**Export PDF:**

1. Dari halaman laporan atau preview, klik tombol **"Export PDF"**

2. File PDF akan otomatis didownload dengan nama: `laporan-anak-yatim-[tanggal-waktu].pdf`

3. PDF berisi:
   - Header dengan nama panti dan judul laporan
   - Tanggal cetak
   - Tabel data lengkap
   - Total jumlah anak

**Export Excel:**

1. Dari halaman laporan atau preview, klik tombol **"Export Excel"**

2. File Excel akan otomatis didownload dengan nama: `laporan-anak-yatim-[tanggal-waktu].xlsx`

3. Excel berisi:
   - Sheet dengan nama "Laporan Anak Yatim"
   - Header kolom yang jelas
   - Data lengkap dalam format tabel
   - Dapat dibuka dengan Microsoft Excel, Google Sheets, atau LibreOffice Calc

**Catatan:**
- File yang didownload akan tersimpan di folder Downloads browser Anda
- Format PDF cocok untuk dicetak atau dibagikan sebagai dokumen resmi
- Format Excel cocok untuk analisis data lebih lanjut atau import ke sistem lain
- Admin dan Bendahara dapat export semua jenis laporan
- Staff hanya dapat export laporan data anak yatim

---

## Manajemen User (Admin Only)

**Hak Akses:** Admin saja

Fitur ini memungkinkan admin untuk mengelola akun pengguna aplikasi.

**Cara Mengakses:**
1. Klik menu **"Manajemen User"** di navigasi (hanya terlihat untuk Admin)

### Melihat Daftar User

1. Halaman akan menampilkan tabel semua user dengan informasi:
   - Nama
   - Email
   - Role (Admin/Bendahara/Staff)
   - Aksi (Edit, Reset Password, Hapus)

### Menambah User Baru

1. Klik tombol **"Tambah User"**
2. Isi form:
   - **Nama Lengkap** (wajib)
   - **Email** (wajib, harus unik)
   - **Password** (wajib, minimal 8 karakter)
   - **Konfirmasi Password** (wajib, harus sama dengan password)
   - **Role** (wajib, pilih: Admin/Bendahara/Staff)
3. Klik **"Simpan"**
4. User baru akan menerima kredensial untuk login

### Mengubah Data User

1. Klik tombol **"Edit"** pada baris user
2. Ubah data yang diperlukan:
   - Nama
   - Email (harus tetap unik)
   - Role
3. Klik **"Update"**

**Catatan:** Password tidak dapat diubah melalui form edit. Gunakan fitur Reset Password.

### Reset Password User

1. Klik tombol **"Reset Password"** pada baris user
2. Masukkan password baru (minimal 8 karakter)
3. Konfirmasi password baru
4. Klik **"Reset Password"**
5. Informasikan password baru kepada user yang bersangkutan

**Catatan Keamanan:**
- Pastikan password yang dibuat cukup kuat
- Sarankan user untuk segera mengubah password setelah reset

### Menghapus User

1. Klik tombol **"Hapus"** pada baris user
2. Konfirmasi penghapusan
3. User akan dihapus dari sistem

**Peringatan:**
- Penghapusan bersifat permanen
- Pastikan user yang dihapus sudah tidak diperlukan
- Tidak dapat menghapus akun sendiri yang sedang login

---

## Profile Management

**Hak Akses:** Semua authenticated users

Setiap pengguna dapat mengelola profil dan password mereka sendiri.

### Melihat Profile

1. Klik menu **"Profile"** di navigasi
2. Informasi yang ditampilkan:
   - Nama Lengkap
   - Email
   - Role (tidak dapat diubah sendiri)

### Mengubah Profile

1. Dari halaman profile, klik tombol **"Edit Profile"**
2. Ubah data yang diperlukan:
   - Nama Lengkap
   - Email (harus unik)
3. Klik **"Update"**

**Catatan:**
- Role tidak dapat diubah sendiri, hanya Admin yang dapat mengubah role
- Email harus tetap unik dalam sistem

### Mengubah Password

1. Dari halaman profile, klik tombol **"Ubah Password"**
2. Isi form:
   - **Password Saat Ini** (wajib, untuk verifikasi)
   - **Password Baru** (wajib, minimal 8 karakter)
   - **Konfirmasi Password Baru** (wajib, harus sama dengan password baru)
3. Klik **"Ubah Password"**

**Catatan Keamanan:**
- Pastikan password baru berbeda dari password lama
- Gunakan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk password yang kuat
- Jangan bagikan password Anda kepada siapapun
- Ubah password secara berkala untuk keamanan

**Jika Lupa Password:**
- Hubungi Admin untuk melakukan reset password
- Admin dapat mereset password Anda melalui fitur Manajemen User

---

## Tips Penggunaan

1. **Keamanan Login**: 
   - Ubah password default segera setelah login pertama kali
   - Gunakan password yang kuat (minimal 8 karakter)
   - Jangan bagikan kredensial login Anda
   - Selalu logout setelah selesai menggunakan aplikasi

2. **Backup Data Reguler**: Lakukan backup database secara berkala untuk menghindari kehilangan data

3. **Validasi Input**: Pastikan data yang diinput akurat dan lengkap untuk memudahkan pencarian dan pelaporan

4. **Foto Berkualitas**: Upload foto dengan resolusi yang baik namun tidak terlalu besar (maksimal 2MB)

5. **Filter Efisien**: Gunakan kombinasi filter untuk menemukan data spesifik dengan cepat

6. **Laporan Berkala**: Buat laporan secara berkala untuk dokumentasi dan pelaporan ke pihak terkait

7. **Pemisahan Tugas**: 
   - Admin mengelola user dan memiliki akses penuh
   - Bendahara fokus pada transaksi keuangan
   - Staff fokus pada data anak yatim

8. **Audit Trail**: Semua aktivitas login dan logout tercatat dalam log sistem untuk keamanan

---

## Troubleshooting

**Masalah: Tidak bisa login**
- Pastikan email dan password benar (case-sensitive)
- Cek apakah akun sudah diblokir karena terlalu banyak percobaan login gagal (tunggu 1 menit)
- Hubungi Admin untuk reset password jika lupa

**Masalah: Halaman menampilkan "Anda tidak memiliki akses"**
- Periksa role Anda dan pastikan Anda memiliki hak akses ke fitur tersebut
- Lihat tabel hak akses di bagian [Role dan Hak Akses](#role-dan-hak-akses)
- Hubungi Admin jika Anda merasa seharusnya memiliki akses

**Masalah: Session expired / Sesi berakhir**
- Login kembali ke aplikasi
- Jika sering terjadi, centang "Ingat Saya" saat login

**Masalah: Foto tidak muncul**
- Pastikan symbolic link sudah dibuat: `php artisan storage:link`
- Periksa permission folder storage

**Masalah: Error saat upload foto**
- Pastikan ukuran foto tidak lebih dari 2MB
- Pastikan format foto adalah jpg, jpeg, atau png

**Masalah: Data tidak tersimpan**
- Periksa koneksi database di file .env
- Pastikan semua field wajib sudah diisi
- Periksa log error di `storage/logs/laravel.log`

**Masalah: Laporan PDF/Excel tidak tergenerate**
- Pastikan package DomPDF dan Laravel-Excel sudah terinstall
- Periksa log error untuk detail masalah

**Masalah: CSRF token mismatch**
- Refresh halaman dan coba lagi
- Clear browser cache
- Pastikan cookies diaktifkan di browser

---

## Kontak Support

Jika mengalami masalah atau membutuhkan bantuan, silakan hubungi:

**Rumah Yatim Baiturrohim**
- Email: support@baiturrohim.org
- Telepon: (021) 1234-5678

---

**Terakhir diupdate:** Januari 2024
