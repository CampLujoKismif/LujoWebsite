<x-mail::message>
# New Rental Submission

A new rental request has been submitted.

## Submission Details

**Status:** Pending {{ ($submission['payment_method'] ?? 'payment') === 'mail_check' ? 'check by mail' : 'online payment' }}

**Requested Dates:** {{ \Carbon\Carbon::parse($submission['start_date'])->format('F j, Y') }} to {{ \Carbon\Carbon::parse($submission['end_date'])->format('F j, Y') }}  
**Number of People:** {{ $submission['number_of_people'] ?? 'N/A' }}

## Contact Information

**Name:** {{ $submission['contact_name'] ?? 'N/A' }}  
**Email:** {{ $submission['contact_email'] ?? 'N/A' }}  
**Phone:** {{ $submission['contact_phone'] ?? 'N/A' }}

## Purpose

{{ $submission['rental_purpose'] ?? 'N/A' }}

## Pricing

**Total Amount:** ${{ number_format($submission['total_amount'] ?? 0, 2) }}  
**Discount Amount:** ${{ number_format($submission['discount_amount'] ?? 0, 2) }}  
**Final Amount:** ${{ number_format($submission['final_amount'] ?? 0, 2) }}  
@if(!empty($submission['deposit_amount']))
**Deposit Amount:** ${{ number_format($submission['deposit_amount'], 2) }}
@endif

@if(!empty($submission['notes']))
## Notes

{{ $submission['notes'] }}
@endif

Thanks,  
{{ config('app.name') }}
</x-mail::message>


