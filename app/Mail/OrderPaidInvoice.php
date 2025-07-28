<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderPaidInvoice extends Mailable {
    use Queueable, SerializesModels;

    public Order $order;

    public function __construct(Order $order) {
        $this->order = $order;
    }

    public function build() {
        return $this->subject("Your Invoice ##{$this->order->id}")
            ->view('emails.invoice');
    }
}
