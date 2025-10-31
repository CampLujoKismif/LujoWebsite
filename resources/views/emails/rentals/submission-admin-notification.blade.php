<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Rental Submission</title>
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
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 50%, #f87171 100%);
            padding: 30px 40px;
            text-align: center;
        }
        .logo-container {
            background: white;
            border-radius: 12px;
            padding: 15px;
            display: inline-block;
            margin-bottom: 15px;
        }
        .email-title {
            color: white;
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }
        .email-subtitle {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            margin: 5px 0 0;
        }
        .email-body {
            padding: 40px;
            color: #374151;
            line-height: 1.6;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e5e7eb;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #f3f4f6;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #4b5563;
            width: 140px;
            flex-shrink: 0;
        }
        .info-value {
            color: #111827;
            flex-grow: 1;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            background: #fef3c7;
            color: #92400e;
        }
        .pricing-box {
            background: #f9fafb;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            text-align: center;
            padding: 10px 0;
        }
        .button-container {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 16px 40px;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            transition: all 0.3s ease;
        }
        .button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
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
        .notes-box {
            background: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo-container">
                <div style="font-size: 32px; font-weight: bold; color: #10b981;">
                    🌲
                </div>
            </div>
            <h1 class="email-title">New Rental Submission</h1>
            <p class="email-subtitle">Camp LUJO-KISMIF</p>
        </div>

        <div class="email-body">
            <div class="section">
                <div class="section-title">Submission Status</div>
                <span class="status-badge">Pending {{ ($submission['payment_method'] ?? 'payment') === 'mail_check' ? 'Check by Mail' : 'Online Payment' }}</span>
            </div>

            <div class="section">
                <div class="section-title">Requested Dates</div>
                <div class="info-row">
                    <span class="info-label">Check-in:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($submission['start_date'])->format('F j, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Check-out:</span>
                    <span class="info-value">{{ \Carbon\Carbon::parse($submission['end_date'])->format('F j, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Guests:</span>
                    <span class="info-value">{{ $submission['number_of_people'] ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Customer Information</div>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $submission['contact_name'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $submission['contact_email'] ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $submission['contact_phone'] ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Purpose</div>
                <div style="color: #6b7280; line-height: 1.6;">
                    {{ $submission['rental_purpose'] ?? 'N/A' }}
                </div>
            </div>

            <div class="section">
                <div class="section-title">Pricing Summary</div>
                <div class="pricing-box">
                    @if(!empty($submission['total_amount']))
                    <div class="info-row">
                        <span class="info-label">Subtotal:</span>
                        <span class="info-value">${{ number_format($submission['total_amount'] ?? 0, 2) }}</span>
                    </div>
                    @endif
                    @if(!empty($submission['discount_amount']) && $submission['discount_amount'] > 0)
                    <div class="info-row">
                        <span class="info-label">Discount:</span>
                        <span class="info-value" style="color: #059669;">-${{ number_format($submission['discount_amount'], 2) }}</span>
                    </div>
                    @endif
                    <div class="total-amount">
                        Total: ${{ number_format($submission['final_amount'] ?? 0, 2) }}
                    </div>
                    @if(!empty($submission['deposit_amount']))
                    <div style="text-align: center; color: #6b7280; font-size: 14px; margin-top: 10px;">
                        Deposit Required: ${{ number_format($submission['deposit_amount'], 2) }}
                    </div>
                    @endif
                </div>
            </div>

            @if(!empty($submission['notes']))
            <div class="section">
                <div class="section-title">Special Notes</div>
                <div class="notes-box">
                    {{ $submission['notes'] }}
                </div>
            </div>
            @endif

            <div class="button-container">
                <a href="{{ config('app.url') }}/dashboard/rental-admin" class="button">
                    View Rental Dashboard
                </a>
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
