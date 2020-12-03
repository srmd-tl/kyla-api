<?php

namespace App\Mail;

use App\Models\KylaProcess;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendReport extends Mailable
{
    use Queueable, SerializesModels;

    public $kylaProcess;

    /**
     *
     *
     * @param KylaProcess $kylaProcess
     */
    public function __construct(KylaProcess $kylaProcess)
    {
        $this->kylaProcess = $kylaProcess;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('kylaProcess.report', ["kylaProcess" => $this->kylaProcess]);
    }
}
