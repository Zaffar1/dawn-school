<?php

namespace Database\Seeders;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Admission;
use Illuminate\Database\Seeder;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $class5 = SchoolClass::where('name', 'Class 5')->first();

        if ($class5) {
            $demoStudents = [
                [
                    'name' => 'Ali Ahmed',
                    'father_name' => 'Ahmed Khan',
                    'date_of_birth' => '2016-04-12',
                    'gender' => 'male',
                    'roll_number' => '501',
                    'phone' => '0312-9876543',
                    'address' => 'Shikarpur Road, Lakhi',
                    'admission_date' => '2026-03-10',
                    'admission_fee' => 3000.00,
                    'monthly_fee' => 2000.00,
                    'exam_fee' => 500.00,
                    'arrears' => 1000.00,
                ],
                [
                    'name' => 'Bilal Khan',
                    'father_name' => 'Muhammad Khan',
                    'date_of_birth' => '2015-08-22',
                    'gender' => 'male',
                    'roll_number' => '502',
                    'phone' => '0333-1122334',
                    'address' => 'Golo Daro Street, Lakhi',
                    'admission_date' => '2026-03-12',
                    'admission_fee' => 3000.00,
                    'monthly_fee' => 2000.00,
                    'exam_fee' => 500.00,
                    'arrears' => 0.00,
                ],
                [
                    'name' => 'Fatima Zahra',
                    'father_name' => 'Zulfiqar Ali',
                    'date_of_birth' => '2016-01-05',
                    'gender' => 'female',
                    'roll_number' => '503',
                    'phone' => '0300-8877665',
                    'address' => 'Station Road, Lakhi',
                    'admission_date' => '2026-03-15',
                    'admission_fee' => 3000.00,
                    'monthly_fee' => 2000.00,
                    'exam_fee' => 500.00,
                    'arrears' => 2500.00,
                ],
                [
                    'name' => 'Zainab Bibi',
                    'father_name' => 'Liaquat Ali',
                    'date_of_birth' => '2015-11-30',
                    'gender' => 'female',
                    'roll_number' => '504',
                    'phone' => '0315-4433221',
                    'address' => 'Bibi Mohalla, Lakhi',
                    'admission_date' => '2026-03-18',
                    'admission_fee' => 3000.00,
                    'monthly_fee' => 2000.00,
                    'exam_fee' => 500.00,
                    'arrears' => 0.00,
                ],
                [
                    'name' => 'Mustafa Shah',
                    'father_name' => 'Syed Akbar Shah',
                    'date_of_birth' => '2016-05-14',
                    'gender' => 'male',
                    'roll_number' => '505',
                    'phone' => '0321-7766554',
                    'address' => 'Shah Mohalla, Lakhi',
                    'admission_date' => '2026-03-20',
                    'admission_fee' => 3000.00,
                    'monthly_fee' => 2000.00,
                    'exam_fee' => 500.00,
                    'arrears' => 500.00,
                ],
            ];

            foreach ($demoStudents as $index => $data) {
                $admNum = 'SD-' . (26001 + $index);
                
                $student = Student::updateOrCreate(
                    ['admission_number' => $admNum],
                    [
                        'name' => $data['name'],
                        'father_name' => $data['father_name'],
                        'date_of_birth' => $data['date_of_birth'],
                        'gender' => $data['gender'],
                        'class_id' => $class5->id,
                        'roll_number' => $data['roll_number'],
                        'phone' => $data['phone'],
                        'address' => $data['address'],
                        'admission_date' => $data['admission_date'],
                        'admission_fee' => $data['admission_fee'],
                        'monthly_fee' => $data['monthly_fee'],
                        'exam_fee' => $data['exam_fee'],
                        'arrears' => $data['arrears'],
                        'status' => 'active',
                    ]
                );

                // Create Admission Record
                Admission::updateOrCreate(
                    ['student_id' => $student->id],
                    [
                        'class_id' => $class5->id,
                        'admission_date' => $data['admission_date'],
                        'admission_fee' => $data['admission_fee'],
                        'monthly_fee' => $data['monthly_fee'],
                        'exam_fee' => $data['exam_fee'],
                        'arrears' => $data['arrears'],
                    ]
                );
            }
        }
    }
}
