<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ChangePasswordMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;

    public $digits;

    public function __construct($digits, $subject)
    {
        $this->digits = $digits;
        $this->subject = $subject;
    }

    public function build()
    {
        return $this->view('emails.change_password');
    }
}
