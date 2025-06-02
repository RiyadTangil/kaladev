@component('mail::message')
# {{ $title }}

Hello,

{!! nl2br(e($message)) !!}

Thanks,<br>
{{ config('app.name') }}
@endcomponent
