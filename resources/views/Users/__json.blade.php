@php
    $internalKeys = [
        '__path',
        '__data',
        '__env',
        'app',
        'errors',
        '__userJsonView',
        'viewName',
        'requirements',
        'internalKeys',
    ];

    $viewName = $__userJsonView ?? null;

    $requirements = [
        'Users.Template.index' => [
            'description' => 'Layout utama halaman user.',
            'required_features' => [
                'Memuat struktur HTML dasar untuk halaman user.',
                'Menyediakan slot konten utama halaman.',
                'Memuat navbar, footer, cart sidebar, shop sidebar, notification, dialog, style, dan script user.',
            ],
        ],
        'Users.Template.header' => [
            'description' => 'Head document untuk halaman user.',
            'required_features' => [
                'Memuat title halaman dari section Blade.',
                'Memuat meta viewport, CSRF token, favicon, font, CSS global, dan stack CSS halaman.',
                'Menjaga asset user-side tersedia tanpa bergantung pada layout admin.',
            ],
        ],
        'Users.Template.navbar' => [
            'description' => 'Navigasi utama storefront.',
            'required_features' => [
                'Menampilkan logo dan link navigasi utama.',
                'Menyediakan akses search, account, wishlist, cart, dan menu shop.',
                'Mendukung tampilan desktop dan mobile.',
            ],
        ],
        'Users.Template.footer' => [
            'description' => 'Footer storefront.',
            'required_features' => [
                'Menampilkan informasi brand, link halaman informasi, kontak, dan sosial media.',
                'Menyediakan area newsletter bila dibutuhkan.',
                'Tetap konsisten di seluruh halaman user.',
            ],
        ],
        'Users.Template.cart-sidebar' => [
            'description' => 'Panel samping keranjang belanja.',
            'required_features' => [
                'Menampilkan daftar item cart, quantity, subtotal, dan total.',
                'Menyediakan aksi update quantity, hapus item, dan lanjut checkout.',
                'Menangani state cart kosong.',
            ],
        ],
        'Users.Template.shop-sidebar' => [
            'description' => 'Panel samping kategori belanja.',
            'required_features' => [
                'Menampilkan kategori, koleksi, dan link belanja.',
                'Mendukung navigasi cepat ke halaman listing produk.',
                'Menangani state data kategori atau koleksi kosong.',
            ],
        ],
        'Users.Template.notification' => [
            'description' => 'Komponen notifikasi user.',
            'required_features' => [
                'Menampilkan flash message sukses, error, warning, dan info.',
                'Mendukung notifikasi validasi form.',
                'Bisa ditutup user dan tidak menghalangi konten utama.',
            ],
        ],
        'Users.Template.dialog' => [
            'description' => 'Komponen dialog/modal user.',
            'required_features' => [
                'Menyediakan modal konfirmasi umum.',
                'Mendukung aksi confirm dan cancel.',
                'Aman digunakan ulang oleh halaman user lain.',
            ],
        ],
        'Users.Template.scripts' => [
            'description' => 'Script global halaman user.',
            'required_features' => [
                'Memuat JavaScript global storefront.',
                'Mengatur interaksi navbar, sidebar, cart, dialog, notification, dan form user.',
                'Menyediakan stack script tambahan dari halaman spesifik.',
            ],
        ],
        'Users.Template.sidebar' => [
            'description' => 'Sidebar umum user.',
            'required_features' => [
                'Menampilkan menu navigasi sekunder.',
                'Mendukung active state sesuai halaman.',
                'Responsif untuk desktop dan mobile.',
            ],
        ],
        'Users.Template.home' => [
            'description' => 'Placeholder area home di layout user.',
            'required_features' => [
                'Menyediakan area tambahan untuk kebutuhan halaman beranda.',
                'Tidak mengganggu konten utama jika tidak ada data.',
            ],
        ],
        'Users.Template.exampleuse' => [
            'description' => 'Contoh penggunaan layout user.',
            'required_features' => [
                'Menunjukkan cara memakai layout user.',
                'Menunjukkan penggunaan section content, stack CSS, dan stack script.',
            ],
        ],
        'Users.partials.product-card' => [
            'description' => 'Kartu produk reusable.',
            'required_features' => [
                'Menampilkan gambar produk, nama, harga, kategori atau koleksi, dan badge status bila ada.',
                'Menyediakan link ke detail produk.',
                'Menangani state gambar atau harga kosong.',
            ],
        ],
        'Users.dashboard.index' => [
            'description' => 'Halaman beranda storefront.',
            'required_features' => [
                'Menampilkan hero atau konten utama brand.',
                'Menampilkan produk unggulan, koleksi, kategori, testimonial, dan konten pendukung sesuai data.',
                'Menyediakan CTA ke katalog atau detail produk.',
                'Menangani section kosong tanpa error.',
            ],
        ],
        'Users.dashboard.payment-tester' => [
            'description' => 'Halaman testing pembayaran untuk user.',
            'required_features' => [
                'Menampilkan ringkasan data pembayaran.',
                'Menyediakan tombol atau flow untuk memulai simulasi pembayaran.',
                'Menampilkan status callback atau response pembayaran.',
            ],
        ],
        'Users.product.show' => [
            'description' => 'Halaman detail produk.',
            'required_features' => [
                'Menampilkan galeri gambar produk, nama, harga, deskripsi, kategori, koleksi, dan detail atribut.',
                'Menyediakan pilihan variant, size, quantity, wishlist, add to cart, dan checkout bila tersedia.',
                'Menampilkan size guide, material, care info, stock, dan produk terkait.',
                'Menangani produk tanpa variant, gambar, material, atau produk terkait.',
            ],
        ],
        'Users.categories.index' => [
            'description' => 'Halaman listing produk berdasarkan kategori.',
            'required_features' => [
                'Menampilkan informasi kategori.',
                'Menampilkan grid produk kategori.',
                'Menyediakan sorting, filter, pagination, dan state produk kosong bila dibutuhkan.',
            ],
        ],
        'Users.collections.show' => [
            'description' => 'Halaman listing produk berdasarkan koleksi.',
            'required_features' => [
                'Menampilkan informasi koleksi.',
                'Menampilkan grid produk koleksi.',
                'Menyediakan sorting, filter, pagination, dan state produk kosong bila dibutuhkan.',
            ],
        ],
        'Users.search.index' => [
            'description' => 'Halaman hasil pencarian produk.',
            'required_features' => [
                'Menampilkan keyword pencarian.',
                'Menampilkan hasil produk sesuai keyword.',
                'Menyediakan state hasil kosong dan opsi kembali mencari.',
            ],
        ],
        'Users.wishlist.index' => [
            'description' => 'Halaman wishlist user.',
            'required_features' => [
                'Menampilkan daftar produk wishlist.',
                'Menyediakan aksi hapus dari wishlist dan menuju detail produk.',
                'Menangani state wishlist kosong.',
            ],
        ],
        'Users.checkout.review' => [
            'description' => 'Halaman review pesanan sebelum pembayaran.',
            'required_features' => [
                'Menampilkan item pesanan, alamat, shipping, subtotal, discount, dan total pembayaran.',
                'Menyediakan pilihan atau ringkasan metode pembayaran.',
                'Menyediakan aksi konfirmasi pesanan dan kembali mengubah data.',
                'Menampilkan validasi jika cart atau alamat tidak lengkap.',
            ],
        ],
        'Users.orders.index' => [
            'description' => 'Halaman riwayat pembelian user.',
            'required_features' => [
                'Menampilkan daftar order user beserta status, tanggal, total, dan kode pesanan.',
                'Menyediakan link ke detail pesanan.',
                'Menyediakan filter status atau pencarian bila dibutuhkan.',
                'Menangani state belum ada pesanan.',
            ],
        ],
        'Users.orders.show' => [
            'description' => 'Halaman detail pesanan user.',
            'required_features' => [
                'Menampilkan kode pesanan, status, tanggal, alamat, item pesanan, biaya, dan total.',
                'Menampilkan informasi pembayaran dan pengiriman.',
                'Menyediakan aksi sesuai status pesanan seperti bayar, batalkan, komplain, atau lihat invoice bila tersedia.',
                'Menangani order item, payment, atau shipment yang belum tersedia.',
            ],
        ],
        'Users.account.index' => [
            'description' => 'Halaman akun user.',
            'required_features' => [
                'Menampilkan profil user dan data kontak.',
                'Menyediakan form update profil, password, dan alamat.',
                'Menampilkan daftar alamat user dan alamat utama.',
                'Menyediakan validasi serta feedback sukses atau error.',
            ],
        ],
        'Users.posts.index' => [
            'description' => 'Halaman daftar artikel/blog user.',
            'required_features' => [
                'Menampilkan daftar post dengan judul, gambar, excerpt, tanggal, kategori, dan tag bila tersedia.',
                'Menyediakan pagination atau load more.',
                'Menangani state belum ada post.',
            ],
        ],
        'Users.posts.show' => [
            'description' => 'Halaman detail artikel/blog.',
            'required_features' => [
                'Menampilkan judul, gambar utama, konten, tanggal, kategori, tag, dan author bila tersedia.',
                'Menampilkan related posts.',
                'Menyediakan navigasi kembali ke daftar artikel.',
            ],
        ],
        'Users.about.index' => [
            'description' => 'Halaman About Us.',
            'required_features' => [
                'Menampilkan judul, konten, gambar, dan informasi brand dari data about.',
                'Menangani state konten about kosong.',
                'Menyediakan layout informatif yang mudah dibaca.',
            ],
        ],
        'Users.faq.index' => [
            'description' => 'Halaman FAQ.',
            'required_features' => [
                'Menampilkan daftar pertanyaan dan jawaban.',
                'Mendukung grouping kategori FAQ bila tersedia.',
                'Menyediakan interaksi accordion dan state FAQ kosong.',
            ],
        ],
        'Users.care-guide.index' => [
            'description' => 'Halaman panduan perawatan produk.',
            'required_features' => [
                'Menampilkan daftar panduan perawatan.',
                'Menyediakan konten detail berupa judul, deskripsi, gambar, atau langkah-langkah bila tersedia.',
                'Menangani state panduan kosong.',
            ],
        ],
        'Users.return-policy.index' => [
            'description' => 'Halaman kebijakan pengembalian.',
            'required_features' => [
                'Menampilkan informasi proses return dan syarat pengembalian.',
                'Menyediakan CTA atau arahan untuk mengajukan return bila tersedia.',
                'Menangani konten kebijakan yang belum diisi.',
            ],
        ],
        'Users.how-to-buy.index' => [
            'description' => 'Halaman panduan cara membeli.',
            'required_features' => [
                'Menampilkan langkah-langkah pembelian.',
                'Menyediakan informasi pembayaran, pengiriman, dan konfirmasi order.',
                'Menyediakan CTA menuju katalog produk.',
            ],
        ],
        'Users.crash-replacement.index' => [
            'description' => 'Halaman program crash replacement.',
            'required_features' => [
                'Menampilkan informasi program crash replacement.',
                'Menampilkan syarat, benefit, proses klaim, dan kontak atau CTA bila tersedia.',
                'Menangani state konten program kosong.',
            ],
        ],
    ];

    $payload = [
        'view' => $viewName,
        'description' => $requirements[$viewName]['description'] ?? 'Blade user-side.',
        'required_features' => $requirements[$viewName]['required_features'] ?? [
            'Menampilkan data yang dikirim controller.',
            'Menangani state data kosong.',
            'Mengikuti layout dan interaksi user-side.',
        ],
        'data' => collect(get_defined_vars())->except($internalKeys)->all(),
    ];

    $jsonFlags =
        JSON_PRETTY_PRINT |
        JSON_UNESCAPED_SLASHES |
        JSON_UNESCAPED_UNICODE |
        JSON_HEX_TAG |
        JSON_HEX_APOS |
        JSON_HEX_AMP |
        JSON_HEX_QUOT |
        JSON_PARTIAL_OUTPUT_ON_ERROR;

    $json = json_encode($payload, $jsonFlags);

    $json =
        $json !== false
            ? $json
            : json_encode(
                [
                    'view' => $__userJsonView ?? null,
                    'error' => json_last_error_msg(),
                    'data' => [],
                ],
                $jsonFlags,
            );

    if (request()->boolean('raw')) {
        echo $json;

        return;
    }
@endphp
<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $payload['view'] ?? 'Users JSON' }}</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #111827;
            --panel: #0b1220;
            --border: #263244;
            --text: #e5e7eb;
            --muted: #94a3b8;
            --accent: #38bdf8;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg);
            color: var(--text);
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }

        .json-page {
            min-height: 100vh;
            padding: 24px;
        }

        .json-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .json-title {
            margin: 0 0 4px;
            font-size: 20px;
            font-weight: 700;
        }

        .json-subtitle {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
        }

        .json-copy {
            text-decoration: none;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: #162033;
            color: var(--text);
            cursor: pointer;
            font-size: 13px;
            padding: 8px 12px;
        }

        .json-copy:hover {
            border-color: var(--accent);
        }

        .json-actions {
            display: flex;
            gap: 8px;
        }

        .json-output {
            min-height: calc(100vh - 112px);
            margin: 0;
            overflow: auto;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--panel);
            color: var(--text);
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
            font-size: 13px;
            line-height: 1.6;
            padding: 18px;
            white-space: pre;
        }

        @media (max-width: 640px) {
            .json-page {
                padding: 14px;
            }

            .json-header {
                align-items: stretch;
                flex-direction: column;
            }

            .json-copy {
                width: 100%;
            }

            .json-actions {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <main class="json-page">
        <header class="json-header">
            <div>
                <h1 class="json-title">{{ $payload['view'] ?? 'Users JSON' }}</h1>
                <p class="json-subtitle"> data Blade user-side.</p>
            </div>
            <div class="json-actions">
                <button class="json-copy" type="button" data-copy-json>Copy JSON</button>
                <a class="json-copy" href="{{ request()->fullUrlWithQuery(['raw' => 1]) }}">Raw JSON</a>
            </div>
        </header>

        <pre class="json-output" id="json-output"></pre>
    </main>

    <script type="application/json" id="blade-json-payload">{!! $json !!}</script>
    <script>
        const payloadElement = document.getElementById('blade-json-payload');
        const outputElement = document.getElementById('json-output');
        const copyButton = document.querySelector('[data-copy-json]');
        const payload = JSON.parse(payloadElement.textContent);
        const prettyJson = JSON.stringify(payload, null, 2);

        outputElement.textContent = prettyJson;

        copyButton.addEventListener('click', () => {
            if (navigator.clipboard) {
                navigator.clipboard.writeText(prettyJson);
            } else {
                const textarea = document.createElement('textarea');
                textarea.value = prettyJson;
                document.body.appendChild(textarea);
                textarea.select();
                document.execCommand('copy');
                textarea.remove();
            }

            copyButton.textContent = 'Copied';

            window.setTimeout(() => {
                copyButton.textContent = 'Copy JSON';
            }, 1200);
        });
    </script>
</body>

</html>
