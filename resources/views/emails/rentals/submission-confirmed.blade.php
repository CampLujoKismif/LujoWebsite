<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Rental Reservation Submitted</title>
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
              <div class="h1" style="font-size:24px; font-weight:700; margin-top:6px;">Rental Reservation Submitted</div>
            </td>
          </tr>

          <!-- Body -->
          <tr>
            <td class="p-24" style="padding:24px;">
              <p style="margin:0 0 12px 0; font-size:16px;">Dear {{ $submission['contact_name'] ?? 'Valued Customer' }},</p>

              <p style="margin:0 0 16px 0; font-size:16px; line-height:1.6;">
                Thank you for submitting your rental reservation request at <strong>Camp LUJO-KISMIF!</strong>
              </p>

              <!-- Reservation Details -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px;">Your Reservation Details</div>
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151; width:48%;">Requested Dates:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">
                          {{ \Carbon\Carbon::parse($submission['start_date'])->format('F j, Y') }} to {{ \Carbon\Carbon::parse($submission['end_date'])->format('F j, Y') }}
                        </td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Number of Days:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">{{ \Carbon\Carbon::parse($submission['start_date'])->diffInDays(\Carbon\Carbon::parse($submission['end_date'])) + 1 }}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Number of People:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">{{ $submission['number_of_people'] ?? 'N/A' }}</td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Purpose:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">{{ $submission['rental_purpose'] ?? 'N/A' }}</td>
                      </tr>
                    </table>
                  </td>
                </tr>
              </table>

              <!-- Payment Information -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px;">Payment Information</div>
                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Total Amount:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">${{ number_format($submission['total_amount'] ?? 0, 2) }}</td>
                      </tr>
                      @if(!empty($submission['discount_amount']) && $submission['discount_amount'] > 0)
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Discount Applied:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right; color:#059669;">-${{ number_format($submission['discount_amount'], 2) }}</td>
                      </tr>
                      @endif
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Final Amount:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right; font-weight:700;">${{ number_format($submission['final_amount'] ?? 0, 2) }}</td>
                      </tr>
                      @if(!empty($submission['deposit_amount']))
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Deposit Amount:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;">${{ number_format($submission['deposit_amount'], 2) }}</td>
                      </tr>
                      @endif
                    </table>
                  </td>
                </tr>
              </table>

              @if(($submission['payment_method'] ?? '') === 'mail_check')
              <!-- Next Steps - Check Payment -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#fff7ed; border:1px solid #fbbf24; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px; color:#92400e;">Next Steps</div>
                    <p style="margin:0; font-size:14px; line-height:1.6; color:#78350f;">
                      Please send a check for <strong>${{ number_format($submission['final_amount'] ?? 0, 2) }}</strong> to complete your reservation. Once we receive your payment, your reservation will be confirmed.
                    </p>
                    <p style="margin:8px 0 0 0; font-size:14px; color:#78350f;">
                      <strong>Payment Status:</strong> Pending Check Payment
                    </p>
                  </td>
                </tr>
              </table>
              @else
              <!-- Next Steps - Online Payment -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#eff6ff; border:1px solid #3b82f6; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px; color:#1e40af;">Next Steps</div>
                    <p style="margin:0; font-size:14px; line-height:1.6; color:#1e3a8a;">
                      Please complete your payment online to confirm your reservation. You will receive a confirmation email once your payment has been processed.
                    </p>
                    <p style="margin:8px 0 0 0; font-size:14px; color:#1e3a8a;">
                      <strong>Payment Status:</strong> Awaiting Online Payment
                    </p>
                  </td>
                </tr>
              </table>
              @endif

              @if(!empty($submission['notes']))
              <!-- Notes -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px;">Notes</div>
                    <p style="margin:0; font-size:14px; line-height:1.6; color:#374151;">
                      {{ $submission['notes'] }}
                    </p>
                  </td>
                </tr>
              </table>
              @endif

              <p style="margin:16px 0; font-size:15px; line-height:1.6; color:#374151;">
                If you have any questions or need to make changes to your reservation request, please contact us.
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

              <p style="margin:20px 0 0 0; font-size:15px; line-height:1.6; text-align:center;">
                We look forward to hosting you!
              </p>

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
