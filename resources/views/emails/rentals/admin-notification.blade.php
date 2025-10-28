<x-mail::message>
# New Rental Reservation Confirmed

A new rental reservation has been confirmed and payment has been received.

## Reservation Details

**Reservation ID:** #{{ $reservation->id }}  
**Status:** {{ ucfirst($reservation->status) }}  
**Dates:** {{ $reservation->start_date->format('F j, Y') }} to {{ $reservation->end_date->format('F j, Y') }}  
**Number of Days:** {{ $reservation->days }}  
**Number of People:** {{ $reservation->number_of_people }}

## Customer Information

**Name:** {{ $reservation->contact_name }}  
**Email:** {{ $reservation->contact_email }}  
**Phone:** {{ $reservation->contact_phone }}  
**Purpose:** {{ $reservation->rental_purpose }}

## Payment Information

**Total Amount:** ${{ number_format($reservation->final_amount, 2) }}  
**Amount Paid:** ${{ number_format($reservation->amount_paid, 2) }}  
@if($reservation->remaining_balance > 0)
**Remaining Balance:** ${{ number_format($reservation->remaining_balance, 2) }}
@endif
**Payment Status:** {{ ucfirst($reservation->payment_status) }}  
**Payment Method:** {{ ucfirst(str_replace('_', ' ', $reservation->payment_method)) }}  
**Payment Date:** {{ $reservation->payment_date?->format('F j, Y g:i A') }}  
@if($reservation->stripe_payment_intent_id)
**Stripe Payment Intent:** {{ $reservation->stripe_payment_intent_id }}
@endif

@if($reservation->discount_code_id)
## Discount Applied

**Discount Code:** {{ $reservation->discountCode->code ?? 'N/A' }}
@endif

@if($reservation->notes)
## Notes

{{ $reservation->notes }}
@endif

<x-mail::button :url="config('app.url') . '/dashboard/rental-admin'">
View Rental Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
