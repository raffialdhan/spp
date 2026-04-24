# Panduan Pengaturan Database MySQL (Laragon)

Ikuti langkah-langkah berikut untuk memindahkan database dari SQLite (saat ini) ke MySQL agar data Anda bisa dikelola melalui **phpMyAdmin** atau **HeidiSQL** di Laragon.

### 1. Buat Database di Laragon
1. Pastikan Laragon sudah berjalan (**Start All**).
2. Klik tombol **Database** di Laragon (ini akan membuka HeidiSQL atau phpMyAdmin).
3. Klik kanan pada koneksi (biasanya `localhost`) -> **Create new** -> **Database**.
4. Beri nama: `spp_new` (atau nama lain pilihan Anda).
5. Klik **OK**.

### 2. Konfigurasi di Laravel (`.env`)
Buka file `.env` di folder proyek Anda, cari bagian database, dan ubah menjadi seperti ini:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=spp_new
DB_USERNAME=root
DB_PASSWORD=
```
> [!NOTE]
> Secara default, password database di Laragon adalah kosong (empty).

### 3. Jalankan Migrasi & Seeder
Buka terminal (bisa klik tombol **Terminal** di Laragon), lalu jalankan perintah berikut:

```bash
php artisan migrate:fresh --seed
```
> [!IMPORTANT]
> Perintah `migrate:fresh` akan menghapus tabel lama dan membuat yang baru. Data di SQLite lama Anda tidak akan ikut pindah, jadi Anda akan mulai dengan data sampel baru dari Seeder.

### 4. Selesai!
Sekarang aplikasi Anda sudah terhubung ke MySQL. Anda bisa mengecek tabel-tabelnya seperti `students`, `payments`, `users`, dll melalui HeidiSQL/phpMyAdmin.

---

### Tips: Cara Masuk ke Akun Default (Setelah Seeding)
Gunakan akun berikut untuk login pertama kali:
- **Admin**: `admin` / `admin123`
- **Petugas**: `petugas` / `petugas123`
- **Siswa**: `siswa` / `siswa123`
