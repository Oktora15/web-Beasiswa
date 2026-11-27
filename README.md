# BeasiswaHub

Platform informasi, pendaftaran, dan manajemen beasiswa menggunakan Laravel 13, MySQL, Blade, Tailwind CSS, dan Livewire.

## Prasyarat

- PHP 8.3 atau lebih baru dengan ekstensi yang dibutuhkan Laravel.
- Composer.
- MySQL.
- Node.js dan npm.

## Instalasi lokal

1. Pasang dependency PHP:

   ```powershell
   composer install
   ```

2. Salin `.env.example` menjadi `.env`, lalu atur koneksi MySQL, `APP_URL`, dan `APP_TIMEZONE=Asia/Jakarta`. Buat database MySQL bernama `beasiswahub` atau sesuaikan `DB_DATABASE`.

   ```powershell
   Copy-Item .env.example .env
   ```

3. Buat application key dan jalankan migration serta seeder:

   ```powershell
   php artisan key:generate
   php artisan migrate --seed
   ```

4. Pasang dependency frontend dan build aset:

   ```powershell
   npm.cmd install
   npm.cmd run build
   ```

5. Untuk mengembangkan aplikasi, jalankan perintah berikut di terminal terpisah:

   ```powershell
   php artisan serve
   npm.cmd run dev
   php artisan queue:work
   php artisan schedule:work
   ```

`schedule:work` memeriksa jadwal setiap menit. Pengingat email dijadwalkan setiap hari pukul 09.00 menurut zona waktu aplikasi, untuk beasiswa aktif yang disimpan mahasiswa dan deadline-nya H-7 atau H-1.

## Konfigurasi email dan worker

`MAIL_MAILER=log` dapat digunakan untuk development; isi SMTP di `.env` (`MAIL_MAILER`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, dan `MAIL_FROM_ADDRESS`) untuk mengirim email sungguhan. Pengingat dikirim sebagai queued notification. Karena `QUEUE_CONNECTION=database`, worker `php artisan queue:work` harus tetap berjalan agar email diproses.

Untuk production, jalankan scheduler melalui cron setiap menit:

```cron
* * * * * cd /path/to/web-Beasiswa && php artisan schedule:run >> /dev/null 2>&1
```

Jalankan juga queue worker sebagai proses terkelola (misalnya dengan Supervisor). Periksa jadwal terdaftar menggunakan `php artisan schedule:list`.

## Akun demo

Seeder membuat 20 beasiswa, kriteria, dan tiga akun mahasiswa. Kredensial demo bawaan hanya digunakan pada lingkungan `local`/`testing`:

| Peran | Email | Kata sandi |
|---|---|---|
| Admin | `admin@beasiswahub.test` | `AdminDemo!2026` |
| Mahasiswa | `alya.maharani@beasiswahub.test` | `StudentDemo1!2026` |
| Mahasiswa | `bima.pratama@beasiswahub.test` | `StudentDemo2!2026` |
| Mahasiswa | `citra.rahman@beasiswahub.test` | `StudentDemo3!2026` |

Ganti kredensial demo sebelum memakai lingkungan bersama. Seeder tidak menggunakan kredensial bawaan di production: atur email dan kata sandi melalui `SEED_ADMIN_EMAIL`, `SEED_ADMIN_PASSWORD`, `SEED_STUDENT_1_EMAIL`, `SEED_STUDENT_1_PASSWORD`, dan pasangan variabel `SEED_STUDENT_2_*` serta `SEED_STUDENT_3_*`.

Tanggal deadline beasiswa contoh bersifat indikatif dan dihitung relatif terhadap tanggal seeding; periksa situs resmi program sebelum mengajukan pendaftaran.

## Fitur

- Katalog publik dengan pencarian, filter beasiswa, dan urutan deadline.
- Autentikasi Laravel Breeze (Blade) dengan role admin dan mahasiswa.
- Profil akademik, bookmark, pendaftaran dengan dokumen privat, dan pelacakan status lamaran.
- CRUD beasiswa, review lamaran, dan statistik admin.
- Email pengingat deadline H-7 dan H-1 untuk beasiswa tersimpan.

## Pengujian

Jalankan feature test dengan:

```powershell
php artisan test
```
