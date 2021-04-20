@component('mail::message')
# Welcome
Please confirm your email address for register with us {{$token}}.<br>

@component('mail::button', ["url" => "http://192.168.0.48:8000/api/verify-email/$token", "color" => "green"])

 
Please verifiy
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
