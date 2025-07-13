<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackageSeeder extends Seeder {
    public function run(): void {
        $packages = [
            [
                'id'          => 1,
                'title'       => 'Essential Package',
                'image'       => 'uploads/packages/kyra-dejesus_1752301087.png',
                'name'        => 'HDR Image Package',
                'description' => '<p>High-quality interior and exterior HDR photos to showcase your listing with great detail and vibrant lighting</p>',
                'is_popular'  => 0,
                'status'      => 'active',
                'created_at'  => '2025-07-12 00:18:07',
                'updated_at'  => '2025-07-12 00:18:07',
            ],
            [
                'id'          => 2,
                'title'       => 'Premium Package',
                'image'       => 'uploads/packages/fritz-harding_1752396751.png',
                'name'        => 'HDR + Aerial Photography',
                'description' => "<p>Includes stunning HDR images plus aerial drone shots to highlight the property's exterior, lot size, and surrounding views.</p>",
                'is_popular'  => 0,
                'status'      => 'active',
                'created_at'  => '2025-07-13 02:52:31',
                'updated_at'  => '2025-07-13 02:52:31',
            ],
            [
                'id'          => 3,
                'title'       => 'Elite Package',
                'image'       => 'uploads/packages/uriel-maxwell_1752396760.png',
                'name'        => 'HDR + Aerial Photos + Vide Combo (Walkthrough + Aerial)',
                'description' => '<p>All-in-one media package with HDR photos, aerial photography, and a dynamic video combining a walkthrough and aerial drone footage.</p>',
                'is_popular'  => 1,
                'status'      => 'active',
                'created_at'  => '2025-07-13 02:52:40',
                'updated_at'  => '2025-07-13 02:52:40',
            ],
        ];

        DB::table('packages')->insert($packages);
    }
}
