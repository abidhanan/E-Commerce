# Codex Reference Audit - Clothique E-Commerce

Terakhir diaudit: 2026-06-27

Dokumen ini adalah acuan kerja untuk Codex saat membaca, mengubah, atau menerapkan pembaruan pada aplikasi ini. Gunakan dokumen ini sebagai peta awal, lalu tetap baca file terkait sebelum mengedit karena beberapa modul punya pola lokal yang spesifik.

## Ringkasan Aplikasi

Clothique adalah aplikasi e-commerce Laravel untuk fashion/technical apparel. Area utamanya:

- Storefront publik: home, shop, detail produk, kategori, collection, search, about, FAQ, care guide, return policy, how to buy, crash replacement, legal document, dan blog.
- Customer login: account, alamat, wishlist, cart, checkout, histori pesanan, review, komplain pesanan.
- Admin: dashboard, users, role access, produk, kategori, collection, atribut produk, size guide, blog, landing page content, order, complaint, finance, performance, error logs.
- Pembayaran: Duitku Payment Gateway otomatis melalui request invoice API, redirect ke payment URL, callback tervalidasi, dan return URL status.
- Audit internal: aktivitas tulis admin dicatat ke `activity_logs` melalui middleware.

## Stack dan Konfigurasi

- Framework: Laravel 12.58.0.
- PHP: requirement `^8.2`; environment audit memakai PHP 8.5.4.
- Frontend build: Vite 7, Tailwind CSS 4, `laravel-vite-plugin`.
- UI admin: Bootstrap 5 dan Bootstrap Icons via CDN di layout admin.
- Package penting: `spatie/laravel-permission`, `jenssegers/agent`.
- Database default config: `sqlite`, tetapi environment audit aktif memakai `mysql`.
- Session/cache/queue di environment audit: database.
- Test: PHPUnit 11; `phpunit.xml` memakai SQLite in-memory, cache/session array, queue sync, mail array.

Perintah umum:

```bash
composer install
npm install
php artisan migrate --seed
php artisan storage:link
npm run build
php artisan test
php artisan route:list
```

## Struktur Direktori Penting

```text
app/Http/Controllers/Admin            Admin order, finance, performance, notifications, role access, error logs
app/Http/Controllers/BlogController   Admin blog category, tag, post
app/Http/Controllers/LandingPageController
                                      Admin content landing page dan legal/content pages
app/Http/Controllers/MainController   Storefront/customer controllers
app/Http/Controllers/ProductController
                                      Admin catalog dan product attributes
app/Http/Controllers/UsersController  Admin user management
app/Models                            Eloquent models
app/Services                          Order lifecycle dan stock service
app/Support/HtmlSanitizer.php         Sanitizer HTML untuk rich content
config/admin_permissions.php          Role/permission source of truth
config/duitku.php                     Konfigurasi Duitku Payment Gateway
database/migrations                   Skema database
database/seeders                      Data awal role, user, catalog, content, orders
resources/views/Admin                 Blade admin
resources/views/Users                 Blade user/storefront
resources/views/components            Layout/component user aktif
resources/views/Email                 Template email notification
routes/web.php                        Semua web route dan JSON endpoint web
tests/Feature                         Test fitur utama
```

## Route dan Akses

Jumlah route saat audit: 261.

Route publik utama:

- `/`, `/shop`, `/product/{product}`, `/category/{category}`, `/collection/{collection}`, `/search`.
- `/about`, `/faq`, `/care-guide`, `/return-policy`, `/how-to-buy`, `/crash-replacement`, `/legal/{slug}`.
- `/post`, `/post/{slug}`, `/explore`.
- `/login`, `/register`, `/password/reset`, `/email/verify/{id}/{hash}`.

Route customer login (`auth`):

- `/wishlist`, `/wishlist/status`, `/wishlist/{product}`.
- `/cart`, `/cart/{cartItem}`.
- `/checkout`, `/checkout/order`, `/checkout/{variantId}`.
- `/orders`, `/orders/{orderCode}`, review, complete, complaint.
- `/payments/{orderCode}/status`, `/payments/{orderCode}/retry`.

Route verified (`auth`, `verified`):

- `/dashboard` untuk role internal.
- `/payment-tester`.
- `/account` dan route `address/*`.

Route admin:

- Prefix `/admin`, name `admin.*`.
- Middleware utama: `auth`, `verified`, role `superadmin|admin|editor|finance|staff`, dan `admin.activity`.
- Permission per modul memakai Spatie Permission dari `config/admin_permissions.php`.

Route khusus:

- `POST /payment/callback` memakai `PaymentController@callback`, CSRF dikecualikan, tetapi signature Duitku wajib valid.
- `GET /payment/return` memakai `PaymentController@returnView`, hanya menampilkan status redirect dan tidak mengubah database.
- Route demo/legacy ada di `/admin-area`, `/produk`, `/laporan`, `/order`, `/profile`; jangan anggap ini modul utama kecuali user meminta.

## Model dan Domain Data

User dan akses:

- `User` memakai `MustVerifyEmail` dan `HasRoles`.
- Role: `superadmin`, `admin`, `editor`, `finance`, `staff`, `user`.
- Permission didefinisikan di `config/admin_permissions.php`; `RoleSeeder` melakukan sync role/permission.
- `Gate::before` di `AppServiceProvider` memberi superadmin akses semua ability.

Catalog:

- `CategoryProduct` memakai tabel `categories`, relasi parent/children/products.
- `Collections` memakai tabel `collections`, relasi products.
- `Product` menyimpan category, collection, size guide, material, gender, weight, temperature, intensity, insulation, breathability, status aktif.
- `ProductVariant` menyimpan `sku`, `size`, `price`, `stock`.
- `ProductImage` menyimpan gambar, primary image, hover image.
- Atribut referensi: `TemperatureProduct`, `Intensities`, `Insulation`, `Breathability`, `Material`, `SizeGuide`.

Cart dan wishlist:

- `CartItem` terkait user dan product variant.
- `Wishlist` terkait user dan product.
- Cart API mengembalikan JSON dari web route, bukan API route.

Order:

- `Order`: `order_code`, user, address, subtotal, shipping, gross amount, status, stock flag, payment gateway/reference/method/status/url, paid timestamp, callback payload, catatan, timestamp quote/shipping/completed.
- `OrderItem`: product, variant, price, qty.
- `Address`: alamat customer, kota/provinsi/kode pos, koordinat, primary flag.
- `OrderReview`: satu review per order.
- `OrderComplaint` dan `OrderComplaintPhoto`: komplain dengan foto.

Content/storefront:

- `Post`, `CategoryBlog`, `TagBlog` untuk blog.
- `Display`, `BestSellers`, `CustomCollectionsDisplay` untuk homepage.
- `Faq`, `ProgressStep`, `CareGuide`, `CrashReplacement`, `Aboutus`, `ConsentDocument`, `SocialLink`.
- `ActivityLog` untuk aktivitas admin dan performance tracking.

## Alur Order, Payment, dan Stock

Jangan mengubah stok langsung dari controller. Gunakan `OrderStockService`.

Status order yang dikenal aplikasi:

```text
waiting_admin, quoted, pending, challenge, paid, processing, shipped,
completed, cancelled, failed, refunded
```

Flow order dari cart:

1. Customer memilih item cart dan alamat.
2. `CheckoutController@placeOrder` membuat order status `pending`.
3. Cart item yang dipilih dihapus.
4. Email `OrderCreatedNotification` dikirim jika memungkinkan.
5. `DuitkuService` meminta invoice ke API Duitku dan menyimpan `payment_reference`, `payment_method`, `payment_url`, serta `payment_status`.
6. Customer otomatis redirect ke `payment_url` dari Duitku.
7. `POST /payment/callback` adalah satu-satunya sumber perubahan status pembayaran.
8. `GET /payment/return` hanya menampilkan status dari redirect Duitku dan tidak mengubah database.

Signature Duitku:

- Invoice request memakai HMAC SHA256 dari `merchantCode + merchantOrderId + paymentAmount`.
- Callback memakai MD5 dari `merchantCode + amount + merchantOrderId + apiKey`.

Aturan stok:

- Status yang mengurangi stok: `quoted`, `paid`, `processing`, `shipped`, `completed`.
- Status yang mengembalikan stok: `waiting_admin`, `pending`, `cancelled`, `failed`, `refunded`.
- `stock_deducted_at` mencegah stok dikurangi berulang.
- Saat order `shipped` punya `delivery_estimated_at` yang sudah lewat, `OrderLifecycleService` dapat otomatis menandai `completed`.

## Frontend dan Blade

User/storefront aktif:

- Layout utama aktif: `resources/views/components/layouts/app.blade.php`.
- Component aktif: `resources/views/components/navbar.blade.php`, `resources/views/components/footer.blade.php`, `resources/views/components/product-card.blade.php`, `resources/views/components/wishlist-button.blade.php`.
- Halaman user: `resources/views/Users/*`.
- Banyak halaman user memakai `<x-layouts.app>`.
- Cart sidebar aktif di-include dari `resources/views/Users/Template/cart-sidebar.blade.php`.
- JavaScript user sebagian besar inline di layout dan cart sidebar.

Admin:

- Layout utama: `resources/views/Admin/Template/index.blade.php`.
- Sidebar/header/footer admin ada di `resources/views/Admin/Template/*`.
- UI admin dominan Bootstrap dan CSS inline layout.

Catatan penting:

- Beberapa file `resources/views/Users/Template/*` hanya meng-include `Users.__json` sebagai metadata/template lama. Jangan edit file tersebut untuk perubahan UI aktif kecuali sudah memastikan view itu benar-benar dipakai.
- Untuk halaman user baru, pakai `<x-layouts.app>` dan ikuti pola halaman di `resources/views/Users/shop/index.blade.php` atau halaman domain terkait.
- Untuk halaman admin baru, extend/include pola `Admin.Template.index` sesuai modul admin yang sudah ada.

## Upload, Asset, dan Storage

- Upload dinamis disimpan ke disk `public` (`storage/app/public`) lalu diakses lewat `/storage/...`.
- Pastikan `php artisan storage:link` aktif di environment target.
- Asset statis ada di `public/images`.
- Jangan hapus file upload lama saat update kecuali fitur memang meminta cleanup dan dampaknya jelas.

Area upload yang sudah ada:

- Produk dan gambar produk.
- Kategori dan collections.
- Blog thumbnail/content images.
- Display/banner/login display.
- Materials dan size guide.
- Complaint photos.

## Security dan Validasi

Yang sudah ada:

- Auth login/register/reset diberi throttle.
- Email verification dipakai untuk area verified.
- CSRF aktif untuk web form; Duitku callback dikecualikan tapi memakai signature.
- `SecurityHeaders` di-append ke middleware web.
- `HtmlSanitizer` membersihkan rich HTML blog/material.
- Admin write activity direkam oleh `RecordAdminActivity`.
- Role/permission berbasis Spatie Laravel Permission.

Saat menambah fitur:

- Pakai route middleware yang sama dengan modul terdekat.
- Jangan membuka endpoint admin tanpa role/permission.
- Sanitasi rich HTML sebelum render jika konten berasal dari admin/user.
- Validasi file upload: tipe gambar, ukuran, jumlah, dan disk `public`.
- Untuk callback/payment, jangan percaya payload eksternal tanpa signature atau ownership check.

## Seeder dan Akun Dev

`DatabaseSeeder` menjalankan role, user, kategori, collection, content, product attributes, size guide, product, blog, order customer, activity log.

Akun seed:

| Role | Email | Password |
| --- | --- | --- |
| superadmin | `superadmin@toko.com` | `password123` |
| admin | `admin@toko.com` | `password123` |
| editor | `editor@toko.com` | `password123` |
| finance | `finance@toko.com` | `password123` |
| staff | `staff@toko.com` | `password123` |
| user/customer | `customer@toko.com` | `password123` |

Jika mengubah permission, update `config/admin_permissions.php`, lalu pastikan `RoleSeeder` tetap sync dengan benar.

## Test Coverage yang Ada

Feature test penting:

- `CheckoutStockTest`: cart stock validation, checkout, order pending Duitku, admin quote, stock restore.
- `DuitkuPaymentTest`: callback signature, paid settlement, idempotent callback, invoice checkout, return URL read-only.
- `OrderHistoryTest`: order list/detail, ownership, auto-complete shipment, review, complaint.
- `AdminFinanceTest`: finance dashboard, role access, dashboard metrics.
- `AdminPerformanceTest`: performance page, admin activity log.
- `HomepageShowcaseTest`: category/collection showcase dan listing.
- `ProductShowPageTest`: detail produk, variant size order, unavailable state, reference attributes, material tabs.
- `PostShowPageTest`: rich content sanitization.
- `PaymentTesterPageTest`: access payment tester.

Rekomendasi test per perubahan:

- Order/payment/stock: jalankan `php artisan test --filter=CheckoutStockTest`, `--filter=DuitkuPaymentTest`, dan `--filter=OrderHistoryTest`.
- Admin finance/dashboard/performance: jalankan `--filter=AdminFinanceTest` dan `--filter=AdminPerformanceTest`.
- Storefront product/home/search/category/collection: jalankan `--filter=ProductShowPageTest` dan `--filter=HomepageShowcaseTest`.
- Blog/rich HTML: jalankan `--filter=PostShowPageTest`.
- Sebelum selesai perubahan besar: jalankan `php artisan test`.

## Temuan Risiko dan Technical Debt

Perlu diperhatikan saat update:

- `LandingpageController@returnPolicy` dan `howToBuy` memanggil view lowercase `users.*`, sedangkan folder adalah `Users`. Ini aman di filesystem case-insensitive, tetapi berisiko gagal di Linux.
- `resources/views/Users/care-guide/index.blade.php` memakai `<x-layouts.app>` tetapi juga berisi dokumen HTML lengkap (`<!DOCTYPE html>`, `<html>`, `<body>`). Ini dapat menghasilkan HTML bersarang.
- File care guide memuat Tailwind CDN dengan tag `<script src="...tailwind.min.css"></script>`, bentuknya tidak tepat untuk CSS.
- `ProductController` mengirim field `color` saat membuat variant, tetapi migration `product_variants` dan fillable model tidak punya kolom `color`.
- `products.material` bertipe `string(100)`, sementara model menyimpan JSON array. Jika material bertambah banyak, kolom ini mudah terlalu pendek; pertimbangkan migrasi ke `json` atau `text`.
- `custom_collections_displays.collection_id` dibuat sebagai string, tetapi model cast ke integer dan relasinya `belongsTo(Collections::class)`. Pertimbangkan migrasi ke foreign id jika modul ini dikembangkan.
- `resources/views/components/footer.blade.php` masih berisi link sosial/marketplace hardcoded, sementara `AppServiceProvider` menyiapkan composer untuk `Users.Template.footer`. Pastikan footer aktif sebelum mengubah social link.
- JavaScript cart/wishlist sebagian inline dan ada pola duplikasi antara layout dan template lama. Saat refactor, pastikan tidak memutus event global seperti `toggleCart()`.
- `ProductController@index` memakai `paginate(2)` di admin; cek apakah ini memang desain atau sementara sebelum mengubah.

## Aturan Kerja untuk Codex Berikutnya

Sebelum mengedit:

1. Jalankan `git status --short` dan jangan menimpa perubahan user.
2. Cari route terkait di `routes/web.php`.
3. Baca controller, model, migration, view, dan test yang terkait domain itu.
4. Ikuti pola modul terdekat, terutama untuk permission, validasi, redirect/flash, dan nama view.

Saat mengubah database:

- Tambahkan migration baru, jangan edit migration lama kecuali proyek masih benar-benar belum pernah migrate di environment target.
- Update model fillable/casts/relations.
- Update seeder bila data awal perlu berubah.
- Update test yang membuat data model terkait.

Saat mengubah order/payment:

- Gunakan `OrderStockService::applyStatus`.
- Jangan decrement/increment stok manual di controller.
- Pertahankan ownership check untuk order customer.
- Pertahankan Duitku signature validation.
- Tambahkan test untuk transisi status baru.

Saat mengubah admin permission:

- Update `config/admin_permissions.php`.
- Pastikan route admin memakai permission/role yang tepat.
- Jalankan atau update `RoleSeeder`.
- Tambahkan test role access jika aksesnya kritis.

Saat mengubah UI user:

- Prioritaskan `resources/views/components/*` dan `resources/views/Users/*`.
- Pastikan halaman tetap kompatibel dengan `<x-layouts.app>`.
- Jika butuh JS global, cek konflik nama fungsi global di layout/cart sidebar.
- Pastikan route/link memakai helper `route()` jika route sudah bernama.

Saat mengubah UI admin:

- Ikuti pola Bootstrap di `resources/views/Admin/Template/index.blade.php`.
- Jangan campur pola Tailwind storefront ke admin kecuali memang diminta.
- Pastikan form admin tetap punya CSRF, method spoofing, dan flash message.

Sebelum final:

- Jalankan test relevan minimal sesuai area perubahan.
- Jalankan `php artisan route:list` jika menambah/mengubah route.
- Jalankan `npm run build` jika mengubah asset Vite/Tailwind.
- Laporkan test yang dijalankan dan file yang diubah.
