<?php

namespace Database\Seeders;

use App\Models\Property;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder {
    public function run(): void {
        Property::insert([
            [
                'id'              => 1,
                'order_id'        => 1,
                'address'         => 'Bashbari, Mohammadpur',
                'city'            => 'Dhaka',
                'state'           => 'Dhaka',
                'zip_code'        => '1207',
                'property_type'   => 'Single Family',
                'footage_size_id' => 1,
                'created_at'      => '2025-07-29 00:09:47',
                'updated_at'      => '2025-07-29 00:09:47',
                'deleted_at'      => null,
            ],
            [
                'id'              => 2,
                'order_id'        => 2,
                'address'         => '123 Main St',
                'city'            => 'Springfield',
                'state'           => 'Il',
                'zip_code'        => '62704',
                'property_type'   => 'Single Family',
                'footage_size_id' => 1,
                'created_at'      => '2025-07-29 00:11:20',
                'updated_at'      => '2025-07-29 00:11:20',
                'deleted_at'      => null,
            ],
        ]);
    }
}
