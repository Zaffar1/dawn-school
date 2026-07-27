<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Exam;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    public function run(): void
    {
        $class5 = SchoolClass::where('name', 'Class 5')->first();

        if ($class5) {
            Exam::updateOrCreate(
                [
                    'name' => 'Annual Examination',
                    'class_id' => $class5->id,
                    'academic_session' => '2026-2027'
                ],
                [
                    'start_date' => '2026-11-01',
                    'end_date' => '2026-11-15',
                    'status' => 'active'
                ]
            );
        }
    }
}
