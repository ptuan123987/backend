<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $title;
    public $email;
    public $name;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($title, $email,$name)
    {
        $this->title = $title;
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {  // customer_mail is the name of template
        return $this->subject($this->title)->markdown('customer_mail')->with([
            'email' => $this->email,
            'name' => $this->name,
        ]);
    }
}
