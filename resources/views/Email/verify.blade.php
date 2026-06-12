<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Your Email</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body style="margin:0; padding:0; background-color:#ffffff; font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;">
        <tr>
            <td align="center" style="padding:40px 20px;">
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px; background-color:#ffffff; border:1px solid #e0e0e0;">

                    <tr>
                        <td style="padding:32px 40px 0 40px;">
                            <img src="{{ url('images/logo.jpg') }}" alt="{{ config('app.name') }}" height="40" style="display:block; height:40px; max-width:140px; border:0;">
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:18px 40px 0 40px;">
                            <p style="font-size:30px; font-weight:700; color:#1a1a1a; line-height:1.2; margin:0 0 14px 0;">Verify Email</p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr><td style="border-top:1px solid #e0e0e0; height:1px;">&nbsp;</td></tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:28px 40px 32px 40px;">
                            <p style="font-size:14px; font-weight:500; color:#1a1a1a; line-height:1.7; margin:0 0 14px 0;">
                                Halo.
                            </p>
                            <p style="font-size:13px; color:#3a3a3a; line-height:1.75; margin:0 0 28px 0;">
                                Terima kasih telah mendaftar di <strong>{{ config('app.name') }}</strong>.<br>
                                Untuk menyelesaikan pendaftaran dan mengamankan akun Anda, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding:0 0 28px 0;">
                                        <a href="{{ $url }}" style="display:inline-block; padding:14px 48px; font-size:14px; font-weight:600; color:#ffffff; text-decoration:none; background-color:#1a1a1a; border-radius:8px;">
                                            Verify Email Address
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size:13px; color:#3a3a3a; line-height:1.75; margin:0 0 28px 0;">
                                Jika Anda kesulitan mengklik tombol di atas, salin dan tempel URL berikut ke browser Anda:<br>
                                <a href="{{ $url }}" style="color:#1a1a1a; word-break: break-all;">{{ $url }}</a>
                            </p>
                            
                            <p style="font-size:13px; color:#3a3a3a; line-height:1.75; margin:0 0 28px 0;">
                                Jika Anda tidak merasa membuat akun ini, abaikan email ini. Tidak ada tindakan lebih lanjut yang diperlukan.
                            </p>

                            <p style="font-size:13px; color:#3a3a3a; margin:0 0 8px 0;">Sincerely</p>
                            <img src="{{ url('images/logotype.jpg') }}" alt="{{ config('app.name') }}" height="32" style="display:block; height:32px; max-width:160px; border:0;">
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:22px 20px; background-color:#1a1a1a;">
                            <table cellpadding="0" cellspacing="0" border="0" align="center">
                                <tr>
                                    <td align="center" style="padding:0 12px;">
                                        <p style="font-size:8px; font-weight:700; color:#C8A97E; margin:0 0 2px 0;">INSTAGRAM:</p>
                                        <p style="font-size:9px; color:#aaaaaa; margin:0;">@gloamingofficial</p>
                                    </td>
                                    </tr>
                            </table>
                            <p style="font-size:10px; color:#666666; margin:16px 0 0 0;">
                                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>