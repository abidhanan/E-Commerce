<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>

<body style="margin:0; padding:0; background-color:#ffffff; font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff;">
        <tr>
            <td align="center" style="padding:40px 20px;">

                <table width="600" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:600px; background-color:#ffffff; border:1px solid #e0e0e0;">

                    <!-- ── LOGO ── -->
                    <tr>
                        <td style="padding:32px 40px 0 40px;">
                            <img src="{{ asset('images/logo.jpg') }}" alt="Gloaming" height="40"
                                style="display:block; height:40px; max-width:140px; border:0;">
                        </td>
                    </tr>

                    <!-- ── JUDUL ── -->
                    <tr>
                        <td style="padding:18px 40px 0 40px;">
                            <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:30px; font-weight:700; color:#1a1a1a; letter-spacing:-1px; line-height:1.2; margin:0 0 14px 0;">
                                Reset Password
                            </p>
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td style="border-top:1px solid #e0e0e0; height:1px; font-size:0; line-height:0;">&nbsp;</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- ── ISI ── -->
                    <tr>
                        <td style="padding:28px 40px 32px 40px;">

                            <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:14px; font-weight:500; color:#1a1a1a; line-height:1.7; margin:0 0 14px 0;">
                                Halo <strong>{{ $name ?? 'User' }}</strong>.
                            </p>

                            <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:13px; color:#3a3a3a; line-height:1.75; margin:0 0 28px 0;">
                                Kami menerima permintaan untuk mereset password akun Anda di <strong style="color:#1a1a1a;">{{ config('app.name') }}</strong>.<br>
                                Klik tombol berikut untuk melanjutkan proses reset password Anda.
                            </p>

                            <!-- ── TOMBOL ── -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                <tr>
                                    <td align="center" style="padding:0 0 28px 0;">
                                        <table cellpadding="0" cellspacing="0" border="0">
                                            <tr>
                                                <td align="center" bgcolor="#1a1a1a" style="border-radius:8px;">
                                                    <a href="{{ $resetUrl ?? '#' }}"
                                                        style="display:inline-block; padding:14px 48px; font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:14px; font-weight:600; color:#ffffff; text-decoration:none; border-radius:1px; background-color:#1a1a1a;">
                                                        Reset Password
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:13px; color:#3a3a3a; line-height:1.75; margin:0 0 14px 0;">
                                Jika Anda tidak merasa melakukan permintaan ini, abaikan email ini.
                            </p>

                            <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:13px; color:#3a3a3a; line-height:1.75; margin:0 0 28px 0;">
                                Link akan kedaluwarsa dalam <strong style="color:#1a1a1a;">{{ $expire ?? '60 menit' }}</strong>.
                            </p>

                            <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:13px; color:#3a3a3a; margin:0 0 8px 0;">
                                Sincerely
                            </p>

                            <img src="{{ asset('images/logotype.jpg') }}" alt="Gloaming" height="32"
                                style="display:block; height:32px; max-width:160px; border:0;">

                        </td>
                    </tr>

                    <!-- ── FOOTER ── -->
                    <tr>
                        <td align="center" style="padding:22px 20px; background-color:#1a1a1a;">

                            <table cellpadding="0" cellspacing="0" border="0" align="center">
                                <tr>

                                    <td align="center" valign="top" style="padding:0 12px;">
                                        <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:8px; font-weight:700; color:#C8A97E; margin:0 0 2px 0; white-space:nowrap;">
                                            instagram:</p>
                                        <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:9px; color:#aaaaaa; margin:0; white-space:nowrap;">
                                            @gloamingofficial</p>
                                    </td>

                                    <td align="center" valign="top" style="padding:0 12px;">
                                        <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:8px; font-weight:700; color:#C8A97E; margin:0 0 2px 0; white-space:nowrap;">
                                            tiktok:</p>
                                        <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:9px; color:#aaaaaa; margin:0; white-space:nowrap;">
                                            @gloamingofficial</p>
                                    </td>

                                    <td align="center" valign="top" style="padding:0 12px;">
                                        <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:8px; font-weight:700; color:#C8A97E; margin:0 0 2px 0; white-space:nowrap;">
                                            shopee:</p>
                                        <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:9px; color:#aaaaaa; margin:0; white-space:nowrap;">
                                            Gloaming Official</p>
                                    </td>

                                    <td align="center" valign="top" style="padding:0 12px;">
                                        <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:8px; font-weight:700; color:#C8A97E; margin:0 0 2px 0; white-space:nowrap;">
                                            zalora:</p>
                                        <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:9px; color:#aaaaaa; margin:0; white-space:nowrap;">
                                            Gloaming Official</p>
                                    </td>

                                </tr>
                            </table>

                            <p style="font-family:'Poppins',Arial,sans-serif; letter-spacing:-0.05em; font-size:10px; color:#666666; margin:16px 0 0 0;">
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