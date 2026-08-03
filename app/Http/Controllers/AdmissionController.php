<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\Student;
use App\Models\Admission;
use App\Models\School;
use App\Services\FeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;

class AdmissionController extends Controller
{
    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function index()
    {
        $admissions = Admission::with(['student.class'])->orderBy('id', 'desc')->paginate(15);
        return view('admissions.index', compact('admissions'));
    }

    public function create()
    {
        $classes = SchoolClass::where('status', 'active')->get();
        return view('admissions.create', compact('classes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'class_id' => 'required|exists:classes,id',
            'section' => 'required|string|max:50',
            'roll_number' => 'required|string|max:50',
            'phone' => 'nullable|string|max:50',
            'address' => 'required|string',
            'admission_date' => 'required|date',
            'admission_fee' => 'required|numeric|min:0',
            'monthly_fee' => 'required|numeric|min:0',
            'exam_fee' => 'required|numeric|min:0',
            'arrears' => 'nullable|numeric|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'collect_admission_fee' => 'nullable|boolean',
            'paid_amount' => 'nullable|required_if:collect_admission_fee,1|numeric|min:0',
        ]);

        try {
            $receipt = DB::transaction(function () use ($request, $validated) {
                // 1. Generate unique Admission Number
                // Format: SD-26001, SD-26002...
                $year = date('y');
                $lastStudent = Student::orderBy('id', 'desc')->first();
                $nextSeq = $lastStudent ? ($lastStudent->id + 1) : 1;
                $admissionNumber = 'SD-' . $year . str_pad($nextSeq, 3, '0', STR_PAD_LEFT);

                // Handle Photo Upload
                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store('student_photos', 'public');
                }

                // 2. Create Student Record
                $studentArrears = (float)($validated['arrears'] ?? 0.0);
                
                $student = Student::create([
                    'admission_number' => $admissionNumber,
                    'name' => $validated['name'],
                    'father_name' => $validated['father_name'],
                    'date_of_birth' => $validated['date_of_birth'],
                    'gender' => $validated['gender'],
                    'class_id' => $validated['class_id'],
                    'section' => $validated['section'],
                    'roll_number' => $validated['roll_number'],
                    'phone' => $validated['phone'],
                    'address' => $validated['address'],
                    'admission_date' => $validated['admission_date'],
                    'photo' => $photoPath,
                    'admission_fee' => $validated['admission_fee'],
                    'monthly_fee' => $validated['monthly_fee'],
                    'exam_fee' => $validated['exam_fee'],
                    'arrears' => $studentArrears,
                    'status' => 'active',
                ]);

                // 3. Create Admission Log
                Admission::create([
                    'student_id' => $student->id,
                    'class_id' => $validated['class_id'],
                    'admission_date' => $validated['admission_date'],
                    'admission_fee' => $validated['admission_fee'],
                    'monthly_fee' => $validated['monthly_fee'],
                    'exam_fee' => $validated['exam_fee'],
                    'arrears' => $studentArrears,
                ]);

                // 4. Optionally collect fee immediately
                if ($request->boolean('collect_admission_fee') && (float)$request->input('paid_amount') > 0) {
                    // Update arrears to include admission fee if not fully paid
                    $arrearsIncludingAdmission = $studentArrears + (float)$validated['admission_fee'];
                    $student->update(['arrears' => $arrearsIncludingAdmission]);

                    return $this->feeService->collectFee([
                        'student_id' => $student->id,
                        'date' => $validated['admission_date'],
                        'admission_fee' => $validated['admission_fee'],
                        'monthly_fee' => 0.00, // Just paying admission fee for now
                        'exam_fee' => 0.00,
                        'paid_amount' => (float)$request->input('paid_amount'),
                    ]);
                }

                return null;
            });

            if ($receipt) {
                return redirect()->route('receipts.show', $receipt->id)
                    ->with('success', 'Admission created successfully and initial fee collected.');
            }

            return redirect()->route('admissions.index')
                ->with('success', 'Admission application saved successfully. Admission number generated.');

        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Error creating admission: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $admission = Admission::with(['student.class'])->findOrFail($id);
        return view('admissions.show', compact('admission'));
    }

    public function pdf($id)
    {
        $admission = Admission::with(['student.class'])->findOrFail($id);
        $school = School::first();
        
        $pdf = Pdf::loadView('admissions.pdf', compact('admission', 'school'))
            ->setPaper('a4', 'portrait');

        return $pdf->download("Admission_Form_{$admission->student->admission_number}.pdf");
    }
}
