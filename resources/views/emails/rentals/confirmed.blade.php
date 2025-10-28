<x-mail::message>
# Rental Reservation Confirmed

Dear {{ $reservation->contact_name }},

Thank you for your reservation at Camp LUJO-KISMIF! We're excited to host you.

## Reservation Details

**Dates:** {{ $reservation->start_date->format('F j, Y') }} to {{ $reservation->end_date->format('F j, Y') }}  
**Number of Days:** {{ $reservation->days }}  
**Number of People:** {{ $reservation->number_of_people }}  
**Purpose:** {{ $reservation->rental_purpose }}

## Payment Information

**Total Amount:** ${{ number_format($reservation->final_amount, 2) }}  
**Amount Paid:** ${{ number_format($reservation->amount_paid, 2) }}  
@if($reservation->remaining_balance > 0)
**Remaining Balance:** ${{ number_format($reservation->remaining_balance, 2) }}
@endif
**Payment Status:** {{ ucfirst($reservation->payment_status) }}  
**Payment Method:** {{ ucfirst(str_replace('_', ' ', $reservation->payment_method)) }}

## Contact Information

**Name:** {{ $reservation->contact_name }}  
**Email:** {{ $reservation->contact_email }}  
**Phone:** {{ $reservation->contact_phone }}

If you have any questions or need to make changes to your reservation, please contact us.

<x-mail::button :url="config('app.url')">
Visit Our Website
</x-mail::button>

We look forward to seeing you!

Thanks,<br>
Camp LUJO-KISMIF Team
</x-mail::message>
