<?php

namespace App\Services\Api\Payment;

use App\Models\AddOn;
use App\Models\Order;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class CheckoutService {
    /**
     * Creates a Stripe session, persists the order + its related data,
     * and returns the Stripe session URL.
     *
     * @param  array  $payload  validated data from CheckoutRequest
     * @return string  the Stripe Checkout URL
     */
    public function createSession(array $payload): string {
        Stripe::setApiKey(config('services.stripe.secret'));

        return DB::transaction(function () use ($payload) {
            // 1) placeholder order
            $contact = $payload['contact'];
            $order   = Order::create([
                'first_name'               => $contact['first_name'],
                'last_name'                => $contact['last_name'],
                'email'                    => $contact['email'],
                'phone_number'             => $contact['phone_number'],
                'message'                  => $contact['message'] ?? null,
                'is_agreed_privacy_policy' => $contact['is_agreed_privacy_policy'],
                // stripe fields will be completed by webhook
                'stripe_session_id'        => '',
                'stripe_payment_intent'    => '',
                'payment_method'           => '',
                'transaction_id'           => '',
                'total_amount'             => 0,
                'currency'                 => 'usd',
                'status'                   => 'pending',
            ]);

            // 2) property & 3) appointment
            $order->properties()->create($payload['property']);
            $order->appointments()->create($payload['appointment']);

            // 4) items & build line_items for Stripe
            $lineItems = [];
            $total     = 0;
            foreach ($payload['items'] as $row) {
                $model = $row['type'] === 'service' ? Service::class : AddOn::class;
                $order->items()->create([
                    'itemable_type' => $model,
                    'itemable_id'   => $row['id'],
                    'quantity'      => $row['quantity'],
                    'unit_price'    => $row['unit_price'],
                ]);
                $name = $row['type'] === 'service'
                ? Service::find($row['id'])->serviceItems->pluck('service_name')->join(', ')
                : AddOn::find($row['id'])->serviceItem->service_name;
                $lineItems[] = [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => ['name' => $name],
                        'unit_amount'  => intval($row['unit_price'] * 100),
                    ],
                    'quantity'   => $row['quantity'],
                ];
                $total += $row['unit_price'] * $row['quantity'];
            }

            // 5) Stripe session
            $session = StripeSession::create([
                'payment_method_types' => ['card'],
                'line_items'           => $lineItems,
                'mode'                 => 'payment',
                'customer_email'       => $order->email,
                'metadata'             => ['order_id' => $order->id],
                'payment_intent_data'  => ['metadata' => ['order_id' => $order->id]],
                'success_url'          => $payload['success_url'],
                'cancel_url'           => $payload['cancel_url'],
            ]);

            // 6) update order with session & total
            $order->update([
                'stripe_session_id' => $session->id,
                'total_amount'      => $total,
            ]);

            return $session->url;
        });
    }
}
