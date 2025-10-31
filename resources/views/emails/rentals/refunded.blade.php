<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Refund Processed</title>
  <style>
    /* Some clients respect <style>; all critical styles are also inlined */
    @media (max-width: 640px) {
      .container { width: 100% !important; }
      .p-24 { padding: 16px !important; }
      .h1 { font-size: 22px !important; }
    }
  </style>
</head>
<body style="margin:0; padding:0; background-color:#f5f7fb; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, 'Helvetica Neue', sans-serif; color:#111827;">

  <!-- Outer wrapper -->
  <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background-color:#f5f7fb; padding:24px 0;">
    <tr>
      <td align="center">

        <!-- Container -->
        <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="600" class="container" style="width:600px; max-width:600px; background:#ffffff; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.06); overflow:hidden;">
          <!-- Header -->
          <tr>
            <td style="background:#0ea5e9; color:#ffffff; padding:20px 24px; text-align:center;">
              <div style="font-size:14px; letter-spacing:0.1em; text-transform:uppercase;">Camp LUJO-KISMIF</div>
              <div class="h1" style="font-size:24px; font-weight:700; margin-top:6px;">Refund Processed for Your Rental Reservation</div>
            </td>
          </tr>

          <!-- Greeting + Summary -->
          <tr>
            <td class="p-24" style="padding:24px;">
              <p style="margin:0 0 12px 0; font-size:16px;">Dear {{ $reservation->contact_name }},</p>

              @if ($isFullRefund)
                <p style="margin:0 0 16px 0; font-size:16px; line-height:1.5;">
                  Your rental reservation has been cancelled and a <strong>full refund</strong> has been processed.
                </p>
              @else
                <p style="margin:0 0 16px 0; font-size:16px; line-height:1.5;">
                  A <strong>partial refund</strong> has been processed for your rental reservation.
                </p>
              @endif

              <!-- Refund Details Card -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px;">Refund Details</div>

                    <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%">
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151; width:48%;">Refund Amount:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;"><strong>${{ number_format($refundAmount, 2) }}</strong></td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Original Reservation Amount:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;"><strong>${{ number_format($reservation->final_amount, 2) }}</strong></td>
                      </tr>
                      <tr>
                        <td style="padding:8px 0; font-size:14px; color:#374151;">Refund Status:</td>
                        <td style="padding:8px 0; font-size:14px; text-align:right;"><strong>{{ ucfirst($reservation->payment_status) }}</strong></td>
                      </tr>
                    </table>

                    @if ($reservation->payment_method === 'credit_card')
                      <p style="margin:12px 0 0 0; font-size:13px; color:#6b7280;">
                        The refund will be returned to your original payment method within <strong>5–10 business days</strong>.
                      </p>
                    @endif
                  </td>
                </tr>
              </table>

              <!-- Original Reservation Details Card -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" width="100%" style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:10px; margin:16px 0;">
                <tr>
                  <td style="padding:16px 18px;">
                    <div style="font-weight:700; font-size:16px; margin-bottom:8px;">Original Reservation Details</div>
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

              <!-- Contact Information Card -->
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
                          <a href="mailto:{{ $reservation->contact_email }}" style="color:#0ea5e9; text-decoration:none;">
                            {{ $reservation->contact_email }}
                          </a>
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
                If you have any questions about this refund or would like to make a new reservation in the future, please contact us.
              </p>

              <!-- CTA Button -->
              <table role="presentation" cellpadding="0" cellspacing="0" border="0" align="center" style="margin: 12px auto 4px auto;">
                <tr>
                  <td align="center" bgcolor="#0ea5e9" style="border-radius:8px;">
                    <a href="{{ $websiteUrl ?? config('app.url') }}" target="_blank"
                       style="display:inline-block; padding:12px 20px; font-size:15px; font-weight:700; color:#ffffff; text-decoration:none; border-radius:8px;">
                      Visit Our Website
                    </a>
                  </td>
                </tr>
              </table>

              <p style="margin:16px 0 0 0; font-size:15px; line-height:1.6; color:#374151; text-align:center;">
                We hope to see you at Camp LUJO-KISMIF in the future!
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
                <a href="{{ $websiteUrl ?? config('app.url') }}" style="color:#6b7280; text-decoration:underline;">Website</a>
                &nbsp;•&nbsp;
                <a href="mailto:{{ $supportEmail ?? 'info@example.com' }}" style="color:#6b7280; text-decoration:underline;">Email us</a>
              </div>
            </td>
          </tr>
        </table>
        <!-- /Container -->

      </td>
    </tr>
  </table>
  <!-- /Outer wrapper -->
</body>
</html>
