<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\OrderPaidInvoice;
use App\Models\Order;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeWebhookController extends Controller {
    /**
     * Handle the incoming Stripe webhook request.
     *
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function __invoke(Request $request): Response {
        Stripe::setApiKey(config('services.stripe.secret'));

        $payload        = $request->getContent();
        $sigHeader      = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $sigHeader, $endpointSecret);
        } catch (Exception $e) {
            return response('Invalid signature', 400);
        }

        // handle checkout.session.completed if it ever comes:
        if ($event->type === 'checkout.session.completed') {
            $session = $event->data->object;
            $orderId = $session->metadata->order_id;
        }
        // also handle the PaymentIntent directly:
        elseif ($event->type === 'payment_intent.succeeded') {
            $pi      = $event->data->object;
            $orderId = $pi->metadata->order_id;
        } else {
            return response('Event ignored', 200);
        }

        // now update the order once
        try {
            if ($orderId && $order = Order::find($orderId)) {
                if ($order->status !== 'paid') {
                    $order->update([
                        'stripe_session_id'     => $event->type === 'checkout.session.completed' ? $session->id : $order->stripe_session_id,
                        'stripe_payment_intent' => $event->type === 'payment_intent.succeeded' ? $pi->id : $order->stripe_payment_intent,
                        'payment_method'        => $event->type === 'payment_intent.succeeded' ? $pi->payment_method : $order->payment_method,
                        'transaction_id'        => $pi->id,
                        'status'                => 'paid',
                    ]);
                }
            }
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'An error occurred', 500, [
                'error' => $e->getMessage(),
            ]);
        }

        // Send invoice email
        try {
            Mail::to($order->email)->send(new OrderPaidInvoice($order));
        } catch (Exception $e) {
            return Helper::jsonResponse(false, 'Failed to send invoice email', 500, [
                'error' => $e->getMessage(),
            ]);
        }

        return response('Webhook handled', 200);
    }
}
