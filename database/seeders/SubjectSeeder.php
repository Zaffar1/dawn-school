<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $class5 = SchoolClass::where('name', 'Class 5')->first();

        if ($class5) {
            $subjects = [
                ['name' => 'English', 'total_marks' => 100, 'passing_marks' => 40],
                ['name' => 'Mathematics', 'total_marks' => 100, 'passing_marks' => 40],
                ['name' => 'Urdu', 'total_marks' => 100, 'passing_marks' => 40],
                ['name' => 'Science', 'total_marks' => 100, 'passing_marks' => 40],
                ['name' => 'Computer', 'total_marks' => 100, 'passing_marks' => 40],
                ['name' => 'Islamiyat', 'total_marks' => 100, 'passing_marks' => 40],
            ];

            foreach ($subjects as $subject) {
                Subject::updateOrCreate(
                    [
                        'class_id' => $class5->id,
                        'name' => $subject['name']
                    ],
                    [
                        'total_marks' => $subject['total_marks'],
                        'passing_marks' => $subject['passing_marks'],
                        'status' => 'active'
                    ]
                );
            }
        }
    }
}
