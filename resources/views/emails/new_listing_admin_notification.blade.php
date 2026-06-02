<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Listing Submitted</title>
</head>
<body style="margin:0; padding:0; background:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f7fb; padding:30px 15px;">
        <tr>
            <td align="center">
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:620px; background:#ffffff; border-radius:18px; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,0.08);">
                    
                    <tr>
                        <td style="background:linear-gradient(135deg, #7c3aed, #2563eb); padding:28px 30px; text-align:center;">
                            <img src="https://citiinfo.com.au/assets/images/logo.png" alt="Citiinfo" style="height:48px; display:block; margin:0 auto 12px;">
                            <h1 style="margin:0; font-size:28px; line-height:1.2; color:#ffffff; font-weight:700;">
                                New Listing Submitted
                            </h1>
                            <p style="margin:10px 0 0; font-size:15px; color:#e0e7ff;">
                                A new business listing has been added and needs review
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:35px 30px;">
                            <p style="margin:0 0 16px; font-size:15px; color:#374151;">
                                Hello Admin,
                            </p>

                            <p style="margin:0 0 20px; font-size:15px; line-height:1.8; color:#4b5563;">
                                A new listing has been submitted on <strong>Citiinfo</strong>. Please review and approve it from the SuperAdmin panel.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:12px;">
                                <tr>
                                    <td style="padding:20px;">
                                        <p style="margin:0 0 10px; font-size:14px; color:#6b7280;">Business Name</p>
                                        <p style="margin:0 0 15px; font-size:16px; color:#111827; font-weight:700;">
                                            {{ $listing->business_name ?? '-' }}
                                        </p>

                                        <p style="margin:0 0 10px; font-size:14px; color:#6b7280;">Listing Status</p>
                                        <p style="margin:0 0 15px; font-size:16px; color:#111827; font-weight:700;">
                                            {{ ucfirst($listing->status ?? 'pending') }}
                                        </p>

                                        <p style="margin:0 0 10px; font-size:14px; color:#6b7280;">Submitted At</p>
                                        <p style="margin:0; font-size:16px; color:#111827; font-weight:700;">
                                            {{ $listing->submitted_at ? \Carbon\Carbon::parse($listing->submitted_at)->format('d M Y h:i A') : now()->format('d M Y h:i A') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:24px 0 0; font-size:15px; color:#4b5563;">
                                Thank you,<br>
                                <strong>Citiinfo System</strong>
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