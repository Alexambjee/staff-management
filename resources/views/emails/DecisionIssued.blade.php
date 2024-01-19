@component('mail::message')
Hi, <br>
<br>
{{ $emaildata['message'] }}

Regards,<br>
{{ $emaildata['username'] }}
<br>
<br>
{{ env('APP_NAME') }}
@endcomponent
