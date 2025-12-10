<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Quote Email</title>
</head>

<body style="font-family: Arial, sans-serif; background:#f4f4f7; padding:20px;">
    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 2px 6px rgba(0,0,0,0.1);">
                    <tr>
                        <td style="background:#4f46e5; padding:20px; text-align:center; color:#fff;">
                            <h2 style="margin:0;">DigiRush</h2>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:30px; color:#333;">
                            <h3>Hello {{ $quote->person->name ?? 'Customer' }},</h3>
                            <p>Thank you for your interest in <strong>DigiRush</strong>.</p>
                            <p>Please find your <strong>quote</strong> attached to this email.</p>
                            <p>If you have any questions, just hit reply — we’ll be happy to help.</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:20px; text-align:center; background:#f9fafb; color:#555; font-size:14px;">
                            Thanks,<br>
                            <strong>The DigiRush Team</strong><br>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>