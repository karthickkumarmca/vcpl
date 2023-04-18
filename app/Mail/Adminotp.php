<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Adminotp extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Stores the otp
     * @var int
     */
    private $_otp;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(int $otp)
    {
        $this->_otp = $otp;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.otp')
        ->subject(config('general_settings.SEND_OTP_SUBJECT_PORTAL'))
        ->with([
            'otp' => $this->_otp
        ]);
    }
}
