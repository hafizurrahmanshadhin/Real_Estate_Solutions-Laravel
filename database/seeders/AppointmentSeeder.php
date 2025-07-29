<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder {
    public function run(): void {
        Appointment::insert([
            [
                'id'         => 1,
                'order_id'   => 1,
                'date'       => '2025-07-31',
                'time'       => '14:30:00',
                'created_at' => '2025-07-29 00:09:47',
                'updated_at' => '2025-07-29 00:09:47',
                'deleted_at' => null,
            ],
            [
                'id'         => 2,
                'order_id'   => 2,
                'date'       => '2025-07-30',
                'time'       => '14:30:00',
                'created_at' => '2025-07-29 00:11:20',
                'updated_at' => '2025-07-29 00:11:20',
                'deleted_at' => null,
            ],
        ]);
    }
}
