<?php

namespace App\Services;

use App\Models\Student;
use App\Models\FeeReceipt;
use App\Models\FeeTransaction;
use App\Models\Marksheet;
use App\Models\SchoolClass;
use App\Models\Exam;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Get Student Reports based on filter type.
     */
    public function getStudentReport(string $type, array $filters = [])
    {
        $query = Student::with(['class']);

        if (!empty($filters['class_id'])) {
            $query->where('class_id', $filters['class_id']);
        }

        switch ($type) {
            case 'active':
                $query->active();
                break;
            case 'inactive':
                $query->inactive();
                break;
            case 'new_admissions':
                $startDate = $filters['start_date'] ?? now()->startOfMonth()->toDateString();
                $endDate = $filters['end_date'] ?? now()->endOfMonth()->toDateString();
                $query->whereBetween('admission_date', [$startDate, $endDate]);
                break;
            default:
                // 'all' - no status filter
                break;
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get Fee Collection Reports.
     */
    public function getFeeReport(string $type, array $filters = [])
    {
        $query = FeeReceipt::with(['student.class']);

        if (!empty($filters['student_id'])) {
            $query->where('student_id', $filters['student_id']);
        }

        if (!empty($filters['class_id'])) {
            $query->whereHas('student', function ($q) use ($filters) {
                $q->where('class_id', $filters['class_id']);
            });
        }

        switch ($type) {
            case 'daily':
                $date = $filters['date'] ?? now()->toDateString();
                $query->whereDate('date', $date);
                break;
            case 'monthly':
                $month = $filters['month'] ?? date('m');
                $year = $filters['year'] ?? date('Y');
                $query->whereMonth('date', $month)->whereYear('date', $year);
                break;
            case 'yearly':
                $year = $filters['year'] ?? date('Y');
                $query->whereYear('date', $year);
                break;
            case 'arrears':
                // List students with outstanding arrears
                return Student::with(['class'])->where('arrears', '>', 0)
                    ->orderBy('arrears', 'desc')->get();
            case 'class_wise':
                return FeeReceipt::join('students', 'fee_receipts.student_id', '=', 'students.id')
                    ->join('classes', 'students.class_id', '=', 'classes.id')
                    ->select('classes.name as class_name', 
                        DB::raw('SUM(fee_receipts.paid_amount) as total_collected'),
                        DB::raw('SUM(fee_receipts.admission_fee) as total_admission'),
                        DB::raw('SUM(fee_receipts.monthly_fee) as total_monthly'),
                        DB::raw('SUM(fee_receipts.exam_fee) as total_exam')
                    )
                    ->groupBy('classes.id', 'classes.name')
                    ->get();
            default:
                if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
                    $query->whereBetween('date', [$filters['start_date'], $filters['end_date']]);
                }
                break;
        }

        return $query->orderBy('date', 'desc')->get();
    }

    /**
     * Get Academic Reports.
     */
    public function getAcademicReport(string $type, array $filters = [])
    {
        $query = Marksheet::with(['student.class', 'exam']);

        if (!empty($filters['class_id'])) {
            $query->whereHas('student', function ($q) use ($filters) {
                $q->where('class_id', $filters['class_id']);
            });
        }

        if (!empty($filters['exam_id'])) {
            $query->where('exam_id', $filters['exam_id']);
        }

        if (!empty($filters['academic_session'])) {
            $query->where('academic_session', $filters['academic_session']);
        }

        switch ($type) {
            case 'passed':
                $query->where('result', 'PASS');
                break;
            case 'failed':
                $query->where('result', 'FAIL');
                break;
            default:
                break;
        }

        return $query->orderBy('percentage', 'desc')->get();
    }
}
