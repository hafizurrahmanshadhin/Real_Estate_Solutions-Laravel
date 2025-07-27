<?php

namespace Database\Seeders;

use App\Models\OtherServiceOrder;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OtherServiceOrderSeeder extends Seeder {
    public function run(): void {
        // Clear existing data
        OtherServiceOrder::truncate();

        $orders = [
            [
                'id'                => 1,
                'first_name'        => 'Christopher',
                'last_name'         => 'Morrison',
                'phone_number'      => '+13543985529',
                'email'             => 'christopher.morrison293@gmail.com',
                'other_services_id' => 3,
                'additional_info'   => 'Hi there! Looking for a reliable partner for property marketing services.',
                'address'           => '123 Main St',
                'city'              => 'Riverside',
                'state'             => 'Ny',
                'zip_code'          => '10001',
                'footage_size_id'   => 4,
                'status'            => 'active',
                'created_at'        => Carbon::parse('2025-07-26 22:42:09'),
                'updated_at'        => Carbon::parse('2025-07-26 22:42:09'),
                'deleted_at'        => null,
            ],
            [
                'id'                => 2,
                'first_name'        => 'Elizabeth',
                'last_name'         => 'Turner',
                'phone_number'      => '+13012678680',
                'email'             => 'elizabeth.turner378@gmail.com',
                'other_services_id' => 3,
                'additional_info'   => 'Hello! I\'m a real estate professional seeking your photography and staging services.',
                'address'           => '987 Cedar Way',
                'city'              => 'Fairview',
                'state'             => 'Ny',
                'zip_code'          => '90210',
                'footage_size_id'   => 4,
                'status'            => 'active',
                'created_at'        => Carbon::parse('2025-07-26 22:42:10'),
                'updated_at'        => Carbon::parse('2025-07-26 22:42:10'),
                'deleted_at'        => null,
            ],
            [
                'id'                => 3,
                'first_name'        => 'Margaret',
                'last_name'         => 'Henderson',
                'phone_number'      => '+15616824531',
                'email'             => 'margaret.henderson514@business.com',
                'other_services_id' => 3,
                'additional_info'   => 'Good morning! I represent property owners and need your drone & photo packages.',
                'address'           => '654 Maple Court',
                'city'              => 'Madison',
                'state'             => 'Ny',
                'zip_code'          => '90210',
                'footage_size_id'   => 4,
                'status'            => 'active',
                'created_at'        => Carbon::parse('2025-07-26 22:42:11'),
                'updated_at'        => Carbon::parse('2025-07-26 22:42:11'),
                'deleted_at'        => null,
            ],
        ];

        // Insert each order
        foreach ($orders as $orderData) {
            OtherServiceOrder::create($orderData);
        }

        $this->command->info('✅ Other Service Orders seeded successfully!');
        $this->command->info('📊 Total orders created: ' . count($orders));
    }
}
