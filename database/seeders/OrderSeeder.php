<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder {
    public function run(): void {
        Order::insert([
            [
                'id'                       => 1,
                'first_name'               => 'Hafizur Rahman',
                'last_name'                => 'Shadhin',
                'email'                    => 'shadhin666@gmail.com',
                'phone_number'             => '+8801719922812',
                'message'                  => 'Please prepare the property for listing.',
                'is_agreed_privacy_policy' => 1,
                'stripe_session_id'        => 'cs_test_b1NHa6k0TOYMgGdKvxj4CrlvmxsoODmvVkSmsqm8lR8XznS9ozHjCR3mDI',
                'stripe_payment_intent'    => 'pi_3Rq6jyRrSTkHftfa0YnrULBD',
                'payment_method'           => 'pm_1Rq6jyRrSTkHftfakNaGMVIm',
                'transaction_id'           => 'pi_3Rq6jyRrSTkHftfa0YnrULBD',
                'total_amount'             => 249.00,
                'currency'                 => 'usd',
                'status'                   => 'paid',
                'order_status'             => 'pending',
                'created_at'               => '2025-07-29 00:09:47',
                'updated_at'               => '2025-07-29 00:10:24',
                'deleted_at'               => null,
            ],
            [
                'id'                       => 2,
                'first_name'               => 'Jane',
                'last_name'                => 'Doe',
                'email'                    => 'janedoe@gmail.com',
                'phone_number'             => '+15551234567',
                'message'                  => 'Please prepare the property for listing.',
                'is_agreed_privacy_policy' => 1,
                'stripe_session_id'        => 'cs_test_b1ol6f8f1gY9pO0UU7yUtThLfJBaUsFSdhxQ0gSuEw9HfEbdBGnJXWR3Bi',
                'stripe_payment_intent'    => '',
                'payment_method'           => '',
                'transaction_id'           => '',
                'total_amount'             => 249.00,
                'currency'                 => 'usd',
                'status'                   => 'pending',
                'order_status'             => 'pending',
                'created_at'               => '2025-07-29 00:11:20',
                'updated_at'               => '2025-07-29 00:11:21',
                'deleted_at'               => null,
            ],
        ]);
    }
}
