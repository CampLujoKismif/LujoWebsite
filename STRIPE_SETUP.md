# Stripe Setup for Camp Registration System

This document explains how to set up Stripe payments for the camp registration system.

## Prerequisites

1. A Stripe account (sign up at https://stripe.com)
2. Laravel Cashier already installed and configured

## Environment Variables

Add the following environment variables to your `.env` file:

```env
# Stripe Configuration
STRIPE_KEY=pk_test_your_publishable_key_here
STRIPE_SECRET=sk_test_your_secret_key_here
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret_here

# Cashier Configuration
CASHIER_CURRENCY=usd
CASHIER_CURRENCY_LOCALE=en
CASHIER_PATH=stripe
```

## Stripe Dashboard Setup

### 1. Get Your API Keys

1. Log in to your Stripe Dashboard
2. Go to Developers > API keys
3. Copy your Publishable key and Secret key
4. Add them to your `.env` file

### 2. Set Up Webhooks

1. Go to Developers > Webhooks in your Stripe Dashboard
2. Click "Add endpoint"
3. Set the endpoint URL to: `https://yourdomain.com/stripe/webhook`
4. Select the following events:
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
   - `payment_intent.canceled`
   - `charge.succeeded`
   - `charge.failed`
   - `charge.refunded`
5. Copy the webhook signing secret and add it to your `.env` file

### 3. Test Mode vs Live Mode

- Use test keys for development and testing
- Switch to live keys for production
- Test cards are available in the Stripe Dashboard under Developers > Testing

## Payment Flow

### 1. Parent Registration Process

1. Parent registers campers for camps
2. Parent fills out required forms
3. Parent goes to payment processing page
4. Parent can choose:
   - **Pay Now**: Process payment immediately via Stripe
   - **Pay at Check-in**: Mark enrollment as awaiting payment

### 2. Payment Statuses

- `pending`: Initial enrollment status
- `registered_awaiting_payment`: Forms complete, payment pending
- `registered_fully_paid`: Forms complete, payment received

### 3. Payment Processing

- Stripe Elements integration for secure card input
- Real-time validation and error handling
- Webhook processing for payment status updates
- Automatic enrollment status updates

## Testing

### Test Cards

Use these test card numbers:

- **Success**: `4242424242424242`
- **Decline**: `4000000000000002`
- **Insufficient funds**: `4000000000009995`
- **Expired card**: `4000000000000069`

### Test Scenarios

1. **Successful Payment**:
   - Use card `4242424242424242`
   - Any future expiry date
   - Any 3-digit CVC

2. **Failed Payment**:
   - Use card `4000000000000002`
   - Should show decline message

3. **Pay at Check-in**:
   - Select "Pay at Check-in" option
   - Should mark enrollment as awaiting payment

## Security Considerations

1. **Never expose secret keys** in client-side code
2. **Always verify webhook signatures** (handled by Cashier)
3. **Use HTTPS** in production
4. **Implement proper error handling** for failed payments
5. **Log all payment events** for audit trails

## Troubleshooting

### Common Issues

1. **Webhook not receiving events**:
   - Check webhook endpoint URL
   - Verify webhook secret
   - Check server logs

2. **Payment processing errors**:
   - Verify Stripe keys are correct
   - Check Stripe Dashboard for error details
   - Review application logs

3. **Enrollment status not updating**:
   - Check webhook event processing
   - Verify database migrations ran
   - Check payment service logic

### Debug Mode

Enable debug logging by adding to your `.env`:

```env
CASHIER_LOGGER=stripe
```

## Production Deployment

1. **Switch to live keys**:
   - Update `STRIPE_KEY` and `STRIPE_SECRET` to live keys
   - Update webhook endpoint to production URL
   - Update webhook secret

2. **SSL Certificate**:
   - Ensure HTTPS is enabled
   - Valid SSL certificate required

3. **Monitoring**:
   - Set up Stripe Dashboard alerts
   - Monitor application logs
   - Set up payment failure notifications

## Support

- Stripe Documentation: https://stripe.com/docs
- Laravel Cashier Documentation: https://laravel.com/docs/cashier
- Application Logs: Check `storage/logs/laravel.log`
