<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Listing Approved</title>
</head>
<body style="margin:0; padding:0; background:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f7fb; padding:30px 15px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:620px; background:#ffffff; border-radius:18px; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,0.08);">

                    <tr>
                        <td style="background:linear-gradient(135deg, #065f46, #10b981); padding:28px 30px; text-align:center;">
                            <img src="https://citiinfo.com.au/assets/images/logo.png" alt="Citiinfo" style="height:48px; display:block; margin:0 auto 12px;">
                            <h1 style="margin:0; font-size:28px; line-height:1.2; color:#ffffff; font-weight:700;">
                                Your Listing Has Been Approved
                            </h1>
                            <p style="margin:10px 0 0; font-size:15px; color:#d1fae5;">
                                Congratulations! Your business is now live on Citiinfo
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:35px 30px;">
                            <p style="margin:0 0 18px; font-size:16px; color:#111827;">
                                Hello <strong>{{ $name ?? $user->name }}</strong>,
                            </p>

                            <p style="margin:0 0 16px; font-size:15px; line-height:1.8; color:#4b5563;">
                                Congratulations! Your listing has been approved by the <strong>Citiinfo</strong> team.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#ecfdf5; border:1px solid #a7f3d0; border-radius:12px; margin:20px 0;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="margin:0 0 8px; font-size:14px; color:#047857; font-weight:700;">
                                            Business Name
                                        </p>
                                        <p style="margin:0; font-size:15px; color:#111827;">
                                            {{ $listing->business_name }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 14px; font-size:15px; line-height:1.8; color:#4b5563;">
                                You can now login using the details below:
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px; margin:20px 0;">
                                <tr>
                                    <td style="padding:20px;">
                                        <p style="margin:0 0 10px; font-size:14px; color:#6b7280;">Email / Username</p>
                                        <p style="margin:0 0 16px; font-size:16px; color:#111827; font-weight:700;">
                                            {{ $user->email }}
                                        </p>

                                        @if($showPassword && !empty($plainPassword))
                                            <p style="margin:0 0 10px; font-size:14px; color:#6b7280;">Password</p>
                                            <p style="margin:0; font-size:16px; color:#111827; font-weight:700;">
                                                {{ $plainPassword }}
                                            </p>
                                        @else
                                            <p style="margin:0; font-size:15px; line-height:1.8; color:#4b5563;">
                                                Please use your existing account password to login.
                                            </p>
                                        @endif
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 16px; font-size:15px; line-height:1.8; color:#4b5563;">
                                Please login and manage your listing from your dashboard.
                            </p>

                            <p style="margin:24px 0 0; font-size:15px; line-height:1.8; color:#4b5563;">
                                Thank you,<br>
                                <strong>Citiinfo Team</strong>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:20px 30px; background:#f9fafb; border-top:1px solid #e5e7eb; text-align:center;">
                            <p style="margin:0; font-size:13px; color:#6b7280;">
                                © {{ date('Y') }} Citiinfo. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>