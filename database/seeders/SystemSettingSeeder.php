<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingSeeder extends Seeder {
    public function run(): void {
        DB::table('system_settings')->insert([
            [
                'id'             => 1,
                'title'          => null,
                'system_name'    => null,
                'email'          => null,
                'phone_number'   => null,
                'address'        => null,
                'copyright_text' => null,
                'description'    => null,
                'logo'           => null,
                'favicon'        => null,
                'created_at'     => '2024-12-08 05:08:00',
                'updated_at'     => '2024-12-08 05:08:00',
            ],
        ]);
    }
}
