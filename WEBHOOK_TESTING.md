# Webhook Testing Guide

## Quick Start

### 1. Install Stripe CLI

**macOS:**
```bash
brew install stripe/stripe-cli/stripe
```

**Linux/WSL:**
```bash
curl -s https://packages.stripe.com/api/v1/public-key.gpg | sudo apt-key add -
echo "deb https://packages.stripe.com/stripe-cli/ stable main" | sudo tee /etc/apt/sources.list.d/stripe.list
sudo apt update
sudo apt install stripe
```

### 2. Login to Stripe

```bash
stripe login
```

This will open your browser to authenticate with Stripe.

### 3. Start Local Development Server

```bash
sail up -d
sail npm run dev
```

### 4. Forward Webhooks to Local Server

```bash
stripe listen --forward-to localhost/api/webhooks/stripe
```

**Important:** Copy the webhook signing secret from the output (starts with `whsec_`) and add it to your `.env`:

```env
STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxxxxxxxxxxx
```

## Testing Scenarios

### Test Successful Payment

```bash
# Trigger a successful payment event
stripe trigger payment_intent.succeeded
```

### Test Failed Payment

```bash
# Trigger a failed payment event
stripe trigger payment_intent.payment_failed
```

### Test Refund

```bash
# Trigger a refund event
stripe trigger charge.refunded
```

### Test with Real Payment Flow

1. Go to your rentals page: `http://localhost/rentals`
2. Select dates
3. Fill out the form
4. Choose "Credit/Debit Card"
5. Use test card: `4242 4242 4242 4242`
6. Complete payment
7. Watch the Stripe CLI output for webhook events
8. Check your application logs for processing details

## Monitoring Webhooks

### View Webhook Events in Stripe CLI

The `stripe listen` command will show all webhook events in real-time:

```
2025-10-28 14:23:45  --> payment_intent.succeeded [evt_xxxxx]
2025-10-28 14:23:45  <-- [200] POST http://localhost/api/webhooks/stripe [evt_xxxxx]
```

### Check Application Logs

```bash
# Follow Laravel logs in real-time
sail logs -f

# Or view log file directly
tail -f storage/logs/laravel.log
```

### Check Database

```bash
# Check reservation status
sail artisan tinker
>>> RentalReservation::latest()->first()
```

## Common Test Events

| Event | Command | What it Tests |
|-------|---------|---------------|
| Payment succeeded | `stripe trigger payment_intent.succeeded` | Successful payment processing |
| Payment failed | `stripe trigger payment_intent.payment_failed` | Payment failure handling |
| Payment canceled | `stripe trigger payment_intent.canceled` | Cancellation handling |
| Refund issued | `stripe trigger charge.refunded` | Refund processing |
| Dispute created | `stripe trigger charge.dispute.created` | Dispute notification |

## Webhook Event Flow

### Successful Payment Flow

1. **User Action**: Customer completes payment in browser
2. **Stripe API**: Payment confirmed, returns success to frontend
3. **Frontend**: Shows success message
4. **Webhook Event**: Stripe sends `payment_intent.succeeded` to your server
5. **Backend**: Processes webhook, updates reservation status
6. **Database**: Reservation marked as paid and confirmed
7. **Logs**: Event logged for audit trail

### What the Webhook Handler Does

```
1. Receives webhook from Stripe
2. Verifies webhook signature (security)
3. Identifies event type
4. Finds reservation by payment intent ID
5. Updates reservation:
   - payment_status: 'paid'
   - status: 'confirmed'
   - payment_date: now()
   - amount_paid: payment amount
6. Logs the event
7. Returns 200 OK to Stripe
```

## Troubleshooting

### Webhook Not Receiving Events

**Check 1: Is Stripe CLI running?**
```bash
# Should show webhook forwarding
ps aux | grep stripe
```

**Check 2: Is the endpoint correct?**
- URL should be: `localhost/api/webhooks/stripe`
- NOT: `localhost:8000/api/webhooks/stripe`

**Check 3: Is the webhook secret set?**
```bash
# Check .env file
grep STRIPE_WEBHOOK_SECRET .env
```

### Signature Verification Failed

**Problem**: Webhook secret mismatch

**Solution**:
1. Stop `stripe listen`
2. Restart it: `stripe listen --forward-to localhost/api/webhooks/stripe`
3. Copy the NEW webhook secret
4. Update `.env` with the new secret
5. Restart your server: `sail restart`

### Reservation Not Updating

**Check 1: Is reservation ID in payment metadata?**
```bash
# Check Stripe Dashboard > Payments > Select payment > Metadata
# Should show: reservation_id: 123
```

**Check 2: Check application logs**
```bash
tail -f storage/logs/laravel.log | grep webhook
```

**Check 3: Verify database**
```bash
sail artisan tinker
>>> RentalReservation::find(123)
```

## Production Setup

### When deploying to production:

1. **Create webhook endpoint in Stripe Dashboard**:
   - URL: `https://yourdomain.com/api/webhooks/stripe`
   - Events: Select the same events you're testing

2. **Get production webhook secret**:
   - Copy from Stripe Dashboard
   - Add to production `.env`

3. **Test production webhook**:
   ```bash
   # Send test event to production
   stripe trigger payment_intent.succeeded --stripe-account acct_xxxxx
   ```

4. **Monitor in Stripe Dashboard**:
   - Go to Developers > Webhooks
   - Click on your endpoint
   - View recent deliveries and responses

## Best Practices

✅ **Always verify signatures**: The webhook handler automatically does this
✅ **Use idempotency**: Stripe may send duplicate events
✅ **Return 200 quickly**: Process async if needed
✅ **Log everything**: All events are logged for debugging
✅ **Handle failures gracefully**: Stripe will retry failed webhooks
✅ **Test before deploying**: Use Stripe CLI to test locally

## Quick Command Reference

```bash
# Start webhook forwarding
stripe listen --forward-to localhost/api/webhooks/stripe

# Trigger test events
stripe trigger payment_intent.succeeded
stripe trigger payment_intent.payment_failed
stripe trigger charge.refunded

# View webhook logs
stripe logs tail

# View recent events
stripe events list --limit 10

# Test specific event
stripe trigger payment_intent.succeeded --override payment_intent:metadata[reservation_id]=123

# Check webhook endpoints
stripe webhook_endpoints list
```

## Support

- **Stripe Documentation**: https://stripe.com/docs/webhooks
- **Stripe CLI Docs**: https://stripe.com/docs/stripe-cli
- **Testing Guide**: https://stripe.com/docs/testing
- **Webhook Best Practices**: https://stripe.com/docs/webhooks/best-practices

