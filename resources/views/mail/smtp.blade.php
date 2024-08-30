@component('mail::message')

Hi <b>{{$from_name}}</b>,


The email account `{{$from_email}}` is working !!!

Regards,<br />
{{ config('app.name') }}.

@endcomponent