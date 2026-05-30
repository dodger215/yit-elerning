<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #ffffff;
            padding: 30px;
            text-align: center;
            border-bottom: 1px solid #e2e8f0;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 40px;
            text-align: center;
        }
        .content h1 {
            color: #1e293b;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .content p {
            color: #64748b;
            font-size: 16px;
            line-height: 1.6;
        }
        .otp-code {
            display: inline-block;
            margin: 30px 0;
            padding: 15px 30px;
            background-color: #1d4ed8;
            color: #ffffff;
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            border-radius: 8px;
        }
        .footer {
            background-color: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/logo.png')) }}" alt="Youth In Tech Logo">
        </div>
        <div class="content">
            <h1>Verification Code</h1>
            <p>Use the following code to complete your login or registration process. This code is valid for 15 minutes.</p>
            <div class="otp-code">{{ $code }}</div>
            <p>If you didn't request this code, please ignore this email.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Youth In Tech. All rights reserved.
        </div>
    </div>
</body>
</html>
