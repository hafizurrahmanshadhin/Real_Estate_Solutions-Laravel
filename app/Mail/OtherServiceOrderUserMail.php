<?php

namespace App\Mail;

use App\Models\OtherServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtherServiceOrderUserMail extends Mailable {
    use Queueable, SerializesModels;

    public $order;

    public function __construct(OtherServiceOrder $order) {
        $this->order = $order;
    }

    public function build() {
        return $this->subject('Your Service Order Confirmation')
            ->view('emails.other_service_order_user');
    }
}
