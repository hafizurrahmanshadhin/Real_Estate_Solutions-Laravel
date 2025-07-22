<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OtherServiceSeeder extends Seeder {
    public function run(): void {
        DB::table('other_services')->insert([
            [
                'id'                  => 1,
                'service_description' => '<p>Let us handle all your real estate needs and get your listing market ready! We will visit the property, assess everything it needs, and provide you with a quote based on the.services we believe are needed to get it listing-ready—making the process easy and stress-free</p>',
                'title'               => null,
                'image'               => null,
                'description'         => null,
                'status'              => 'active',
                'created_at'          => '2025-07-22 04:36:57',
                'updated_at'          => '2025-07-22 04:38:29',
                'deleted_at'          => null,
            ],
            [
                'id'                  => 2,
                'service_description' => null,
                'title'               => 'Decluttering and Soft Staging',
                'image'               => 'backend/images/seeder/services1.jpg',
                'description'         => '<p>Let us handle all your real estate needs and get your listing market ready! We will visit the property, assess everything it needs, and provide you with a quote based on the.services we believe are needed to get it listing-ready—making the process easy and stress-free</p>',
                'status'              => 'active',
                'created_at'          => '2025-07-22 04:40:03',
                'updated_at'          => '2025-07-22 04:40:03',
                'deleted_at'          => null,
            ],
            [
                'id'                  => 3,
                'service_description' => null,
                'title'               => 'House Cleaning Service',
                'image'               => 'backend/images/seeder/services2.png',
                'description'         => '<p>We offer thorough and reliable house cleaning to make your home shine and get your listing ready to hit the market. We’ll come by to see what’s needed and provide you with a personalized quote —no pressure, just a clean start</p>',
                'status'              => 'active',
                'created_at'          => '2025-07-22 04:42:06',
                'updated_at'          => '2025-07-22 04:42:06',
                'deleted_at'          => null,
            ],
            [
                'id'                  => 4,
                'service_description' => null,
                'title'               => 'Landscape Service',
                'image'               => 'backend/images/seeder/services3.png',
                'description'         => '<p>We’ll help get your yard looking its best to make your listing shine. Each service includes lawn mowing, trimming, edging, and leaf blowing. Just send us your address, and based on the lot size, we’ll provide you with a quote—simple and stress-free!</p>',
                'status'              => 'active',
                'created_at'          => '2025-07-22 04:42:54',
                'updated_at'          => '2025-07-22 04:42:54',
                'deleted_at'          => null,
            ],
            [
                'id'                  => 5,
                'service_description' => null,
                'title'               => 'Professional Staging Service',
                'image'               => 'backend/images/seeder/services4.jpg',
                'description'         => '<p>We’ll help you create a warm, inviting space that makes buyers feel right at home. Our team will visit your house, assess the space, and provide a tailored staging plan along with a clear quote— making the process easy and stress-free!</p>',
                'status'              => 'active',
                'created_at'          => '2025-07-22 04:43:39',
                'updated_at'          => '2025-07-22 04:43:39',
                'deleted_at'          => null,
            ],
            [
                'id'                  => 6,
                'service_description' => null,
                'title'               => 'Bundle Package',
                'image'               => 'backend/images/seeder/services5.jpg',
                'description'         => '<p>Our comprehensive packages are designed to make your property stand out. From professional HDR photography to stunning aerial drone footage, we provide everything you need to attract buyers and sell faster.</p><ul><li><strong>✓High-Quality HDR Photos</strong></li><li><strong>✓Cinematic Video Tours</strong></li><li><strong>✓Aerial Drone Footage</strong></li><li><strong>✓Fast 24-Hour Turnaround</strong></li></ul>',
                'status'              => 'active',
                'created_at'          => '2025-07-22 04:44:43',
                'updated_at'          => '2025-07-22 04:44:43',
                'deleted_at'          => null,
            ],
        ]);
    }
}
