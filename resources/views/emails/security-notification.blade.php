<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #f8f9fa; padding: 20px; border-radius: 8px 8px 0 0; text-align: center; }
        .content { background: white; padding: 30px; border: 1px solid #e9ecef; }
        .footer { background: #f8f9fa; padding: 20px; border-radius: 0 0 8px 8px; text-align: center; font-size: 12px; color: #6c757d; }
        .alert { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 4px; margin: 20px 0; }
        .btn { display: inline-block; padding: 12px 24px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin: 10px 0; }
        .details { background: #f8f9fa; padding: 15px; border-radius: 4px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('app.name') }}</h1>
            <h2>Security Notification</h2>
        </div>
        
        <div class="content">
            <h3>Hello {{ $user->name }},</h3>
            
            <div class="alert">
                <strong>{{ $subject }}</strong>
            </div>
            
            <p>{{ $message }}</p>
            
            <div class="details">
                <h4>Event Details:</h4>
                <ul>
                    <li><strong>Time:</strong> {{ $time }}</li>
                    <li><strong>IP Address:</strong> {{ $ip }}</li>
                    <li><strong>Device:</strong> {{ $userAgent }}</li>
                </ul>
            </div>
            
            <p>If this wasn't you, please secure your account immediately by changing your password and enabling two-factor authentication.</p>
            
            <a href="{{ route('admin.security.index') }}" class="btn">Review Security Settings</a>
            
            <p>If you have any concerns about your account security, please contact our support team immediately.</p>
        </div>
        
        <div class="footer">
            <p>This is an automated security notification from {{ config('app.name') }}.</p>
            <p>Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>