<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class ForgetPassword extends Mailable
{
    use Queueable, SerializesModels;

    private $userId;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $url = URL::temporarySignedRoute(
            'forget', now()->addMinutes(30), ['user' => $this->userId]);
        return $this->view('emails.forget', ["url" => $url]);
    }
}
