# School Work BE

Repositori API Backend untuk tugas sekolah SMK Negeri 7 Samarinda Kelas XII RPL 1.

## Prasyarat

Berikut adalah beberapa hal yang diinstal terlebih dahulu:

-   composer - [Download composer](https://getcomposer.org/)
-   PHP >=7.4
-   MySQL >=15.1
-   XAMPP [Download XAMPP](https://www.apachefriends.org/index.html)

Jika anda menggunakan XAMPP, untuk PHP dan MySQL sudah menjadi 1 (bundle) didalam aplikasi XAMPP.

## Langkah-langkah instalasi

-   Clone repositori ini.

```bash
https://github.com/mrizkimaulidan/school-work-be.git
```

```bash
git@github.com:mrizkimaulidan/school-work-be.git
```

-   Install seluruh packages yang dibutuhkan.

```bash
composer install
```

-   Siapkan database dan atur file .env sesuai dengan konfigurasi anda.
-   Jika sudah, migrate seluruh migrasi, seeding data dan install passport client key.

```bash
php artisan migrate --seed
```

```bash
php artisan passport:install --force
```

-   Dan jalankan local development server.

```bash
php artisan serve
```

-   User default aplikasi untuk login

_Administrator_

```
Email       : admin@mail.com
Password    : secret
```

_Pegawai_

```
Email       : pegawai@mail.com
Password    : secret
```

_Pengguna_

```
Email       : pengguna@mail.com
Password    : secret
```

## Dibuat dengan

-   [Laravel](https://laravel.com/) - Web Framework
