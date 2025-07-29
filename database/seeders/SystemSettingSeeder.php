<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingSeeder extends Seeder {
    // Company constants
    private const COMPANY_NAME   = 'ASAP Real Estate Solutions';
    private const COPYRIGHT_YEAR = '2025';
    private const LOGO_PATH      = 'backend/images/PNG FILE-01-02.png';
    private const FAVICON_PATH   = 'backend/images/PNG FILE-01-02.png';

    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Clear existing data
        DB::table('system_settings')->truncate();

        // Insert system settings data
        DB::table('system_settings')->insert([
            [
                'id'             => 1,
                'title'          => null,
                'system_name'    => null,
                'email'          => 'shadhin666@gmail.com',
                'phone_number'   => null,
                'address'        => null,
                'copyright_text' => $this->getCopyrightText(),
                'description'    => $this->getCompanyDescription(),
                'logo'           => self::LOGO_PATH,
                'favicon'        => self::FAVICON_PATH,
                'created_at'     => Carbon::parse('2024-12-07 23:08:00'),
                'updated_at'     => Carbon::parse('2025-07-23 23:18:50'),
            ],
        ]);

        // Reset auto increment
        DB::statement('ALTER TABLE system_settings AUTO_INCREMENT = 2;');

        $this->command->info('System Settings Table Seeded Successfully!');
    }

    /**
     * Get formatted copyright text
     */
    private function getCopyrightText(): string {
        return '© ' . self::COPYRIGHT_YEAR . ' ' . self::COMPANY_NAME . '. All Rights Reserved.';
    }

    /**
     * Get company description
     */
    private function getCompanyDescription(): string {
        return '<p>We help properties shine with professional photography, drone footage, and property prep services like cleaning, landscaping, and staging. Our mission is to make real estate listings stand out, attract more buyers, and sell faster.</p>';
    }
}
