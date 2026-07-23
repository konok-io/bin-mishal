<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Newsletter Subscription</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a87 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-top: none;
        }
        .button {
            display: inline-block;
            background: #1e3a5f;
            color: white;
            padding: 15px 40px;
            text-decoration: none;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .button:hover {
            background: #2d5a87;
        }
        .highlight {
            background: #f0f7ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
            border-top: 1px solid #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 Verify Your Subscription</h1>
    </div>
    <div class="content">
        <p>Hello {{ $subscriber->name ?: 'Valued Subscriber' }},</p>
        
        <p>Thank you for subscribing to our newsletter! Please click the button below to verify your email address:</p>
        
        <div style="text-align: center;">
            <a href="{{ $verifyUrl }}" class="button">Verify Email Address</a>
        </div>
        
        <div class="highlight">
            <p><strong>What you'll get:</strong></p>
            <ul>
                <li>✈️ Exclusive travel deals and offers</li>
                <li>📰 Latest travel news and tips</li>
                <li>🎁 Special promotions for subscribers</li>
            </ul>
        </div>
        
        <p>Or copy and paste this link into your browser:</p>
        <p style="word-break: break-all; color: #666; font-size: 14px;">{{ $verifyUrl }}</p>
        
        <p><strong>Important:</strong> This verification link will expire in 24 hours.</p>
        
        <p>If you didn't subscribe to our newsletter, please ignore this email.</p>
        
        <p>Safe travels,<br><strong>The {{ $siteName }} Team</strong></p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} {{ $siteName }}. All rights reserved.</p>
        <p>
            <a href="{{ url('/') }}">Visit Website</a> | 
            <a href="{{ $verifyUrl }}">Unsubscribe</a>
        </p>
    </div>
</body>
</html>
