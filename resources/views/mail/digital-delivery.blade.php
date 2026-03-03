<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #0a0b10; color: #e5e7eb; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 0 auto; padding: 40px 20px; }
        .card { background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 32px; }
        .logo { text-align: center; margin-bottom: 24px; }
        .logo span { font-size: 20px; font-weight: 700; color: #fff; }
        h1 { color: #fff; font-size: 22px; margin: 0 0 8px; }
        .subtitle { color: #9ca3af; font-size: 14px; margin-bottom: 24px; }
        .delivery-box { background: rgba(6,182,212,0.1); border: 1px solid rgba(6,182,212,0.2); border-radius: 12px; padding: 20px; margin: 20px 0; }
        .delivery-label { color: #22d3ee; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px; }
        .delivery-content { color: #fff; font-size: 14px; line-height: 1.6; }
        .btn { display: inline-block; background: linear-gradient(to right, #06b6d4, #8b5cf6); color: #fff; text-decoration: none; padding: 12px 28px; border-radius: 10px; font-weight: 600; font-size: 14px; margin-top: 16px; }
        .order-info { border-top: 1px solid rgba(255,255,255,0.06); margin-top: 24px; padding-top: 16px; }
        .info-row { display: flex; justify-content: space-between; padding: 4px 0; }
        .info-label { color: #9ca3af; font-size: 13px; }
        .info-value { color: #fff; font-size: 13px; font-weight: 500; }
        .footer { text-align: center; color: #6b7280; font-size: 11px; margin-top: 32px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="logo">
                <span>⚡ RevenueForge</span>
            </div>

            <h1>Your purchase is ready! 🎉</h1>
            <p class="subtitle">Thank you for purchasing <strong style="color:#fff;">{{ $order->offer?->title }}</strong></p>

            @if($order->offer?->delivery_type === 'file_url')
                <div class="delivery-box">
                    <div class="delivery-label">{{ $order->offer->delivery_label ?? '📦 Your Download' }}</div>
                    <div class="delivery-content">
                        <a href="{{ $order->offer->delivery_content }}" class="btn" target="_blank">
                            Download Now →
                        </a>
                    </div>
                </div>
            @elseif($order->offer?->delivery_type === 'redirect_url')
                <div class="delivery-box">
                    <div class="delivery-label">{{ $order->offer->delivery_label ?? '🔗 Access Link' }}</div>
                    <div class="delivery-content">
                        <a href="{{ $order->offer->delivery_content }}" class="btn" target="_blank">
                            Access Now →
                        </a>
                    </div>
                </div>
            @elseif($order->offer?->delivery_type === 'text_content')
                <div class="delivery-box">
                    <div class="delivery-label">{{ $order->offer->delivery_label ?? '📋 Your Content' }}</div>
                    <div class="delivery-content">
                        {!! nl2br(e($order->offer->delivery_content)) !!}
                    </div>
                </div>
            @endif

            <div class="order-info">
                <table width="100%" style="border-collapse:collapse;">
                    <tr>
                        <td class="info-label" style="padding:4px 0;">Order ID</td>
                        <td class="info-value" style="padding:4px 0; text-align:right;">#{{ $order->id }}</td>
                    </tr>
                    <tr>
                        <td class="info-label" style="padding:4px 0;">Amount</td>
                        <td class="info-value" style="padding:4px 0; text-align:right;">{{ $order->formattedAmount() }}</td>
                    </tr>
                    <tr>
                        <td class="info-label" style="padding:4px 0;">Date</td>
                        <td class="info-value" style="padding:4px 0; text-align:right;">{{ $order->paid_at?->format('d M Y, H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} RevenueForge. All rights reserved.
        </div>
    </div>
</body>
</html>
