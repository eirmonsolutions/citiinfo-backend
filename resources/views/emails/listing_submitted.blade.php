<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Listing Submitted</title>
</head>
<body style="margin:0; padding:0; background:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f7fb; padding:30px 15px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:620px; background:#ffffff; border-radius:18px; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg, #0f172a, #2563eb); padding:28px 30px; text-align:center;">
                            <img src="https://citiinfo.com.au/assets/images/logo.png" alt="Citiinfo" style="height:48px; display:block; margin:0 auto 12px;">
                            <h1 style="margin:0; font-size:28px; line-height:1.2; color:#ffffff; font-weight:700;">
                                Listing Submitted Successfully
                            </h1>
                            <p style="margin:10px 0 0; font-size:15px; color:#dbeafe;">
                                Thank you for submitting your business to Citiinfo
                            </p>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding:35px 30px;">
                            <p style="margin:0 0 18px; font-size:16px; color:#111827;">
                                Hello <strong>{{ $listing->business_name ?? 'User' }}</strong>,
                            </p>

                            <p style="margin:0 0 16px; font-size:15px; line-height:1.8; color:#4b5563;">
                                Your listing has been submitted successfully on <strong>Citiinfo</strong>.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; margin:20px 0;">
                                <tr>
                                    <td style="padding:18px 20px;">
                                        <p style="margin:0 0 10px; font-size:14px; color:#1d4ed8; font-weight:700;">
                                            What happens next?
                                        </p>
                                        <p style="margin:0; font-size:14px; line-height:1.8; color:#374151;">
                                            Our Citiinfo team will review your listing and approve it shortly.
                                            Please wait for a few hours while we verify your submitted details.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 16px; font-size:15px; line-height:1.8; color:#4b5563;">
                                Once your listing is approved, you will receive another email with your login details.
                            </p>

                            <p style="margin:24px 0 0; font-size:15px; line-height:1.8; color:#4b5563;">
                                Thank you,<br>
                                <strong>Citiinfo Team</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
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