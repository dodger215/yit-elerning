<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meeting Invitation</title>
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
        }
        .content h1 {
            color: #1e293b;
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }
        .content p {
            color: #64748b;
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .details-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        .details-box p {
            margin-bottom: 5px;
            font-size: 14px;
            color: #334155;
        }
        .cta-container {
            text-align: center;
            margin: 30px 0;
        }
        .btn {
            display: inline-block;
            padding: 15px 30px;
            background-color: #1d4ed8;
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
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
            <h1>You're Invited to a Meeting!</h1>
            <p>Hello,</p>
            <p>You have been invited to join an upcoming meeting on the YouthInTech platform.</p>
            
            <div class="details-box">
                <p><strong>Meeting Topic:</strong> {{ $meeting->title }}</p>
                <p><strong>Date & Time:</strong> {{ \Carbon\Carbon::parse($meeting->start_time)->format('F j, Y, g:i a') }}</p>
                @if($meeting->description)
                <p><strong>Description:</strong> {{ $meeting->description }}</p>
                @endif
            </div>

            <p>Please click the button below to join the meeting room when it is time.</p>
            <div class="cta-container">
                <a href="{{ route('meeting.join', $meeting->room_id) }}" class="btn" style="color: #ffffff;">Join Meeting</a>
            </div>
            
            <p>If the button doesn't work, copy and paste this link into your browser:</p>
            <p style="font-size: 12px; word-break: break-all;">{{ route('meeting.join', $meeting->room_id) }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Youth In Tech. All rights reserved.
        </div>
    </div>
</body>
</html>
