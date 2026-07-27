<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\FeeSetting;
use Illuminate\Database\Seeder;

class ClassSeeder extends Seeder
{
    public function run(): void
    {
        $classes = [
            'Play',
            'Nursery',
            'KG',
            'Class 1',
            'Class 2',
            'Class 3',
            'Class 4',
            'Class 5',
            'Class 6',
            'Class 7',
            'Class 8',
            'Class 9',
            'Class 10',
        ];

        foreach ($classes as $className) {
            $classModel = SchoolClass::updateOrCreate(
                ['name' => $className],
                ['status' => 'active']
            );

            // Populate Default Fee Settings for each class
            FeeSetting::updateOrCreate(
                ['class_id' => $classModel->id],
                [
                    'admission_fee' => 3000.00,
                    'monthly_fee' => 2000.00,
                    'exam_fee' => 500.00,
                ]
            );
        }
    }
}
