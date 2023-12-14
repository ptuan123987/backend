@component('mail::message')
dear {{ $customer_details['name'] }} <br/>
		Thank you registering <br/>
		email : {{ $customer_details['email'] }}

    Regards,<br/>
    Tấn Đặng Udemy

@endcomponent
