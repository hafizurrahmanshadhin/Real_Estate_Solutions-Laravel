<?php

namespace Database\Seeders;

use Database\Seeders\AddOnSeeder;
use Database\Seeders\AppointmentSeeder;
use Database\Seeders\CMSSeeder;
use Database\Seeders\ContactUsSeeder;
use Database\Seeders\ContentSeeder;
use Database\Seeders\FootageSizeSeeder;
use Database\Seeders\OrderItemSeeder;
use Database\Seeders\OrderSeeder;
use Database\Seeders\OtherServiceOrderSeeder;
use Database\Seeders\OtherServiceSeeder;
use Database\Seeders\PackageSeeder;
use Database\Seeders\PropertySeeder;
use Database\Seeders\ServiceItemSeeder;
use Database\Seeders\ServiceItemsPivotSeeder;
use Database\Seeders\ServiceSeeder;
use Database\Seeders\SocialMediaSeeder;
use Database\Seeders\SystemSettingSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ZipCodeSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        $this->call([
            UserSeeder::class,
            SystemSettingSeeder::class,
            SocialMediaSeeder::class,
            ContentSeeder::class,
            ZipCodeSeeder::class,
            PackageSeeder::class,
            FootageSizeSeeder::class,
            ServiceItemSeeder::class,
            ServiceSeeder::class,
            ServiceItemsPivotSeeder::class,
            AddOnSeeder::class,
            OtherServiceSeeder::class,
            CMSSeeder::class,
            ContactUsSeeder::class,
            OtherServiceOrderSeeder::class,
            OrderSeeder::class,
            PropertySeeder::class,
            AppointmentSeeder::class,
            OrderItemSeeder::class,
        ]);
    }
}
