<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Seeder;

class OrderItemSeeder extends Seeder {
    public function run(): void {
        OrderItem::insert([
            [
                'id'            => 1,
                'order_id'      => 1,
                'itemable_type' => 'App\Models\Service',
                'itemable_id'   => 3,
                'quantity'      => 1,
                'unit_price'    => 229.00,
                'created_at'    => '2025-07-29 00:09:47',
                'updated_at'    => '2025-07-29 00:09:47',
                'deleted_at'    => null,
            ],
            [
                'id'            => 2,
                'order_id'      => 1,
                'itemable_type' => 'App\Models\AddOn',
                'itemable_id'   => 2,
                'quantity'      => 1,
                'unit_price'    => 20.00,
                'created_at'    => '2025-07-29 00:09:47',
                'updated_at'    => '2025-07-29 00:09:47',
                'deleted_at'    => null,
            ],
            [
                'id'            => 3,
                'order_id'      => 2,
                'itemable_type' => 'App\Models\Service',
                'itemable_id'   => 3,
                'quantity'      => 1,
                'unit_price'    => 229.00,
                'created_at'    => '2025-07-29 00:11:20',
                'updated_at'    => '2025-07-29 00:11:20',
                'deleted_at'    => null,
            ],
            [
                'id'            => 4,
                'order_id'      => 2,
                'itemable_type' => 'App\Models\AddOn',
                'itemable_id'   => 2,
                'quantity'      => 1,
                'unit_price'    => 20.00,
                'created_at'    => '2025-07-29 00:11:20',
                'updated_at'    => '2025-07-29 00:11:20',
                'deleted_at'    => null,
            ],
        ]);
    }
}
