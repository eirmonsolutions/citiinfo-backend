<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reply to your message</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Reply from {{ $enquiry->listing->business_name ?? 'CitiInfo' }}</h2>

    <p>Hi {{ $enquiry->name }},</p>

    <p>You sent a message regarding <strong>{{ $enquiry->listing->business_name ?? 'a listing' }}</strong>:</p>

    <blockquote style="border-left: 4px solid #ddd; padding-left: 12px; color: #555;">
        {{ $enquiry->message ?: '—' }}
    </blockquote>

    <p><strong>Reply:</strong></p>
    <blockquote style="border-left: 4px solid #0d6efd; padding-left: 12px; color: #222;">
        {{ $enquiry->admin_reply }}
    </blockquote>

    @if($enquiry->replier)
    <p style="color:#666;font-size:14px;">
        — {{ $enquiry->replier->name }}
    </p>
    @endif

    <p style="margin-top:24px;font-size:14px;color:#666;">
        You can also view this reply in your CitiInfo account under <strong>My Messages</strong>.
    </p>
</body>
</html>
