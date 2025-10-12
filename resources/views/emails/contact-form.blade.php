<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Baru dari Contact Form</title>
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
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .content {
            background-color: #ffffff;
            padding: 20px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
        }
        .field {
            margin-bottom: 15px;
        }
        .field-label {
            font-weight: bold;
            color: #495057;
            margin-bottom: 5px;
        }
        .field-value {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            border-left: 4px solid #007bff;
        }
        .message-content {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 4px;
            border-left: 4px solid #28a745;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            font-size: 12px;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 style="margin: 0; color: #007bff;">Pesan Baru dari Contact Form</h1>
        <p style="margin: 10px 0 0 0; color: #6c757d;">
            Diterima pada: {{ now()->format('d F Y, H:i') }} WIB
        </p>
    </div>

    <div class="content">
        <div class="field">
            <div class="field-label">Nama:</div>
            <div class="field-value">{{ $contactData['name'] }}</div>
        </div>

        <div class="field">
            <div class="field-label">Email:</div>
            <div class="field-value">
                <a href="mailto:{{ $contactData['email'] }}">{{ $contactData['email'] }}</a>
            </div>
        </div>

        <div class="field">
            <div class="field-label">Subjek:</div>
            <div class="field-value">{{ $contactData['subject'] }}</div>
        </div>

        <div class="field">
            <div class="field-label">Pesan:</div>
            <div class="message-content">{{ $contactData['message'] }}</div>
        </div>
    </div>

    <div class="footer">
        <p>
            <strong>{{ config('app.name') }}</strong><br>
            Email ini dikirim otomatis dari sistem contact form website.
        </p>
        <p>
            Untuk membalas pesan ini, Anda dapat langsung reply ke email ini atau 
            mengirim email ke: <a href="mailto:{{ $contactData['email'] }}">{{ $contactData['email'] }}</a>
        </p>
    </div>
</body>
</html>