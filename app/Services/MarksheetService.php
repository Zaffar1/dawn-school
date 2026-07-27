<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Exam;
use App\Models\Marksheet;
use App\Models\MarksheetSubject;
use App\Models\Subject;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class MarksheetService
{
    /**
     * Calculate grading based on percentage.
     */
    public function calculateGrade(float $percentage): string
    {
        if ($percentage >= 80) return 'A-1';
        if ($percentage >= 70) return 'A';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C';
        if ($percentage >= 40) return 'D';
        if ($percentage >= 33) return 'E';
        return 'F';
    }

    /**
     * Process and save a student marksheet with automatic backend calculation.
     */
    public function saveMarksheet(array $data)
    {
        return DB::transaction(function () use ($data) {
            $student = Student::findOrFail($data['student_id']);
            $exam = Exam::findOrFail($data['exam_id']);
            
            $totalMaxMarks = 0;
            $totalObtainedMarks = 0;
            $hasFailedSubject = false;
            
            $subjectScores = []; // Array to store processed subject marks

            foreach ($data['marks'] as $subjectId => $obtained) {
                $subject = Subject::findOrFail($subjectId);
                $obtained = (int)$obtained;

                // Backend validation of obtained marks
                if ($obtained < 0 || $obtained > $subject->total_marks) {
                    throw new Exception("Obtained marks for {$subject->name} must be between 0 and {$subject->total_marks}.");
                }

                $totalMaxMarks += $subject->total_marks;
                $totalObtainedMarks += $obtained;

                if ($obtained < $subject->passing_marks) {
                    $hasFailedSubject = true;
                }

                $subjectScores[] = [
                    'subject_id' => $subject->id,
                    'total_marks' => $subject->total_marks,
                    'passing_marks' => $subject->passing_marks,
                    'obtained_marks' => $obtained
                ];
            }

            if ($totalMaxMarks === 0) {
                throw new Exception("No subject marks provided.");
            }

            // Calculations
            $percentage = ($totalObtainedMarks / $totalMaxMarks) * 100;
            $grade = $this->calculateGrade($percentage);
            $result = $hasFailedSubject ? 'FAIL' : 'PASS';

            // Create or update Marksheet
            $marksheet = Marksheet::updateOrCreate(
                [
                    'student_id' => $student->id,
                    'exam_id' => $exam->id,
                ],
                [
                    'academic_session' => $data['academic_session'] ?? $exam->academic_session,
                    'total_marks' => $totalMaxMarks,
                    'obtained_marks' => $totalObtainedMarks,
                    'percentage' => round($percentage, 2),
                    'grade' => $grade,
                    'result' => $result,
                ]
            );

            // Clear existing marksheet subjects if updating
            $marksheet->marksheetSubjects()->delete();

            // Save subject scores
            foreach ($subjectScores as $score) {
                $score['marksheet_id'] = $marksheet->id;
                MarksheetSubject::create($score);
            }

            return $marksheet;
        });
    }

    /**
     * Generate a PDF download for a marksheet.
     */
    public function generatePdf(Marksheet $marksheet)
    {
        $marksheet->load(['student.class', 'exam', 'marksheetSubjects.subject']);
        $school = \App\Models\School::first();
        
        $pdf = Pdf::loadView('marksheets.pdf', compact('marksheet', 'school'))
            ->setPaper('a4', 'portrait');
            
        return $pdf;
    }
}
