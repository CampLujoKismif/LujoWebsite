<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Payment Request</title>
  <style>
    @media (max-width: 640px) {
      .container { width: 100% !important; }
      .p-24 { padding: 16px !important; }
      .h1 { font-size: 22px !important; }
    }
  </style>
</head>
<body style="margin:0; padding:0; background-color:#f5f7fb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, 'Helvetica Neue', sans-serif; color:#111827;">

  <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f5f7fb; padding:24px 0;">
    <tr>
      <td align="center">

        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" class="container" style="width:600px; max-width:600px; background:#ffffff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden;">
          <!-- Header -->
          <tr>
            <td style="background:#0ea5e9; color:#ffffff; padding:20px 24px; text-align:center;">
              <div style="font-size:14px; letter-spacing:0.1em; text-transform:uppercase;">Camp LUJO-KISMIF</div>
              <div class="h1" style="font-size:24px; font-weight:700; margin-top:6px;">Payment Request for Your Rental Reservation</div>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td class="p-24" style="padding:24px;">
              <p style="margin:0 0 12px 0; font-size:16px;">Dear {{ $reservation->contact_name }},</p>

              <p style="margin:0 0 16px 0; font-size:16px; line-height:1.6;">
                Thank you for your reservation at <strong>Camp LUJO-KISMIF!</strong> We're looking forward to hosting you.
              </p>

              <p style="margin:0 0 16px 0; font-size:16px; line-height:1.6;">
                This is a friendly reminder that payment is due for your upcoming rental reservation. Please review the payment details below and complete your payment at your earliest convenience.
              </p>

              <!-- Reservation Details -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px;">Reservation Details</div>
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151; width:48%;">Dates:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">
                          {{ $reservation->start_date->format('F j, Y') }} to {{ $reservation->end_date->format('F j, Y') }}
                        </td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Number of Days:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">{{ $reservation->days }}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Number of People:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">{{ $reservation->number_of_people }}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Purpose:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">{{ $reservation->rental_purpose }}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- Payment Information -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#fff7ed; border:2px solid #f59e0b; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px; color:#92400e;">Payment Due</div>
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Total Amount:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right; font-weight:700;">${{ number_format($reservation->final_amount, 2) }}</td>
                      </tr>
                      @if($reservation->amount_paid > 0)
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Amount Paid:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">${{ number_format($reservation->amount_paid, 2) }}</td>
                      </tr>
                      @endif
                      @if($reservation->remaining_balance > 0)
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151; font-weight:700;">Remaining Balance:</td>
                        <td style="padding:8px 0; font-size:16px; text-align:right; font-weight:700; color:#dc2626;">${{ number_format($reservation->remaining_balance, 2) }}</td>
                      </tr>
                      @else
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151; font-weight:700;">Amount Due:</td>
                        <td style="padding:8px 0; font-size:16px; text-align:right; font-weight:700; color:#dc2626;">${{ number_format($reservation->final_amount, 2) }}</td>
                      </tr>
                      @endif
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Payment Status:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">{{ ucfirst($reservation->payment_status) }}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- Payment Instructions -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px;">Payment Instructions</div>
                    <p style="margin:8px 0; font-size:14px; line-height:1.6; color:#374151;">
                      You can pay online securely using a credit card by clicking the button below, or send your payment via check. Make checks payable to <strong>Camp LUJO-KISMIF</strong>.
                    </p>
                    <p style="margin:8px 0; font-size:14px; line-height:1.6; color:#374151;">
                      If you have any questions about your reservation or payment, please don't hesitate to contact us.
                    </p>
                  </td>
                </tr>
              </table>

              <!-- Payment Button -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 20px auto 4px auto;">
                <tr>
                  <td align="center" bgcolor="#10b981" style="border-radius:8px;">
                    <a href="{{ route('rentals.pay', $reservation->id) }}" target="_blank"
                       style="display:inline-block; padding:14px 28px; font-size:16px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:8px;">
                      Pay Online with Credit Card
                    </a>
                  </td>
                </tr>
              </table>

              <!-- Contact Information -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px;">Contact Information</div>
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151; width:48%;">Name:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">{{ $reservation->contact_name }}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Email:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">
                          <a href="mailto:{{ $reservation->contact_email }}" style="color:#0ea5e9; text-decoration:none;">{{ $reservation->contact_email }}</a>
                        </td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Phone:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">
                          <a href="tel:{{ preg_replace('/[^0-9\+]/', '', $reservation->contact_phone) }}" style="color:#0ea5e9; text-decoration:none;">
                            {{ $reservation->contact_phone }}
                          </a>
                        </td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <p style="margin:16px 0; font-size:15px; line-height:1.6; color:#374151;">
                We appreciate your prompt attention to this matter and look forward to hosting you at Camp LUJO-KISMIF!
              </p>

              <!-- Button -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 12px auto 4px auto;">
                <tr>
                  <td align="center" bgcolor="#0ea5e9" style="border-radius:8px;">
                    <a href="{{ config('app.url') }}" target="_blank"
                       style="display:inline-block; padding:12px 20px; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:8px;">
                      Visit Our Website
                    </a>
                  </td>
                </tr>
              </table>

              <p style="margin:20px 0 0 0; font-size:15px; line-height:1.6;">
                Thanks,<br />
                <strong>Camp LUJO-KISMIF Team</strong>
              </p>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="background:#f3f4f6; color:#6b7280; padding:16px 24px; font-size:12px; text-align:center;">
              <div>© {{ now()->format('Y') }} Camp LUJO-KISMIF. All rights reserved.</div>
              <div style="margin-top:6px;">
                <a href="{{ config('app.url') }}" style="color:#6b7280; text-decoration:underline;">Website</a>
                &nbsp;•&nbsp;
                <a href="mailto:{{ $supportEmail ?? 'info@example.com' }}" style="color:#6b7280; text-decoration:underline;">Email us</a>
              </div>
            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>

</body>
</html>

