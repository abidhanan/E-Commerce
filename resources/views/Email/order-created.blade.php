<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Pesanan Dibuat</title>
</head>

<body style="margin:0; padding:0; background:#ffffff; font-family:Arial,sans-serif; color:#1a1a1a;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff;">
        <tr>
            <td align="center" style="padding:36px 18px;">
                <table width="620" cellpadding="0" cellspacing="0" border="0" style="max-width:620px; border:1px solid #e5e5e5;">
                    <tr>
                        <td style="padding:28px 34px 0;">
                            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" height="38" style="display:block; height:38px; border:0;">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 34px 12px;">
                            <h1 style="margin:0; font-size:28px; line-height:1.25;">Pesanan berhasil dibuat</h1>
                            <p style="margin:14px 0 0; font-size:14px; line-height:1.7; color:#444;">
                                Halo {{ $user->name ?? 'Customer' }}, pesanan {{ $order->order_code }} sudah kami terima.
                                Admin akan mengecek alamat, ongkir, dan total akhir sebelum mengirim link pembayaran Midtrans.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 34px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #eeeeee;">
                                @foreach ($order->items as $item)
                                    <tr>
                                        <td style="padding:14px 0; border-bottom:1px solid #eeeeee;">
                                            <strong style="font-size:14px;">{{ $item->product->name ?? 'Produk' }}</strong>
                                            <div style="font-size:12px; color:#666; margin-top:4px;">
                                                Size {{ $item->productVariant->size ?? '-' }} / Qty {{ $item->qty }}
                                            </div>
                                        </td>
                                        <td align="right" style="padding:14px 0; border-bottom:1px solid #eeeeee; font-size:14px;">
                                            Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 34px;">
                            <p style="margin:0 0 8px; font-size:13px;"><strong>Alamat pengiriman</strong></p>
                            @if ($order->address)
                                <p style="margin:0; font-size:13px; line-height:1.7; color:#555;">
                                    {{ $order->address->recipient_name }} / {{ $order->address->phone_number }}<br>
                                    {{ $order->address->full_address }},
                                    {{ $order->address->city }},
                                    {{ $order->address->province }}
                                    {{ $order->address->postal_code }}
                                </p>
                            @else
                                <p style="margin:0; font-size:13px; color:#555;">Alamat belum tersedia.</p>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:16px 34px 30px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
                                <tr>
                                    <td style="font-size:14px; color:#555;">Subtotal produk</td>
                                    <td align="right" style="font-size:16px;"><strong>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</strong></td>
                                </tr>
                                <tr>
                                    <td style="padding-top:8px; font-size:14px; color:#555;">Ongkir</td>
                                    <td align="right" style="padding-top:8px; font-size:14px;">Menunggu admin</td>
                                </tr>
                            </table>
                            <a href="{{ $statusUrl }}" style="display:inline-block; padding:13px 24px; background:#111111; color:#ffffff; text-decoration:none; font-size:13px; font-weight:700;">
                                Lihat Status Pesanan
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding:18px 24px; background:#111111; color:#999999; font-size:11px;">
                            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
