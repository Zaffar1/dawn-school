<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use App\Models\SchoolClass;
use App\Models\Exam;
use App\Models\Student;
use App\Models\School;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $classes = SchoolClass::where('status', 'active')->get();
        $exams = Exam::where('status', 'active')->get();
        $students = Student::active()->orderBy('name')->get();
        $school = School::first();
        $defaultSession = $school ? $school->academic_session : '2026-2027';

        return view('reports.index', compact('classes', 'exams', 'students', 'defaultSession'));
    }

    public function generate(Request $request)
    {
        $reportType = $request->input('report_type'); // e.g. student, fee, academic
        $subType = $request->input('sub_type'); // e.g. active, inactive, daily, monthly, class_wise, passed, failed
        
        $filters = [
            'class_id' => $request->input('class_id'),
            'student_id' => $request->input('student_id'),
            'exam_id' => $request->input('exam_id'),
            'date' => $request->input('date'),
            'month' => $request->input('month'),
            'year' => $request->input('year'),
            'start_date' => $request->input('start_date'),
            'end_date' => $request->input('end_date'),
            'academic_session' => $request->input('academic_session'),
        ];

        $data = [];
        $title = "Report";

        if ($reportType === 'student') {
            $data = $this->reportService->getStudentReport($subType, $filters);
            $title = ucwords(str_replace('_', ' ', $subType)) . " Students Report";
        } elseif ($reportType === 'fee') {
            $data = $this->reportService->getFeeReport($subType, $filters);
            $title = ucwords(str_replace('_', ' ', $subType)) . " Fee Collection Report";
        } elseif ($reportType === 'academic') {
            $data = $this->reportService->getAcademicReport($subType, $filters);
            $title = ucwords(str_replace('_', ' ', $subType)) . " Academic Report";
        }

        $school = School::first();

        // Check if PDF format is requested
        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('reports.pdf', compact('data', 'reportType', 'subType', 'filters', 'school', 'title'))
                ->setPaper('a4', 'portrait');
            return $pdf->download(str_replace(' ', '_', $title) . ".pdf");
        }

        // Default: Show report in browser view
        $classes = SchoolClass::where('status', 'active')->get();
        $exams = Exam::where('status', 'active')->get();
        $students = Student::active()->orderBy('name')->get();
        $defaultSession = $school ? $school->academic_session : '2026-2027';

        return view('reports.index', compact(
            'classes', 'exams', 'students', 'defaultSession',
            'data', 'reportType', 'subType', 'filters', 'title'
        ));
    }
}
