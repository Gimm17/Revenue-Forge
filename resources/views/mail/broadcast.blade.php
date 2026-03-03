<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #1a1a2e;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 40px 20px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 32px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        .body-content {
            font-size: 15px;
            color: #333;
            white-space: pre-line;
        }
        .footer {
            text-align: center;
            margin-top: 24px;
            font-size: 12px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="body-content">{!! nl2br(e($body)) !!}</div>
        </div>
        <div class="footer">
            <p>Sent by {{ $senderName }} via RevenueForge</p>
        </div>
    </div>
</body>
</html>
