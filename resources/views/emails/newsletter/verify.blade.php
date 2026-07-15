<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirm Your Subscription</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f8f9fa;
            color: #333333;
            margin: 0;
            padding: 40px 20px;
        }
        .container {
            max-width: 600px;
            background: #ffffff;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            overflow: hidden;
            border: 1px solid #eaeaea;
        }
        .header {
            background-color: #1a1a1a;
            padding: 30px;
            text-align: center;
            color: #ffffff;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            letter-spacing: 1px;
            font-weight: 300;
        }
        .content {
            padding: 40px 30px;
            line-height: 1.6;
        }
        .content p {
            margin-bottom: 20px;
        }
        .cta-btn {
            display: inline-block;
            background-color: #b19356;
            color: #ffffff !important;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 4px;
            font-weight: 600;
            margin: 20px 0;
            letter-spacing: 0.5px;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ strtoupper($siteSetting->site_name ?? 'Francena Decors') }}</h1>
        </div>
        <div class="content">
            <p>Hello {{ $name }},</p>
            <p>Thank you for subscribing to our newsletter! Please click the button below to verify your email address and activate your subscription.</p>
            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="cta-btn">Confirm Subscription</a>
            </div>
            <p>If you did not make this request, you can safely ignore this email.</p>
            <p>Warm regards,<br>The {{ $siteSetting->site_name ?? 'Francena Decors' }} Team</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ $siteSetting->site_name ?? 'Francena Decors' }}. All rights reserved.
        </div>
    </div>
</body>
</html>
