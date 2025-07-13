<?php

namespace Database\Seeders;

use Database\Seeders\ContentSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\SocialMediaSeeder;
use Database\Seeders\SystemSettingSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            UserSeeder::class,
            SystemSettingSeeder::class,
            SocialMediaSeeder::class,
            ContentSeeder::class,
            PackageSeeder::class,
        ]);
    }
}
