<x-mail::message>
# New website enquiry

**Name:** {{ $enquiry->name }}
@if ($enquiry->phone)

**Phone:** {{ $enquiry->phone }}
@endif
@if ($enquiry->email)

**Email:** {{ $enquiry->email }}
@endif
@if ($enquiry->service)

**Service:** {{ $enquiry->service }}
@endif

**Message:**

{{ $enquiry->message }}

<x-mail::button :url="$adminUrl">
Open enquiries
</x-mail::button>

Received {{ $enquiry->created_at->timezone(config('app.timezone'))->format('j M Y, g:i A') }}.
</x-mail::message>
