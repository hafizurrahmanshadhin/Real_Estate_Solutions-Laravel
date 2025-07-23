<?php

namespace Database\Seeders;

use App\Models\CMS;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CMSSeeder extends Seeder {
    public function run(): void {
        // Clear existing data
        DB::table('c_m_s')->truncate();

        $this->seedHomePageSection();
        $this->seedContactPageSection();
        $this->seedOthersPageSection();

        $this->command->info('CMS table seeded successfully with ' . CMS::count() . ' records!');
    }

    /**
     * Seed home page section
     */
    private function seedHomePageSection(): void {
        CMS::create([
            'section'    => 'home_page',
            'title'      => 'ASAP Real Estate Solutions|',
            'content'    => '<p>All the services you need to get your listing ready</p>',
            'image'      => 'backend/images/seeder/cms/home-page-hero-section.png',
            'status'     => 'active',
            'created_at' => Carbon::parse('2025-07-23 04:23:58'),
            'updated_at' => Carbon::parse('2025-07-23 04:23:58'),
        ]);
    }

    /**
     * Seed contact page section
     */
    private function seedContactPageSection(): void {
        CMS::create([
            'section'    => 'contact_us_page',
            'title'      => 'Contact Us',
            'content'    => '<p>Building strong, lasting partnerships to achieve shared goals and success.</p>',
            'image'      => 'backend/images/seeder/cms/contact-page-body-section.png',
            'banner'     => 'backend/images/seeder/cms/contact-page-hero-section.jpg',
            'status'     => 'active',
            'created_at' => Carbon::parse('2025-07-23 04:26:58'),
            'updated_at' => Carbon::parse('2025-07-23 04:26:58'),
        ]);
    }

    /**
     * Seed others page section
     */
    private function seedOthersPageSection(): void {
        CMS::create([
            'section'    => 'others_page',
            'title'      => 'Privacy Policy',
            'content'    => '<p>Building strong, lasting partnerships to achieve shared goals and success.</p>',
            'image'      => 'backend/images/seeder/cms/contact-page-hero-section.jpg',
            'status'     => 'active',
            'created_at' => Carbon::parse('2025-07-23 04:30:48'),
            'updated_at' => Carbon::parse('2025-07-23 04:33:16'),
        ]);
    }
}
