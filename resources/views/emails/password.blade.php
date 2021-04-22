@component('mail::message')
# Introduction

if you want to change your password please click on the below button first

@component('mail::button', ["url" => "http://192.168.0.48:8000/api/password-reset/$id", "color" => "green"])

Change Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
