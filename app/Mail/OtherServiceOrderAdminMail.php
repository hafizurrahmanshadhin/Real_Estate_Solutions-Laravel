<?php

namespace App\Mail;

use App\Models\OtherServiceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtherServiceOrderAdminMail extends Mailable {
    use Queueable, SerializesModels;

    public $order;

    public function __construct(OtherServiceOrder $order) {
        $this->order = $order;
    }

    public function build() {
        return $this->subject('New Service Order Received')
            ->view('emails.other_service_order_admin');
    }
}
