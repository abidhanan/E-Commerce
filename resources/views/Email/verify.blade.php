<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Email</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family:Arial, sans-serif;">

    <table width="100%" bgcolor="#f4f4f4" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">

                <table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; margin-top:40px; border-radius:10px; overflow:hidden;">
                    
                    <!-- HEADER -->
                    <tr>
                        <td style="background:#4f46e5; padding:20px; text-align:center; color:white;">
                            <h2 style="margin:0;">E-Commerce App</h2>
                        </td>
                    </tr>

                    <!-- BODY -->
                    <tr>
                        <td style="padding:30px; color:#333;">
                            
                            <h3>Halo 👋</h3>

                            <p>Terima kasih sudah mendaftar di aplikasi kami.</p>

                            <p>Silakan klik tombol di bawah ini untuk memverifikasi email kamu:</p>

                            <!-- BUTTON -->
                            <div style="text-align:center; margin:30px 0;">
                                <a href="{{ $url }}" 
                                   style="background:#4f46e5; color:white; padding:12px 25px; text-decoration:none; border-radius:6px; display:inline-block;">
                                     Verifikasi Email
                                </a>
                            </div> 

                            <p>Jika kamu tidak merasa mendaftar, abaikan email ini.</p>

                            <p>Terima kasih,<br><strong>Tim E-Commerce</strong></p>
                        </td>
                    </tr>

                    <!-- FOOTER -->
                    <tr>
                        <td style="background:#f4f4f4; padding:15px; text-align:center; font-size:12px; color:#777;">
                            © {{ date('Y') }} E-Commerce App. All rights reserved.
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>