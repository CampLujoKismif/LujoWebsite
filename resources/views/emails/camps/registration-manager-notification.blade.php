<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New Camp Registration</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; color: #1f2933; background-color: #f9fafb; margin: 0; padding: 0;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color: #f9fafb; padding: 24px 0;">
    <tr>
        <td align="center">
            <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(15, 23, 42, 0.12);">
                <tr>
                    <td style="background-color: #1f2937; padding: 24px 32px; color: #ffffff;">
                        <h1 style="margin: 0; font-size: 20px;">New Registration Submitted</h1>
                        <p style="margin: 8px 0 0; font-size: 15px;">
                            {{ $registration['camp_name'] ?? 'Camp LUJO' }} - {{ $registration['session_name'] ?? 'Camp Session' }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 24px 32px;">
                        <p style="margin: 0 0 16px; line-height: 1.5;">
                            A new registration has been completed for this session. Details are below for your records.
                        </p>

                        <h2 style="font-size: 18px; margin: 0 0 12px;">Registrant</h2>
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-bottom: 16px;">
                            <tr>
                                <td style="padding: 8px 0;"><strong>Name:</strong></td>
                                <td style="padding: 8px 0;">{{ $registration['registrant']['name'] ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <td style="padding: 8px 0;"><strong>Email:</strong></td>
                                <td style="padding: 8px 0;">{{ $registration['registrant']['email'] ?? 'N/A' }}</td>
                            </tr>
                        </table>

                        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin: 24px 0;">
                            <tr>
                                <td style="padding: 12px; background-color: #f3f4f6; border-radius: 6px;">
                                    <strong>Session Dates:</strong>
                                    <div>{{ $registration['session_dates'] ?? 'TBA' }}</div>
                                </td>
                                <td style="padding: 12px; background-color: #f3f4f6; border-radius: 6px; margin-left: 12px;">
                                    <strong>Payment Method:</strong>
                                    <div style="text-transform: capitalize;">{{ str_replace('_', ' ', $registration['payment_method'] ?? 'stripe') }}</div>
                                </td>
                            </tr>
                        </table>

                        <h2 style="font-size: 18px; margin: 0 0 12px;">Campers</h2>
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse; margin-bottom: 24px;">
                            <thead>
                            <tr style="background-color: #111827; color: #ffffff; text-align: left;">
                                <th style="padding: 12px;">Camper</th>
                                <th style="padding: 12px;">Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($registration['campers'] ?? [] as $camper)
                                <tr style="border-bottom: 1px solid #e5e7eb;">
                                    <td style="padding: 12px;">{{ $camper['name'] }}</td>
                                    <td style="padding: 12px; text-transform: capitalize;">{{ str_replace('_', ' ', $camper['status']) }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>

                        <h2 style="font-size: 18px; margin: 0 0 12px;">Financial Summary</h2>
                        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse: collapse;">
                            <tr>
                                <td style="padding: 8px 0;">Subtotal</td>
                                <td style="padding: 8px 0; text-align: right;">
                                    ${{ number_format(($registration['financials']['subtotal_cents'] ?? 0) / 100, 2) }}
                                </td>
                            </tr>
                            @if(($registration['financials']['discount_cents'] ?? 0) > 0)
                                <tr>
                                    <td style="padding: 8px 0; color: #047857;">Discount</td>
                                    <td style="padding: 8px 0; text-align: right; color: #047857;">
                                        -${{ number_format(($registration['financials']['discount_cents'] ?? 0) / 100, 2) }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td style="padding: 8px 0;"><strong>Total Due</strong></td>
                                <td style="padding: 8px 0; text-align: right;">
                                    <strong>${{ number_format(($registration['financials']['balance_cents'] ?? 0) / 100, 2) }}</strong>
                                </td>
                            </tr>
                            @if(($registration['financials']['paid_cents'] ?? 0) > 0)
                                <tr>
                                    <td style="padding: 8px 0;">Amount Paid</td>
                                    <td style="padding: 8px 0; text-align: right;">
                                        ${{ number_format(($registration['financials']['paid_cents'] ?? 0) / 100, 2) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 8px 0;">Outstanding Balance</td>
                                    <td style="padding: 8px 0; text-align: right;">
                                        ${{ number_format(($registration['financials']['outstanding_cents'] ?? 0) / 100, 2) }}
                                    </td>
                                </tr>
                            @endif
                        </table>

                        @if($registration['awaiting_payment'] ?? false)
                            <p style="margin: 24px 0 0; padding: 12px 16px; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px; line-height: 1.5;">
                                This registration is awaiting an on-site cash/check payment at camp check-in.
                            </p>
                        @else
                            <p style="margin: 24px 0 0; padding: 12px 16px; background-color: #dcfce7; border-left: 4px solid #16a34a; border-radius: 6px; line-height: 1.5;">
                                Payment has been completed online via Stripe.
                            </p>
                        @endif

                        <p style="margin: 24px 0 0; line-height: 1.5;">
                            You can review this registration in the dashboard for additional details or updates.
                        </p>

                        <p style="margin: 0; line-height: 1.5;">
                            — Camp LUJO Registration System
                        </p>
                    </td>
                </tr>
                <tr>
                    <td style="background-color: #f3f4f6; color: #6b7280; font-size: 12px; padding: 16px 32px; text-align: center;">
                        © {{ date('Y') }} Camp LUJO-KISMIF. All rights reserved.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>


