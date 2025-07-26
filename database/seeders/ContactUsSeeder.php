<?php

namespace Database\Seeders;

use App\Models\ContactUs;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ContactUsSeeder extends Seeder {
    public function run(): void {
        // Clear existing data
        ContactUs::query()->delete();

        // Seed the specific data
        $this->seedSpecificData();

        $this->command->info('ContactUs table seeded successfully.');
    }

    /**
     * Seed the specific data
     */
    private function seedSpecificData(): void {
        $specificContacts = [
            [
                'first_name'   => 'Nicholas',
                'last_name'    => 'Edwards',
                'email'        => 'nicholas.edwards152@realty.com',
                'phone_number' => '+19546035142',
                'message'      => 'Hi! I\'m expanding my real estate services and looking for a trusted vendor for property photography, drone services, and staging consultation. Your company comes highly recommended.',
                'is_agree'     => true,
                'status'       => 'active',
                'created_at'   => Carbon::parse('2025-07-26 02:08:35'),
                'updated_at'   => Carbon::parse('2025-07-26 02:08:35'),
            ],
            [
                'first_name'   => 'Charles',
                'last_name'    => 'Phillips',
                'email'        => 'charles.phillips494@gmail.com',
                'phone_number' => '+19853068603',
                'message'      => 'Good afternoon! I\'m interested in your real estate photography packages, particularly for high-value properties. Please provide information about your services and current availability.',
                'is_agree'     => true,
                'status'       => 'active',
                'created_at'   => Carbon::parse('2025-07-26 02:08:36'),
                'updated_at'   => Carbon::parse('2025-07-26 02:08:36'),
            ],
            [
                'first_name'   => 'Jonathan',
                'last_name'    => 'Patterson',
                'email'        => 'jonathan.patterson569@business.com',
                'phone_number' => '+19814071189',
                'message'      => 'Good day! I need immediate assistance with property staging and professional photography for a luxury home listing. Time is of the essence, so please contact me as soon as possible.',
                'is_agree'     => true,
                'status'       => 'active',
                'created_at'   => Carbon::parse('2025-07-26 02:08:36'),
                'updated_at'   => Carbon::parse('2025-07-26 02:08:36'),
            ],
        ];

        ContactUs::insert($specificContacts);

        $this->command->info('Seeded ' . count($specificContacts) . ' specific contact records.');
    }
}
