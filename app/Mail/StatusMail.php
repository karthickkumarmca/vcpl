<?php

namespace App\Mail;

use App\Models\Applications;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class StatusMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Stores the otp
     * @var int
     */
    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->view('mails.statusmail');
        if($this->data['status']==1111){
            $getQOrder = Applications::generateapproval($this->data['qrcode']);
            if ($getQOrder) {
                $getQOrder = file_get_contents($getQOrder);
                $mail->attachData($getQOrder, 'approval-certificate.png');
            // unlink($getQOrder);
            }
        }
        $status =  $this->data['statusname'];
        if($this->data['fifa_status']==5){
            $status = 'Complimentary';
        }
        if($this->data['payment_status_value']==1){
            $status = 'Payment received';
        }
        return $mail->subject(config('general_settings.SEND_STATUS_SUBJECT').' - '.$this->data['qrcode'].' is '.$status )->with(['data' => $this->data]);
    }
}
