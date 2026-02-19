<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class QuoteMail extends Mailable
{
    public function __construct(public $data) {}

    public function build()
    {
        return $this->subject('New Quote Request')
            ->view('emails.quote');
    }
}