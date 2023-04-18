<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DonorInfoUpdate extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Stores the otp
     * @var int
     */
    private $_data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->_data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.donorinfoupdate')
        ->subject(config('general_settings.SEND_DONOR_INFO_UPDATE'))
        ->with([
            'data' => $this->_data
        ]);
    }
}
