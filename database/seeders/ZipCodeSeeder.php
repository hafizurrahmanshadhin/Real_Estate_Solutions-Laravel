<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZipCodeSeeder extends Seeder {
    public function run(): void {
        DB::table('zip_codes')->insert([
            [
                'id'         => 1,
                'zip_code'   => '80015',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-14 02:56:13'),
                'updated_at' => Carbon::parse('2025-07-14 02:56:13'),
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'zip_code'   => '80016',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-14 02:56:17'),
                'updated_at' => Carbon::parse('2025-07-14 02:56:17'),
                'deleted_at' => null,
            ],
            [
                'id'         => 3,
                'zip_code'   => '80017',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-14 02:56:17'),
                'updated_at' => Carbon::parse('2025-07-14 02:56:17'),
                'deleted_at' => null,
            ],
            [
                'id'         => 4,
                'zip_code'   => '80018',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-14 02:56:17'),
                'updated_at' => Carbon::parse('2025-07-14 02:56:17'),
                'deleted_at' => null,
            ],
            [
                'id'         => 5,
                'zip_code'   => '80019',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-14 02:56:17'),
                'updated_at' => Carbon::parse('2025-07-14 02:56:17'),
                'deleted_at' => null,
            ],
            [
                'id'         => 6,
                'zip_code'   => '80020',
                'status'     => 'active',
                'created_at' => Carbon::parse('2025-07-14 02:56:17'),
                'updated_at' => Carbon::parse('2025-07-14 02:56:17'),
                'deleted_at' => null,
            ],
        ]);
    }
}
