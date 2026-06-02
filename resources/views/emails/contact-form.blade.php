<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>New Contact Message - Citiinfo</title>
</head>
<body style="margin:0; padding:0; background:#f4f7fb; font-family:Arial, Helvetica, sans-serif; color:#1f2937;">

    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f4f7fb; padding:30px 15px;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0" border="0"
                    style="max-width:620px; background:#ffffff; border-radius:18px; overflow:hidden; box-shadow:0 8px 30px rgba(0,0,0,0.08);">

                    {{-- Header --}}
                    <tr>
                        <td style="background:linear-gradient(135deg, #0f172a, #2563eb); padding:28px 30px; text-align:center;">
                            <img src="https://citiinfo.com.au/assets/images/logo.png"
                                alt="Citiinfo"
                                style="height:48px; display:block; margin:0 auto 12px;">

                            <h1 style="margin:0; font-size:28px; line-height:1.2; color:#ffffff; font-weight:700;">
                                New Contact Message
                            </h1>

                            <p style="margin:10px 0 0; font-size:15px; color:#dbeafe;">
                                New enquiry received from Citiinfo Contact Form
                            </p>
                        </td>
                    </tr>

                    {{-- Content --}}
                    <tr>
                        <td style="padding:35px 30px;">

                            <p style="margin:0 0 20px; font-size:16px; color:#111827;">
                                Hello Admin,
                            </p>

                            <p style="margin:0 0 20px; font-size:15px; line-height:1.8; color:#4b5563;">
                                A new message has been submitted through the Citiinfo Contact Us page.
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="background:#eff6ff; border:1px solid #bfdbfe; border-radius:12px; margin:20px 0;">

                                <tr>
                                    <td style="padding:20px;">

                                        <table width="100%" cellpadding="8" cellspacing="0" border="0">

                                            <tr>
                                                <td width="140" style="font-weight:700; color:#1d4ed8;">
                                                    Name:
                                                </td>
                                                <td style="color:#374151;">
                                                    {{ $data['name'] }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="font-weight:700; color:#1d4ed8;">
                                                    Email:
                                                </td>
                                                <td style="color:#374151;">
                                                    {{ $data['email'] }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="font-weight:700; color:#1d4ed8;">
                                                    Phone:
                                                </td>
                                                <td style="color:#374151;">
                                                    {{ $data['phone'] ?? 'N/A' }}
                                                </td>
                                            </tr>

                                            <tr>
                                                <td style="font-weight:700; color:#1d4ed8;">
                                                    Subject:
                                                </td>
                                                <td style="color:#374151;">
                                                    {{ $data['subject'] ?? 'N/A' }}
                                                </td>
                                            </tr>

                                        </table>

                                    </td>
                                </tr>
                            </table>

                            <h3 style="margin:25px 0 12px; color:#111827;">
                                Message Details
                            </h3>

                            <div style="
                                background:#f9fafb;
                                border:1px solid #e5e7eb;
                                border-radius:12px;
                                padding:18px;
                                color:#374151;
                                line-height:1.8;
                                font-size:15px;
                            ">
                                {!! nl2br(e($data['message'])) !!}
                            </div>

                            <p style="margin:30px 0 0; font-size:15px; line-height:1.8; color:#4b5563;">
                                Regards,<br>
                                <strong>Citiinfo Contact Form</strong>
                            </p>

                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="padding:20px 30px; background:#f9fafb; border-top:1px solid #e5e7eb; text-align:center;">

                            <p style="margin:0 0 6px; font-size:13px; color:#6b7280;">
                                © {{ date('Y') }} Citiinfo. All rights reserved.
                            </p>

                            <p style="margin:0; font-size:13px; color:#6b7280;">
                                support@citiinfo.com.au
                            </p>

                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>
</html>