<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceItemSeeder extends Seeder {
    public function run(): void {
        DB::table('service_items')->insert([
            [
                'id'           => 1,
                'service_name' => 'HDR Images',
                'status'       => 'active',
                'created_at'   => Carbon::parse('2025-07-14 02:37:21'),
                'updated_at'   => Carbon::parse('2025-07-14 02:55:14'),
            ],
            [
                'id'           => 2,
                'service_name' => 'Aerial Drone',
                'status'       => 'active',
                'created_at'   => Carbon::parse('2025-07-14 02:37:28'),
                'updated_at'   => Carbon::parse('2025-07-14 02:55:04'),
            ],
            [
                'id'           => 3,
                'service_name' => 'Vide Combo',
                'status'       => 'active',
                'created_at'   => Carbon::parse('2025-07-14 02:55:23'),
                'updated_at'   => Carbon::parse('2025-07-14 02:55:23'),
            ],
            [
                'id'           => 4,
                'service_name' => 'Virtual Twilight',
                'status'       => 'active',
                'created_at'   => Carbon::parse('2025-07-14 02:55:23'),
                'updated_at'   => Carbon::parse('2025-07-14 02:55:23'),
            ],
            [
                'id'           => 5,
                'service_name' => 'Virtual Staging',
                'status'       => 'active',
                'created_at'   => Carbon::parse('2025-07-14 02:55:23'),
                'updated_at'   => Carbon::parse('2025-07-14 02:55:23'),
            ],
            [
                'id'           => 6,
                'service_name' => 'Zillow 3D Tour',
                'status'       => 'active',
                'created_at'   => Carbon::parse('2025-07-14 02:55:23'),
                'updated_at'   => Carbon::parse('2025-07-14 02:55:23'),
            ],
            [
                'id'           => 7,
                'service_name' => 'Community Image',
                'status'       => 'active',
                'created_at'   => Carbon::parse('2025-07-14 02:55:23'),
                'updated_at'   => Carbon::parse('2025-07-14 02:55:23'),
            ],
        ]);
    }
}
