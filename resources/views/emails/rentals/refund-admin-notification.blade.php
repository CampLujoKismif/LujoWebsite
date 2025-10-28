<x-mail::message>
# Rental Reservation Refund Processed

@if($isFullRefund)
A full refund has been processed for a rental reservation.
@else
A partial refund has been processed for a rental reservation.
@endif

## Refund Information

**Refund Amount:** ${{ number_format($refundAmount, 2) }}  
**Original Amount:** ${{ number_format($reservation->final_amount, 2) }}  
**Refund Type:** {{ $isFullRefund ? 'Full Refund' : 'Partial Refund' }}  
**New Payment Status:** {{ ucfirst($reservation->payment_status) }}  
@if($processedBy)
**Processed By:** {{ $processedBy }}  
@endif
**Processed At:** {{ now()->format('F j, Y g:i A') }}

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

## Payment Details

**Payment Method:** {{ ucfirst(str_replace('_', ' ', $reservation->payment_method)) }}  
**Original Payment Date:** {{ $reservation->payment_date?->format('F j, Y g:i A') ?? 'N/A' }}  
@if($reservation->stripe_payment_intent_id)
**Stripe Payment Intent:** {{ $reservation->stripe_payment_intent_id }}
@endif

@if($reservation->discount_code_id)
## Discount Information

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
