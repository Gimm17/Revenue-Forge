<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Receipt</title>
</head>
<body style="margin:0;padding:0;background-color:#0a0a0f;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;">
    <table cellpadding="0" cellspacing="0" width="100%" style="background-color:#0a0a0f;padding:40px 20px;">
        <tr><td align="center">
            <table cellpadding="0" cellspacing="0" width="560" style="max-width:560px;">
                <tr><td style="padding-bottom:32px;text-align:center;">
                    <span style="font-size:24px;font-weight:700;color:#ffffff;">⚡ RevenueForge</span>
                </td></tr>
                <tr><td style="background:linear-gradient(135deg,rgba(255,255,255,0.06),rgba(255,255,255,0.02));border:1px solid rgba(255,255,255,0.08);border-radius:16px;padding:40px;">
                    <div style="text-align:center;margin-bottom:24px;">
                        <span style="display:inline-block;width:48px;height:48px;line-height:48px;font-size:24px;background:rgba(16,185,129,0.15);border-radius:12px;">✅</span>
                    </div>
                    <h1 style="margin:0 0 8px;font-size:22px;font-weight:700;color:#ffffff;text-align:center;">Payment Confirmed!</h1>
                    <p style="margin:0 0 24px;font-size:14px;color:#9ca3af;text-align:center;">
                        Thank you for your purchase. Here is your receipt:
                    </p>

                    <!-- Receipt Card -->
                    <table cellpadding="0" cellspacing="0" width="100%" style="background:rgba(255,255,255,0.03);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:20px;margin-bottom:20px;">
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:#6b7280;">Order ID</td>
                            <td style="padding:8px 0;font-size:13px;color:#ffffff;text-align:right;font-family:monospace;">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;font-size:13px;color:#6b7280;">Product</td>
                            <td style="padding:8px 0;font-size:13px;color:#ffffff;text-align:right;font-weight:500;">{{ $order->offer?->title ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.06);font-size:13px;color:#6b7280;">Date</td>
                            <td style="padding:8px 0;border-top:1px solid rgba(255,255,255,0.06);font-size:13px;color:#ffffff;text-align:right;">{{ $order->paid_at?->format('d M Y, H:i') ?? now()->format('d M Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" style="padding-top:12px;border-top:2px solid rgba(255,255,255,0.08);"></td>
                        </tr>
                        <tr>
                            <td style="padding:4px 0;font-size:16px;color:#ffffff;font-weight:700;">Total Paid</td>
                            <td style="padding:4px 0;font-size:16px;color:#10b981;text-align:right;font-weight:700;">{{ $order->formattedAmount() }}</td>
                        </tr>
                    </table>

                    <p style="margin:0;font-size:13px;color:#6b7280;text-align:center;line-height:1.5;">
                        If you have any questions about this purchase, please contact the seller directly.
                    </p>
                </td></tr>
                <tr><td style="padding-top:24px;text-align:center;">
                    <p style="margin:0;font-size:12px;color:#4b5563;">© {{ date('Y') }} RevenueForge. Powered by Mayar.</p>
                </td></tr>
            </table>
        </td></tr>
    </table>
</body>
</html>
