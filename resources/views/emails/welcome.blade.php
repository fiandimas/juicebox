{{-- resources/views/emails/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f5f7; font-family: Arial, Helvetica, sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f5f7; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden;">
                    {{-- Header --}}
                    <tr>
                        <td style="background-color:#4f46e5; padding:32px 40px; text-align:center;">
                            <h1 style="color:#ffffff; font-size:22px; margin:0;">{{ config('app.name') }}</h1>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:40px;">
                            <h2 style="font-size:20px; color:#111827; margin:0 0 16px;">
                                Hi {{ $user->name }}, welcome aboard! 👋
                            </h2>
                            <p style="font-size:15px; color:#374151; line-height:1.6; margin:0 0 16px;">
                                Terima kasih sudah bergabung dengan <strong>{{ config('app.name') }}</strong>.
                                Akun kamu sudah aktif dan siap digunakan dengan email:
                            </p>
                            <p style="font-size:15px; color:#111827; background-color:#f3f4f6; padding:12px 16px; border-radius:6px; margin:0 0 24px;">
                                {{ $user->email }}
                            </p>

                            <p style="font-size:13px; color:#9ca3af; margin:32px 0 0;">
                                Kalau kamu ngerasa gak pernah daftar akun ini, abaikan aja email ini ya.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="background-color:#f9fafb; padding:20px 40px; text-align:center;">
                            <p style="font-size:12px; color:#9ca3af; margin:0;">
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