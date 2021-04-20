@component('mail::message')
# Introduction

You can change your password by click below button.

@component('mail::button', ['url' => 'http://192.168.0.48:8000/api/password-reset/'])
Change Password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
