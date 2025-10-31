<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Rental Reservation Confirmed</title>
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
            background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
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
            width: 160px;
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
            background: #d1fae5;
            color: #065f46;
        }
        .payment-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            background: #dbeafe;
            color: #1e40af;
        }
        .pricing-box {
            background: #f0fdf4;
            border-radius: 8px;
            padding: 20px;
            margin: 15px 0;
            border: 2px solid #d1fae5;
        }
        .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #059669;
            text-align: center;
            padding: 10px 0;
        }
        .balance-amount {
            font-size: 18px;
            font-weight: 600;
            color: #dc2626;
            text-align: center;
            padding: 5px 0;
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
        .discount-box {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .notes-box {
            background: #fff7ed;
            border-left: 4px solid #f97316;
            padding: 15px;
            border-radius: 6px;
            margin: 15px 0;
        }
        .reservation-id {
            font-size: 18px;
            font-weight: bold;
            color: #3b82f6;
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
            <h1 class="email-title">Rental Reservation Confirmed</h1>
            <p class="email-subtitle">Camp LUJO-KISMIF</p>
        </div>

        <div class="email-body">
            <div class="section">
                <div class="section-title">Reservation Information</div>
                <div class="info-row">
                    <span class="info-label">Reservation ID:</span>
                    <span class="info-value reservation-id">#{{ $reservation->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Status:</span>
                    <span class="info-value">
                        <span class="status-badge">{{ ucfirst($reservation->status) }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Check-in:</span>
                    <span class="info-value">{{ $reservation->start_date->format('F j, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Check-out:</span>
                    <span class="info-value">{{ $reservation->end_date->format('F j, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Duration:</span>
                    <span class="info-value">{{ $reservation->days }} day(s)</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Guests:</span>
                    <span class="info-value">{{ $reservation->number_of_people }}</span>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Customer Information</div>
                <div class="info-row">
                    <span class="info-label">Name:</span>
                    <span class="info-value">{{ $reservation->contact_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $reservation->contact_email }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $reservation->contact_phone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Purpose:</span>
                    <span class="info-value">{{ $reservation->rental_purpose }}</span>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Payment Information</div>
                <div class="info-row">
                    <span class="info-label">Payment Status:</span>
                    <span class="info-value">
                        <span class="payment-badge">{{ ucfirst($reservation->payment_status) }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Payment Method:</span>
                    <span class="info-value">{{ ucfirst(str_replace('_', ' ', $reservation->payment_method)) }}</span>
                </div>
                @if($reservation->payment_date)
                <div class="info-row">
                    <span class="info-label">Payment Date:</span>
                    <span class="info-value">{{ $reservation->payment_date->format('F j, Y g:i A') }}</span>
                </div>
                @endif
                @if($reservation->stripe_payment_intent_id)
                <div class="info-row">
                    <span class="info-label">Stripe Intent:</span>
                    <span class="info-value" style="font-family: monospace; font-size: 12px;">{{ $reservation->stripe_payment_intent_id }}</span>
                </div>
                @endif

                <div class="pricing-box">
                    <div class="info-row">
                        <span class="info-label">Total Amount:</span>
                        <span class="info-value">${{ number_format($reservation->final_amount, 2) }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Amount Paid:</span>
                        <span class="info-value">${{ number_format($reservation->amount_paid, 2) }}</span>
                    </div>
                    @if($reservation->remaining_balance > 0)
                    <div class="balance-amount">
                        Remaining Balance: ${{ number_format($reservation->remaining_balance, 2) }}
                    </div>
                    @endif
                </div>
            </div>

            @if($reservation->discount_code_id)
            <div class="section">
                <div class="section-title">Discount Applied</div>
                <div class="discount-box">
                    <strong>Discount Code:</strong> {{ $reservation->discountCode->code ?? 'N/A' }}
                </div>
            </div>
            @endif

            @if($reservation->notes)
            <div class="section">
                <div class="section-title">Special Notes</div>
                <div class="notes-box">
                    {{ $reservation->notes }}
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
