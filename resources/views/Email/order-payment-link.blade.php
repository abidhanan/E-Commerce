<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Link Pembayaran</title>
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
                            <h1 style="margin:0; font-size:28px; line-height:1.25;">Link pembayaran tersedia</h1>
                            <p style="margin:14px 0 0; font-size:14px; line-height:1.7; color:#444;">
                                Halo {{ $user->name ?? 'Customer' }}, admin sudah mengonfirmasi pesanan {{ $order->order_code }}.
                                Silakan lanjutkan pembayaran melalui link Midtrans berikut.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:12px 34px;">
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="border-top:1px solid #eeeeee; border-bottom:1px solid #eeeeee;">
                                <tr>
                                    <td style="padding:12px 0; font-size:14px; color:#555;">Subtotal produk</td>
                                    <td align="right" style="padding:12px 0; font-size:14px;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 0; font-size:14px; color:#555;">Ongkir</td>
                                    <td align="right" style="padding:12px 0; font-size:14px;">Rp {{ number_format($order->shipping_cost ?? 0, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:12px 0; font-size:16px;"><strong>Total final</strong></td>
                                    <td align="right" style="padding:12px 0; font-size:18px;"><strong>Rp {{ number_format($order->gross_amount, 0, ',', '.') }}</strong></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    @if ($order->admin_note)
                        <tr>
                            <td style="padding:6px 34px 12px;">
                                <p style="margin:0 0 8px; font-size:13px;"><strong>Catatan admin</strong></p>
                                <p style="margin:0; font-size:13px; line-height:1.7; color:#555;">{{ $order->admin_note }}</p>
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td style="padding:18px 34px 30px;">
                            <a href="{{ $order->payment_url }}" style="display:inline-block; padding:13px 24px; background:#111111; color:#ffffff; text-decoration:none; font-size:13px; font-weight:700;">
                                Bayar via Midtrans
                            </a>
                            <a href="{{ $statusUrl }}" style="display:inline-block; padding:13px 18px; color:#111111; text-decoration:none; font-size:13px; font-weight:700;">
                                Lihat Status
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
