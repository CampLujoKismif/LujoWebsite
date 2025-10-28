<x-mail::message>
# Refund Processed for Your Rental Reservation

Dear {{ $reservation->contact_name }},

@if($isFullRefund)
Your rental reservation has been cancelled and a full refund has been processed.
@else
A partial refund has been processed for your rental reservation.
@endif

## Refund Details

**Refund Amount:** ${{ number_format($refundAmount, 2) }}  
**Original Reservation Amount:** ${{ number_format($reservation->final_amount, 2) }}  
**Refund Status:** {{ ucfirst($reservation->payment_status) }}

@if($reservation->payment_method === 'credit_card')
The refund will be returned to your original payment method within 5-10 business days.
@endif

## Original Reservation Details

**Dates:** {{ $reservation->start_date->format('F j, Y') }} to {{ $reservation->end_date->format('F j, Y') }}  
**Number of Days:** {{ $reservation->days }}  
**Number of People:** {{ $reservation->number_of_people }}  
**Purpose:** {{ $reservation->rental_purpose }}

## Contact Information

**Name:** {{ $reservation->contact_name }}  
**Email:** {{ $reservation->contact_email }}  
**Phone:** {{ $reservation->contact_phone }}

If you have any questions about this refund or would like to make a new reservation in the future, please contact us.

<x-mail::button :url="config('app.url')">
Visit Our Website
</x-mail::button>

We hope to see you at Camp LUJO-KISMIF in the future!

Thanks,<br>
Camp LUJO-KISMIF Team
</x-mail::message>
