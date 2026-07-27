<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Marksheet;
use App\Models\School;
use App\Services\MarksheetService;
use Illuminate\Http\Request;
use Exception;
use Barryvdh\DomPDF\Facade\Pdf;

class MarksheetController extends Controller
{
    protected $marksheetService;

    public function __construct(MarksheetService $marksheetService)
    {
        $this->marksheetService = $marksheetService;
    }

    public function index(Request $request)
    {
        $classFilter = $request->input('class_id');
        $examFilter = $request->input('exam_id');

        $query = Marksheet::with(['student.class', 'exam']);

        if ($classFilter) {
            $query->whereHas('student', function ($q) use ($classFilter) {
                $q->where('class_id', $classFilter);
            });
        }

        if ($examFilter) {
            $query->where('exam_id', $examFilter);
        }

        $marksheets = $query->orderBy('percentage', 'desc')->paginate(15)->withQueryString();
        $classes = SchoolClass::where('status', 'active')->get();
        $exams = Exam::where('status', 'active')->get();

        return view('marksheets.index', compact('marksheets', 'classes', 'exams', 'classFilter', 'examFilter'));
    }

    public function create()
    {
        $classes = SchoolClass::where('status', 'active')->get();
        $exams = Exam::where('status', 'active')->get();
        $school = School::first();
        $defaultSession = $school ? $school->academic_session : '2026-2027';

        return view('marksheets.create', compact('classes', 'exams', 'defaultSession'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'exam_id' => 'required|exists:exams,id',
            'academic_session' => 'required|string',
            'marks' => 'required|array',
            'marks.*' => 'required|integer|min:0',
        ]);

        try {
            $marksheet = $this->marksheetService->saveMarksheet($validated);

            return redirect()->route('marksheets.show', $marksheet->id)
                ->with('success', 'Marksheet generated and saved successfully.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Error generating marksheet: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $marksheet = Marksheet::with(['student.class', 'exam', 'marksheetSubjects.subject'])->findOrFail($id);
        $school = School::first();
        return view('marksheets.show', compact('marksheet', 'school'));
    }

    public function pdf($id)
    {
        $marksheet = Marksheet::findOrFail($id);
        $pdf = $this->marksheetService->generatePdf($marksheet);
        return $pdf->download("Marksheet_{$marksheet->student->admission_number}.pdf");
    }

    /**
     * View Class-wise academic results.
     */
    public function classWise(Request $request)
    {
        $classId = $request->input('class_id');
        $examId = $request->input('exam_id');
        $session = $request->input('academic_session');

        $classes = SchoolClass::where('status', 'active')->get();
        $exams = Exam::where('status', 'active')->get();
        $school = School::first();
        $defaultSession = $school ? $school->academic_session : '2026-2027';

        $marksheets = [];
        if ($classId && $examId) {
            $marksheets = Marksheet::with(['student'])
                ->where('exam_id', $examId)
                ->where('academic_session', $session ?? $defaultSession)
                ->whereHas('student', function ($q) use ($classId) {
                    $q->where('class_id', $classId);
                })
                ->orderBy('obtained_marks', 'desc')
                ->get();
        }

        return view('marksheets.class_wise', compact('classes', 'exams', 'marksheets', 'classId', 'examId', 'session', 'defaultSession'));
    }

    /**
     * Download Class-wise result PDF.
     */
    public function classWisePdf(Request $request)
    {
        $classId = $request->input('class_id');
        $examId = $request->input('exam_id');
        $session = $request->input('academic_session');

        $class = SchoolClass::findOrFail($classId);
        $exam = Exam::findOrFail($examId);
        $school = School::first();

        $marksheets = Marksheet::with(['student'])
            ->where('exam_id', $examId)
            ->where('academic_session', $session ?? $school->academic_session)
            ->whereHas('student', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            })
            ->orderBy('obtained_marks', 'desc')
            ->get();

        $pdf = Pdf::loadView('marksheets.class_wise_pdf', compact('class', 'exam', 'marksheets', 'school', 'session'))
            ->setPaper('a4', 'landscape');

        return $pdf->download("Class_Result_{$class->name}_{$exam->name}.pdf");
    }

    /**
     * AJAX endpoint to fetch students of a class.
     */
    public function getStudentsForExam($class_id)
    {
        // Only active students should be selected
        $students = Student::active()->where('class_id', $class_id)->orderBy('name')->get();
        return response()->json($students);
    }

    /**
     * AJAX endpoint to fetch subjects of a class.
     */
    public function getSubjectsForClass($class_id)
    {
        $subjects = Subject::where('class_id', $class_id)->where('status', 'active')->orderBy('name')->get();
        return response()->json($subjects);
    }
}
