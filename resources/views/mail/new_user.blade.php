@component('mail::message')

Hi <b>{{$username}}</b>,

Thanks for signing up for {{ config('app.name') }}.

To finish your registration click the button below 

@component('mail::button', ['url' => $link])
    Join {{ config('app.name') }} site!
@endcomponent

Regards,<br />
{{ config('app.name') }}.

@endcomponent