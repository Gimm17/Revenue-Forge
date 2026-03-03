<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to RevenueForge</title>
</head>
<body style="margin:0;padding:0;background-color:#0a0a0f;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <table cellpadding="0" cellspacing="0" width="100%" style="background-color:#0a0a0f;padding:40px 20px;">
        <tr><td align="center">
            <table cellpadding="0" cellspacing="0" width="560" style="max-width:560px;">
                <!-- Logo -->
                <tr><td style="padding-bottom:32px;text-align:center;">
                    <span style="font-size:24px;font-weight:700;color:#ffffff;">⚡ RevenueForge</span>
                </td></tr>
                <!-- Card -->
                <tr><td style="background:linear-gradient(135deg,rgba(255,255,255,0.06),rgba(255,255,255,0.02));border:1px solid rgba(255,255,255,0.08);border-radius:16px;padding:40px;">
                    <h1 style="margin:0 0 8px;font-size:24px;font-weight:700;color:#ffffff;">Welcome aboard, {{ $user->name }}! 🚀</h1>
                    <p style="margin:0 0 24px;font-size:15px;color:#9ca3af;line-height:1.6;">
                        Your RevenueForge account is ready. You can now create AI-powered offers, track revenue, and start monetizing.
                    </p>

                    <table cellpadding="0" cellspacing="0" width="100%" style="margin-bottom:24px;">
                        <tr>
                            <td style="padding:12px 16px;background:rgba(6,182,212,0.08);border:1px solid rgba(6,182,212,0.15);border-radius:8px;">
                                <p style="margin:0 0 2px;font-size:11px;color:#06b6d4;text-transform:uppercase;letter-spacing:1px;font-weight:600;">Your Login Email</p>
                                <p style="margin:0;font-size:15px;color:#ffffff;font-weight:500;">{{ $user->email }}</p>
                            </td>
                        </tr>
                    </table>

                    <h3 style="margin:0 0 12px;font-size:14px;color:#ffffff;font-weight:600;">Quick Start Guide:</h3>
                    <table cellpadding="0" cellspacing="0" width="100%">
                        <tr><td style="padding:6px 0;font-size:14px;color:#9ca3af;">1️⃣ Create your first offer with AI Studio</td></tr>
                        <tr><td style="padding:6px 0;font-size:14px;color:#9ca3af;">2️⃣ Publish & share your offer link</td></tr>
                        <tr><td style="padding:6px 0;font-size:14px;color:#9ca3af;">3️⃣ Connect Mayar for live payments</td></tr>
                        <tr><td style="padding:6px 0;font-size:14px;color:#9ca3af;">4️⃣ Track revenue on your dashboard</td></tr>
                    </table>

                    <table cellpadding="0" cellspacing="0" style="margin-top:28px;"><tr><td>
                        <a href="{{ url('/app') }}" style="display:inline-block;padding:12px 32px;background:linear-gradient(135deg,#06b6d4,#8b5cf6);color:#ffffff;font-size:14px;font-weight:600;text-decoration:none;border-radius:10px;">
                            Go to Dashboard →
                        </a>
                    </td></tr></table>
                </td></tr>
                <!-- Footer -->
                <tr><td style="padding-top:24px;text-align:center;">
                    <p style="margin:0;font-size:12px;color:#4b5563;">© {{ date('Y') }} RevenueForge. All rights reserved.</p>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
