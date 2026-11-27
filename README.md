# BeasiswaHub

Platform informasi, pendaftaran, dan manajemen beasiswa menggunakan Laravel 13, MySQL, Blade, Tailwind CSS, dan Livewire.

## Menjalankan aplikasi

1. Salin `.env.example` menjadi `.env`, lalu isi kredensial MySQL dan SMTP. Zona waktu bawaan adalah `Asia/Jakarta`.
2. Jalankan `composer install`, `npm.cmd install`, dan `php artisan key:generate`.
3. Buat database MySQL `beasiswahub`, lalu jalankan `php artisan migrate --seed`.
4. Jalankan aplikasi dan aset dengan `composer run dev`.

Seeder membuat 20 beasiswa, kriteria, dan tiga akun mahasiswa. Di lingkungan `local`/`testing`, kredensial demo bawaan tersedia hanya untuk pengembangan:

| Peran | Email | Kata sandi |
|---|---|---|
| Admin | `admin@beasiswahub.test` | `AdminDemo!2026` |
| Mahasiswa | `alya.maharani@beasiswahub.test` | `StudentDemo1!2026` |
| Mahasiswa | `bima.pratama@beasiswahub.test` | `StudentDemo2!2026` |
| Mahasiswa | `citra.rahman@beasiswahub.test` | `StudentDemo3!2026` |

Ganti kredensial demo sebelum memakai lingkungan bersama. Seeder tidak menggunakan kredensial bawaan di production: atur email dan kata sandi khusus melalui `SEED_ADMIN_EMAIL`, `SEED_ADMIN_PASSWORD`, `SEED_STUDENT_1_EMAIL`, `SEED_STUDENT_1_PASSWORD`, dan pasangan variabel `SEED_STUDENT_2_*` serta `SEED_STUDENT_3_*`.

Tanggal deadline beasiswa contoh bersifat indikatif dan dihitung relatif terhadap tanggal seeding; periksa situs resmi program sebelum mengajukan pendaftaran.

## Fitur

- Daftar publik dengan pencarian, filter negara/jenjang/pendanaan/syarat bahasa/IPK/usia/kewajiban kembali, status pembukaan, dan urutan deadline.
- Registrasi, profil akademik, bookmark, pendaftaran beserta berkas privat, serta pelacakan status mahasiswa.
- CRUD beasiswa dan peninjauan lamaran untuk admin.
- Pengingat email H-7 dan H-1 untuk beasiswa yang disimpan. Atur SMTP, lalu jalankan scheduler Laravel melalui cron atau `php artisan schedule:work`.

Jalankan pengujian dengan `php artisan test` dan build frontend dengan `npm.cmd run build`.
