<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            color: #111827;
        }

        .wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 560px;
            background: #ffffff;
            border-radius: 18px;
            padding: 32px;
            box-shadow: 0 24px 48px rgba(15, 23, 42, 0.08);
        }

        .badge {
            display: inline-block;
            padding: 8px 14px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 18px;
        }

        .badge.paid {
            background: #dcfce7;
            color: #166534;
        }

        .badge.pending,
        .badge.challenge,
        .badge.waiting_admin,
        .badge.quoted,
        .badge.processing,
        .badge.shipped {
            background: #fef3c7;
            color: #92400e;
        }

        .badge.failed,
        .badge.refunded,
        .badge.cancelled {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge.completed {
            background: #dcfce7;
            color: #166534;
        }

        h1 {
            margin: 0 0 12px;
            font-size: 30px;
        }

        p {
            line-height: 1.7;
            color: #4b5563;
        }

        .details {
            margin: 24px 0;
            padding: 18px;
            border-radius: 14px;
            background: #f9fafb;
        }

        .details strong {
            color: #111827;
        }

        .flash {
            margin: 0 0 18px;
            padding: 12px 14px;
            border-radius: 12px;
            background: #dcfce7;
            color: #166534;
            font-size: 14px;
            line-height: 1.6;
        }

        .actions {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-top: 24px;
        }

        .actions a {
            text-decoration: none;
            border-radius: 999px;
            padding: 12px 18px;
            font-weight: 700;
        }

        .actions .primary {
            background: #111827;
            color: #ffffff;
        }

        .actions .secondary {
            background: #e5e7eb;
            color: #111827;
        }
    </style>
</head>
<body>
    @php
        $labelMap = [
            'waiting_admin' => 'Menunggu Konfirmasi Admin',
            'quoted' => 'Menunggu Pembayaran',
            'paid' => 'Lunas',
            'pending' => 'Menunggu Pembayaran',
            'challenge' => 'Perlu Review',
            'failed' => 'Gagal',
            'refunded' => 'Dikembalikan',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        $messageMap = [
            'waiting_admin' => 'Pesanan sudah masuk. Admin akan mengecek alamat, ongkir, dan total akhir sebelum mengirim link pembayaran.',
            'quoted' => 'Admin sudah mengonfirmasi total akhir. Gunakan link pembayaran yang tersedia untuk menyelesaikan pembayaran.',
            'paid' => 'Pembayaran sudah diterima. Order kamu aman diproses.',
            'pending' => 'Transaksi sudah dibuat, tapi pembayaran belum selesai. Kalau baru saja bayar, tunggu webhook Midtrans masuk lalu refresh halaman ini.',
            'challenge' => 'Transaksi masih perlu review dari Midtrans atau bank. Pantau lagi statusnya sebentar lagi.',
            'failed' => 'Pembayaran tidak berhasil atau transaksi dibatalkan.',
            'refunded' => 'Transaksi sudah direfund atau kena chargeback.',
            'processing' => 'Pesanan sedang diproses.',
            'shipped' => 'Pesanan sudah dikirim.',
            'completed' => 'Pesanan sudah selesai.',
            'cancelled' => 'Pesanan dibatalkan.',
        ];
    @endphp

    <div class="wrapper">
        <div class="card">
            @if (session('notify.message'))
                <div class="flash">{{ session('notify.message') }}</div>
            @endif
            <div class="badge {{ $order->status }}">{{ $labelMap[$order->status] ?? ucfirst($order->status) }}</div>
            <h1>Status pembayaran</h1>
            <p>{{ $messageMap[$order->status] ?? 'Status transaksi sedang diproses.' }}</p>

            <div class="details">
                <p><strong>Order:</strong> {{ $order->order_code }}</p>
                <p><strong>Subtotal produk:</strong> Rp{{ number_format($order->subtotal ?: $order->items->sum(fn($item) => $item->price * $item->qty), 0, ',', '.') }}</p>
                <p><strong>Ongkir:</strong>
                    {{ is_null($order->shipping_cost) ? 'Menunggu admin' : 'Rp' . number_format($order->shipping_cost, 0, ',', '.') }}
                </p>
                <p><strong>Total:</strong> Rp{{ number_format($order->gross_amount, 0, ',', '.') }}</p>
                <p><strong>Status di database:</strong> {{ $labelMap[$order->status] ?? ucfirst($order->status) }}</p>
                @if ($sourceStatus)
                    <p><strong>Status callback browser:</strong> {{ $sourceStatus }}</p>
                @endif
                @if ($order->admin_note)
                    <p><strong>Catatan admin:</strong> {{ $order->admin_note }}</p>
                @endif
                @if ($order->address)
                    <p><strong>Alamat:</strong> {{ $order->address->full_address }}, {{ $order->address->city }}, {{ $order->address->province }}</p>
                @endif
            </div>

            <div class="actions">
                @if ($order->payment_url)
                    <a class="primary" href="{{ $order->payment_url }}" target="_blank" rel="noopener">Buka Link Pembayaran</a>
                @endif
                <a class="primary" href="{{ route('user.orders.show', $order->order_code) }}">Detail Pesanan</a>
                <a class="secondary" href="{{ route('user.orders.index') }}">Riwayat Pesanan</a>
                <a class="primary" href="{{ route('payments.status', $order->order_code) }}">Refresh Status</a>
                <a class="secondary" href="{{ route('home') }}">Kembali ke Store</a>
            </div>
        </div>
    </div>
</body>
</html>
