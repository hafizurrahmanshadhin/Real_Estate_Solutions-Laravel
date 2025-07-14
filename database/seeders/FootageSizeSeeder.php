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
                'size'       => '0 - 2000',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-14 02:37:31'),
                'updated_at' => Carbon::parse('2025-07-14 02:37:31'),
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'size'       => '2001 - 3500',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-14 02:37:34'),
                'updated_at' => Carbon::parse('2025-07-14 02:52:29'),
                'deleted_at' => null,
            ],
            [
                'id'         => 3,
                'size'       => '3501 - 5000',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-14 02:37:34'),
                'updated_at' => Carbon::parse('2025-07-14 02:52:29'),
                'deleted_at' => null,
            ],
            [
                'id'         => 4,
                'size'       => '5001 - 6000',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-14 02:37:34'),
                'updated_at' => Carbon::parse('2025-07-14 02:52:29'),
                'deleted_at' => null,
            ],
        ]);
    }
}
