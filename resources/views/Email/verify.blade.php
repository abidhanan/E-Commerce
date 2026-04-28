<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Verify Email - Clothique</title>
</head>
<body style="margin:0; padding:0; background-color:#fafafa; font-family:'Helvetica Neue', Helvetica, Arial, sans-serif;">

    <table width="100%" bgcolor="#fafafa" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td align="center" style="padding: 60px 20px;">

                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background:#ffffff; border: 1px solid #e5e5e5;">
                    
                    <tr>
                        <td align="center" style="padding: 50px 20px 20px 20px;">
                            <h1 style="margin:0; font-size:32px; font-weight:normal; letter-spacing:6px; text-transform:uppercase; color:#000000;">
                                Clothique
                            </h1>
                            <div style="height:2px; width:40px; background-color:#000000; margin: 25px auto 0 auto;"></div>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding: 30px 50px 50px 50px; text-align:center;">
                            
                            <h2 style="margin:0 0 20px 0; font-size:16px; font-weight:bold; letter-spacing:3px; text-transform:uppercase; color:#000000;">
                                Verify Your Email
                            </h2>

                            <p style="margin:0 0 30px 0; font-size:14px; line-height:1.8; color:#555555; font-weight:300;">
                                Welcome to the exclusive world of Clothique. To ensure the security of your account and begin your journey with us, please verify your email address by clicking the button below.
                            </p>

                            <div style="margin: 40px 0;">
                                <a href="{{ $url }}" 
                                   style="background-color:#000000; color:#ffffff; padding:18px 36px; font-size:12px; font-weight:bold; letter-spacing:3px; text-transform:uppercase; text-decoration:none; display:inline-block;">
                                    Verify Email
                                </a>
                            </div> 

                            <p style="margin:0 0 10px 0; font-size:12px; color:#999999; line-height:1.5;">
                                If you did not create an account with Clothique, please ignore this email. No further action is required.
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="background-color:#000000; padding:40px 20px; text-align:center;">
                            <p style="margin:0; font-size:10px; letter-spacing:2px; text-transform:uppercase; color:#888888;">
                                © {{ date('Y') }} Clothique. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>