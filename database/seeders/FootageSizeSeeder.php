<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FootageSizeSeeder extends Seeder {
    public function run(): void {
        DB::table('footage_sizes')->insert([
            [
                'id'         => 1,
                'size'       => '0-2000',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-16 02:57:39'),
                'updated_at' => Carbon::parse('2025-07-16 02:57:39'),
            ],
            [
                'id'         => 2,
                'size'       => '2001-3500',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-16 02:58:00'),
                'updated_at' => Carbon::parse('2025-07-16 02:58:00'),
            ],
            [
                'id'         => 3,
                'size'       => '3501-5000',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-16 02:58:12'),
                'updated_at' => Carbon::parse('2025-07-16 02:58:12'),
            ],
            [
                'id'         => 4,
                'size'       => '5001-6000',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-16 02:59:29'),
                'updated_at' => Carbon::parse('2025-07-16 02:59:29'),
            ],
        ]);
    }
}
