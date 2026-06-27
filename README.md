# Laravel E-Commerce

Aplikasi e-commerce berbasis Laravel yang dapat disesuaikan untuk berbagai jenis toko online. Aplikasi ini mencakup storefront, katalog produk, checkout, manajemen pesanan, pembayaran Duitku, blog, konten landing page, laporan finance, dan panel admin berbasis role.

## Fitur Utama

- Storefront publik: home, shop, detail produk, kategori, collection, search, about, FAQ, care guide, return policy, how to buy, crash replacement, legal document, dan blog.
- Akun customer: register, login, verifikasi email, reset password, profil, alamat, wishlist, cart, checkout, histori pesanan, review, dan komplain pesanan dengan foto.
- Checkout: alur order dari cart atau direct checkout, validasi stok varian, alamat pengiriman, catatan customer, dan pembuatan kode order.
- Pembayaran: integrasi Duitku Payment Gateway otomatis, request invoice API, redirect ke payment URL, callback tervalidasi, return URL, dan halaman status pembayaran.
- Admin catalog: produk, gambar produk, varian, kategori, collections, size guide, temperature, intensities, insulation, breathability, dan materials.
- Admin content: display landing page, best seller, custom collections, social link, FAQ, about us, how-to-buy step, return step, care guide, crash replacement, consent document, blog category, tag, dan post.
- Admin order: daftar order, detail order, quote ongkir dan total pembayaran, update status, komplain order, review, dan lifecycle stok.
- Finance: dashboard revenue, piutang, status transaksi, top product, daftar transaksi, filter, dan export CSV.
- Akses internal: role `superadmin`, `admin`, `editor`, `finance`, `staff`, dan `user` memakai Spatie Laravel Permission.
- Audit internal: aktivitas mutasi data admin dicatat melalui middleware `admin.activity`.
- Keamanan dasar: rate limit auth, email verification, CSRF untuk web form, signature Duitku callback, dan security headers.

## Tech Stack

- PHP `^8.2`
- Laravel `^12.0`
- SQLite default dari `.env.example`, bisa diganti ke MySQL/MariaDB/PostgreSQL lewat konfigurasi Laravel
- Vite `^7`
- Tailwind CSS `^4`
- Bootstrap 5 dan Bootstrap Icons pada template admin
- Spatie Laravel Permission
- Jenssegers Agent untuk metadata device/browser pada activity log
- PHPUnit untuk test

## Struktur Penting

```text
app/Http/Controllers     Controller storefront, auth, checkout, payment, admin, dan content
app/Models               Model produk, order, user, blog, landing page, dan atribut katalog
app/Services             Service lifecycle order dan stok
app/Notifications        Email verifikasi dan notifikasi order
config/admin_permissions.php
                         Definisi role dan permission admin
config/duitku.php        Konfigurasi Duitku Payment Gateway
database/migrations      Skema database
database/seeders         Data awal role, user, produk, collection, blog, FAQ, dll
resources/views          Blade untuk user, auth, email, payment, dan admin
routes/web.php           Semua route web aplikasi
tests/Feature            Test fitur checkout, Duitku, order history, admin finance, dll
```

## Instalasi Lokal

1. Clone repository dan masuk ke folder proyek.

```bash
git clone <url-repository>
cd E-Commerce
```

2. Install dependency PHP dan JavaScript.

```bash
composer install
npm install
```

3. Buat file environment dan application key.

```bash
cp .env.example .env
php artisan key:generate
```

4. Atur `.env`.

Contoh minimal untuk SQLite:

```env
APP_NAME="Laravel E-Commerce"
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/ke/project/database/database.sqlite

QUEUE_CONNECTION=database
SESSION_DRIVER=database
MAIL_MAILER=log
```

Jika file SQLite belum ada:

```bash
touch database/database.sqlite
```

5. Jalankan migrasi dan seeder.

```bash
php artisan migrate --seed
```

6. Buat link storage publik.

```bash
php artisan storage:link
```

7. Jalankan aplikasi.

```bash
php artisan serve
npm run dev
```

Aplikasi web berjalan di `http://127.0.0.1:8000`. Vite berjalan terpisah untuk asset development.

## Perintah Alternatif

Laravel script di `composer.json` menyediakan beberapa shortcut:

```bash
composer run setup
composer run dev
composer run test
npm run build
```

Catatan: `composer run dev` menjalankan server Laravel, queue listener, log viewer `pail`, dan Vite secara bersamaan menggunakan `concurrently`.

## Akun Seeder

Semua akun seed memakai password:

```text
password123
```

| Role | Email |
| --- | --- |
| Super Admin | `superadmin@toko.com` |
| Admin | `admin@toko.com` |
| Editor | `editor@toko.com` |
| Finance | `finance@toko.com` |
| Staff | `staff@toko.com` |
| Customer | `customer@toko.com` |

Dashboard admin dapat diakses melalui `/dashboard` setelah login sebagai role internal.

## Konfigurasi Duitku

Tambahkan key berikut di `.env` saat ingin menjalankan pembayaran Duitku:

```env
DUITKU_MERCHANT_CODE=
DUITKU_API_KEY=
DUITKU_SANDBOX=true
DUITKU_CALLBACK_URL="${APP_URL}/payment/callback"
DUITKU_RETURN_URL="${APP_URL}/payment/return"
DUITKU_PAYMENT_METHOD=VC
DUITKU_TIMEOUT=15
```

Callback Duitku diarahkan ke:

```text
POST /payment/callback
```

Return URL Duitku diarahkan ke:

```text
GET /payment/return
```

Untuk development lokal dengan callback dari Duitku, gunakan tunnel seperti Ngrok dan set `APP_URL`, `DUITKU_CALLBACK_URL`, dan `DUITKU_RETURN_URL` ke URL publik tunnel tersebut.

## Alur Order dan Stok

- Customer membuat order dari cart atau direct checkout.
- Order awal checkout memakai status `pending`.
- Sistem meminta invoice ke Duitku dan menyimpan `payment_reference`, `payment_method`, `payment_url`, dan `payment_status`.
- Duitku callback menjadi sumber perubahan status pembayaran.
- Stok dikurangi saat status masuk ke `quoted`, `paid`, `processing`, `shipped`, atau `completed`.
- Stok dikembalikan saat status kembali ke `waiting_admin`, `pending`, `cancelled`, `failed`, atau `refunded`.
- Order `shipped` dapat otomatis selesai saat `delivery_estimated_at` sudah lewat ketika halaman status/admin diakses.

Status yang digunakan aplikasi:

```text
waiting_admin, quoted, pending, challenge, paid, processing, shipped,
completed, cancelled, failed, refunded
```

## Route Utama

| Area | Route |
| --- | --- |
| Home | `/` |
| Shop | `/shop` |
| Detail produk | `/product/{product}` |
| Category | `/category/{category}` |
| Collection | `/collection/{collection}` |
| Blog | `/post` dan `/post/{slug}` |
| Search | `/search` |
| Login | `/login` |
| Register | `/register` |
| Account | `/account` |
| Cart | `/cart` |
| Wishlist | `/wishlist` |
| Checkout | `/checkout` |
| Order customer | `/orders` |
| Status pembayaran | `/payments/{orderCode}/status` |
| Dashboard admin | `/dashboard` |
| Admin resources | `/admin/...` |
| Finance | `/admin/finance` |

## Role dan Permission

Definisi role dan permission ada di `config/admin_permissions.php`.

- `superadmin`: semua permission, termasuk role access dan performance team.
- `admin`: mayoritas modul operasional, produk, konten, order, finance, dan user.
- `editor`: produk, kategori, collection, blog, dan storefront.
- `finance`: order dan finance.
- `staff`: order dan storefront.
- `user`: akses storefront/customer.

Jalankan ulang seeder role jika ada perubahan permission:

```bash
php artisan db:seed --class=RoleSeeder
```

## File Upload dan Storage

Aplikasi menyimpan gambar produk, kategori, collection, blog thumbnail, display, banner login, material, size guide, dan foto komplain ke disk `public`.

Pastikan symlink storage aktif:

```bash
php artisan storage:link
```

URL file publik menggunakan pola:

```text
/storage/{path-file}
```

## Email dan Queue

Notifikasi yang tersedia:

- Verifikasi email custom.
- Email order dibuat.
- Reset password.

Default `.env.example` memakai:

```env
MAIL_MAILER=log
QUEUE_CONNECTION=database
```

Untuk memproses queue database secara lokal:

```bash
php artisan queue:listen --tries=1 --timeout=0
```

## Testing

Jalankan seluruh test:

```bash
php artisan test
```

Atau lewat composer script:

```bash
composer run test
```

Konfigurasi test memakai SQLite in-memory, mail array, queue sync, cache array, dan session array dari `phpunit.xml`.

## Build Production

Contoh langkah build sebelum deploy:

```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Pastikan environment production memakai nilai yang sesuai:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-produksi
DUITKU_SANDBOX=false
DUITKU_MERCHANT_CODE=kode-merchant-produksi
DUITKU_API_KEY=api-key-produksi
DUITKU_CALLBACK_URL=https://domain-produksi/payment/callback
DUITKU_RETURN_URL=https://domain-produksi/payment/return
```

## Catatan Data Awal

Seeder menyiapkan data dasar untuk demo dan pengembangan:

- Role, permission, dan user awal.
- Kategori produk, collection, atribut produk, size guide, produk, gambar SVG placeholder, dan varian stok.
- Konten about, FAQ, progress step return/how-to-buy, care guide, crash replacement, social link, blog, customer order, dan activity log.

Setelah `php artisan migrate --seed`, aplikasi sudah bisa diuji dari sisi customer maupun admin.
