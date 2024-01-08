<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InfoServiceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $request;
    public $subjet;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request_)
    {
        $this->request = $request_;
        $this->subjet = "Solicitud de información en servicio";
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.info_service');
    }
}
