<?php

namespace Database\Seeders;

use App\Models\School;
use Illuminate\Database\Seeder;

class SchoolSeeder extends Seeder
{
    public function run(): void
    {
        School::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'DAWN PUBLIC SCHOOL / SUPER DAWN SCHOOL SYSTEM LAKHI',
                'address' => 'Main Bazar Lakhi, Tehsil & District Shikarpur, Sindh',
                'phone' => '0300-1234567',
                'email' => 'info@superdawnschool.edu.pk',
                'principal_name' => 'Prof. Ghulam Rasool',
                'academic_session' => '2026-2027',
                'logo' => null,
            ]
        );
    }
}
