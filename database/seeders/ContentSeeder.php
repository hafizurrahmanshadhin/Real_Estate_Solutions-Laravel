<?php

namespace Database\Seeders;

use App\Models\Content;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder {
    public function run(): void {
        // Clear existing data
        DB::table('contents')->truncate();

        // Seed Terms & Conditions
        Content::create([
            'type'       => 'termsAndConditions',
            'title'      => 'Terms & Conditions',
            'slug'       => 'terms-conditions',
            'content'    => $this->getTermsAndConditionsContent(),
            'status'     => 'active',
            'created_at' => Carbon::parse('2025-01-11 17:37:30'),
            'updated_at' => Carbon::parse('2025-07-23 23:30:33'),
        ]);

        // Seed Privacy Policy
        Content::create([
            'type'       => 'privacyPolicy',
            'title'      => 'Privacy Policy',
            'slug'       => 'privacy-policy',
            'content'    => $this->getPrivacyPolicyContent(),
            'status'     => 'active',
            'created_at' => Carbon::parse('2025-01-14 17:37:30'),
            'updated_at' => Carbon::parse('2025-07-23 23:30:50'),
        ]);

        $this->command->info('Contents seeded successfully with Eloquent!');
    }

    /**
     * Get Terms & Conditions content
     */
    private function getTermsAndConditionsContent(): string {
        return '<h2><strong>1. Introduction</strong></h2><p>This Privacy Policy explains how we collect, use, disclose, and protect your personal information when you use the Signage Sharing Website\'s forum.</p><h2><strong>2. Information We Collect</strong></h2><p>We address international legal problems by us when you create an account in place focusing on regional content. This may include your online client address, payment information, and our other information; we choose to provide.</p><h2><strong>3. Use of Information</strong></h2><p>We use the Information we collect to provide and improve our services, process payments, communicate with you, and for other customer service purposes. We may also see your Information for marketing and promotional purposes.</p><h2><strong>4. Disclosure of Information</strong></h2><p>We may disclose your Information to third parties to the following circumstances:</p><ul><li>To promote products who perform services or are useful</li><li>To comply with legal obligations</li><li>In presence of data and updates</li></ul><h2><strong>5. Security</strong></h2><p>The recommended framework is printed, your personal information from new phoneed access, use, or disclosure. However, no Internet-based service can be completely secure, not we cannot guarantee the absolute security of your information.</p><h2><strong>6. Your Rights</strong></h2><p>You have this right to assess, correct, or advise your personal information. You can exercise these rights by contacting us at <a href="mailto:usage@cagehealth.com">usage@cagehealth.com</a>.</p><h2><strong>7. Changes to Privacy Policy</strong></h2><p>You may update the Privacy Policy from a time. Any changes will be posted on the logo, and your continued use of the App Store and change to more than 1 month later, you can approve of the changes.</p>';
    }

    /**
     * Get Privacy Policy content
     */
    private function getPrivacyPolicyContent(): string {
        return '<h2><strong>1. Introduction</strong></h2><p>This Privacy Policy explains how we collect, use, disclose, and protect your personal information when you use the Signage Sharing Website\'s forum.</p><h2><strong>2. Information We Collect</strong></h2><p>We address international legal problems by us when you create an account in place focusing on regional content. This may include your online client address, payment information, and our other information; we choose to provide.</p><h2><strong>3. Use of Information</strong></h2><p>We use the Information we collect to provide and improve our services, process payments, communicate with you, and for other customer service purposes. We may also see your Information for marketing and promotional purposes.</p><h2><strong>4. Disclosure of Information</strong></h2><p>We may disclose your Information to third parties to the following circumstances:</p><ul><li>To promote products who perform services or are useful</li><li>To comply with legal obligations</li><li>In presence of data and updates</li></ul><h2><strong>5. Security</strong></h2><p>The recommended framework is printed, your personal information from new phoneed access, use, or disclosure. However, no Internet-based service can be completely secure, not we cannot guarantee the absolute security of your information.</p><h2><strong>6. Your Rights</strong></h2><p>You have this right to assess, correct, or advise your personal information. You can exercise these rights by contacting us at <a href="mailto:usage@cagehealth.com">usage@cagehealth.com</a>.</p><h2><strong>7. Changes to Privacy Policy</strong></h2><p>You may update the Privacy Policy from a time. Any changes will be posted on the logo, and your continued use of the App Store and change to more than 1 month later, you can approve of the changes.</p>';
    }
}
