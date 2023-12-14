@component('mail::message')
# Introduction
If you forgots password, Let's touch button below !

@component('mail::button', ['url' => 'http://localhost:8000/response-password-reset?token='.$token])
Reset Password Link
@endcomponent

Thanks,<br>
Tấn Đặng Laravel
@endcomponent
