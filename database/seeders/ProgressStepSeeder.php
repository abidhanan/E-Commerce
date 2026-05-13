<?php

namespace Database\Seeders;

use App\Models\ProgressStep;
use Illuminate\Database\Seeder;

class ProgressStepSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            [
                'module' => 'return_module',
                'items' => [
                    [
                        'title' => 'Hubungi tim kami',
                        'slug' => 'return-contact-team',
                        'description' => 'Sampaikan nomor order dan alasan retur agar tim bisa memeriksa kelayakan permintaan.',
                        'step_order' => 1,
                    ],
                    [
                        'title' => 'Verifikasi produk',
                        'slug' => 'return-product-verification',
                        'description' => 'Tim akan mengecek status pesanan, kondisi barang, dan jendela waktu retur.',
                        'step_order' => 2,
                    ],
                    [
                        'title' => 'Kirim kembali barang',
                        'slug' => 'return-send-back-item',
                        'description' => 'Kemas produk dengan aman lalu kirim sesuai instruksi yang sudah diberikan.',
                        'step_order' => 3,
                    ],
                    [
                        'title' => 'Refund atau penggantian',
                        'slug' => 'return-refund-or-replacement',
                        'description' => 'Setelah barang diterima dan lolos pemeriksaan, proses refund atau penggantian akan dijalankan.',
                        'step_order' => 4,
                    ],
                ],
            ],
            [
                'module' => 'how_to_buy_module',
                'items' => [
                    [
                        'title' => 'Pilih produk',
                        'slug' => 'how-to-buy-select-product',
                        'description' => 'Buka detail produk, cek size guide, lalu pilih varian yang tersedia.',
                        'step_order' => 1,
                    ],
                    [
                        'title' => 'Masukkan alamat',
                        'slug' => 'how-to-buy-set-address',
                        'description' => 'Tambahkan alamat pengiriman aktif agar admin bisa menghitung ongkir dan total final.',
                        'step_order' => 2,
                    ],
                    [
                        'title' => 'Buat pesanan',
                        'slug' => 'how-to-buy-place-order',
                        'description' => 'Klik pesan untuk mengirim order ke admin dan menunggu konfirmasi total pembayaran.',
                        'step_order' => 3,
                    ],
                    [
                        'title' => 'Bayar setelah quote',
                        'slug' => 'how-to-buy-pay-after-quote',
                        'description' => 'Setelah admin mengirim total akhir dan link pembayaran, selesaikan pembayaran sesuai instruksi.',
                        'step_order' => 4,
                    ],
                ],
            ],
        ];

        foreach ($steps as $module) {
            foreach ($module['items'] as $item) {
                ProgressStep::query()->updateOrCreate(
                    ['slug' => $item['slug']],
                    [
                        'module' => $module['module'],
                        'title' => $item['title'],
                        'description' => $item['description'],
                        'step_order' => $item['step_order'],
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
