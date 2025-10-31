<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .email-header {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #60a5fa 100%);
            padding: 40px 40px 60px;
            text-align: center;
        }
        .logo-container {
            background: white;
            border-radius: 12px;
            padding: 20px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .email-title {
            color: white;
            font-size: 28px;
            font-weight: bold;
            margin: 0 0 10px;
        }
        .email-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 16px;
            margin: 0;
        }
        .email-body {
            padding: 40px;
            color: #374151;
            line-height: 1.6;
        }
        .email-greeting {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 20px;
        }
        .email-message {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 30px;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .verify-button {
            display: inline-block;
            padding: 16px 40px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }
        .verify-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .email-footer {
            background: #f9fafb;
            padding: 30px 40px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .email-footer-text {
            font-size: 14px;
            color: #6b7280;
            margin: 0;
        }
        .alternative-link {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .alternative-link-text {
            font-size: 13px;
            color: #9ca3af;
        }
        .alternative-link-url {
            color: #3b82f6;
            word-break: break-all;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo-container">
                <!-- Camp Logo -->
                <div style="font-size: 32px; font-weight: bold; color: #10b981;">
                    🌲
                </div>
            </div>
            <h1 class="email-title">Camp LUJO-KISMIF</h1>
            <p class="email-subtitle">Verify Your Email Address</p>
        </div>

        <div class="email-body">
            <div class="email-greeting">
                Hello {{ $user->name }}!
            </div>

            <div class="email-message">
                <p>Thank you for registering with Camp LUJO-KISMIF! We're excited to have you join our community.</p>
                
                <p>To complete your registration and ensure the security of your account, please verify your email address by clicking the button below.</p>
            </div>

            <div class="button-container">
                <a href="{{ $verificationUrl }}" class="verify-button">
                    Verify Email Address
                </a>
            </div>

            <div class="alternative-link">
                <p class="alternative-link-text">
                    If you're having trouble clicking the button, copy and paste this URL into your browser:
                </p>
                <p class="alternative-link-url">{{ $verificationUrl }}</p>
            </div>

            <div class="email-message" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <p style="font-size: 14px; color: #9ca3af;">
                    This verification link will expire in 60 minutes. If you did not create an account with Camp LUJO-KISMIF, please ignore this email.
                </p>
            </div>
        </div>

        <div class="email-footer">
            <p class="email-footer-text">
                © 2024 Camp LUJO-KISMIF. All rights reserved.<br>
                Keep It Spiritual, Make It Fun!
            </p>
        </div>
    </div>
</body>
</html>

