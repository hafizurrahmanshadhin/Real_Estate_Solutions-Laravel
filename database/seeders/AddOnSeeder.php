<?php

namespace Database\Seeders;

use App\Models\AddOn;
use Illuminate\Database\Seeder;

class AddOnSeeder extends Seeder {
    public function run(): void {
        $addOns = [
            [
                'id'              => 1,
                'footage_size_id' => 1,
                'service_item_id' => 1,
                'locations'       => null,
                'quantity'        => 5,
                'price'           => 20.00,
                'status'          => 'active',
                'created_at'      => '2025-07-19 02:45:35',
                'updated_at'      => '2025-07-19 02:45:35',
                'deleted_at'      => null,
            ],
            [
                'id'              => 2,
                'footage_size_id' => 1,
                'service_item_id' => 2,
                'locations'       => null,
                'quantity'        => 1,
                'price'           => 5.00,
                'status'          => 'active',
                'created_at'      => '2025-07-19 02:45:59',
                'updated_at'      => '2025-07-19 02:45:59',
                'deleted_at'      => null,
            ],
            [
                'id'              => 3,
                'footage_size_id' => 1,
                'service_item_id' => 4,
                'locations'       => null,
                'quantity'        => 1,
                'price'           => 25.00,
                'status'          => 'active',
                'created_at'      => '2025-07-19 02:46:19',
                'updated_at'      => '2025-07-19 02:46:19',
                'deleted_at'      => null,
            ],
            [
                'id'              => 4,
                'footage_size_id' => 1,
                'service_item_id' => 5,
                'locations'       => null,
                'quantity'        => 1,
                'price'           => 35.00,
                'status'          => 'active',
                'created_at'      => '2025-07-19 02:46:40',
                'updated_at'      => '2025-07-19 02:46:40',
                'deleted_at'      => null,
            ],
            [
                'id'              => 5,
                'footage_size_id' => 1,
                'service_item_id' => 6,
                'locations'       => null,
                'quantity'        => 1,
                'price'           => 119.00,
                'status'          => 'active',
                'created_at'      => '2025-07-19 02:46:57',
                'updated_at'      => '2025-07-19 02:46:57',
                'deleted_at'      => null,
            ],
            [
                'id'              => 6,
                'footage_size_id' => 1,
                'service_item_id' => 7,
                'locations'       => 1,
                'quantity'        => 2,
                'price'           => 19.00,
                'status'          => 'active',
                'created_at'      => '2025-07-19 02:47:41',
                'updated_at'      => '2025-07-19 02:47:41',
                'deleted_at'      => null,
            ],
            [
                'id'              => 7,
                'footage_size_id' => 1,
                'service_item_id' => 7,
                'locations'       => 2,
                'quantity'        => 5,
                'price'           => 49.00,
                'status'          => 'active',
                'created_at'      => '2025-07-19 02:48:05',
                'updated_at'      => '2025-07-19 02:48:05',
                'deleted_at'      => null,
            ],
        ];

        // Clear existing data
        AddOn::truncate();

        // Insert seed data
        foreach ($addOns as $addOn) {
            AddOn::create($addOn);
        }

        $this->command->info('✅ AddOn seeder completed successfully!');
        $this->command->info('📊 Created ' . count($addOns) . ' add-on records');
        $this->command->info('🏢 Including 2 Community Image packages (1 & 2 locations)');
    }
}
