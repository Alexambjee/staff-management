@component('mail::message')

<br>
{{ $maildata['subject'] }}
<br>
<br>
Dear, Taxpayer, <br>
<br>
{{ $maildata['body'] }}
<br>
<br>
Regards,<br>
@endcomponent
