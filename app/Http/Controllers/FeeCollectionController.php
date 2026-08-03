<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\FeeReceipt;
use App\Models\SchoolClass;
use App\Services\FeeService;
use Illuminate\Http\Request;
use Exception;

class FeeCollectionController extends Controller
{
    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    public function index(Request $request)
    {
        $query = FeeReceipt::with(['student.class']);

        if ($request->filled('month')) {
            $parts = explode('-', $request->month);
            if (count($parts) === 2) {
                $query->whereYear('date', $parts[0])
                      ->whereMonth('date', $parts[1]);
            }
        }

        $receipts = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(15)->appends($request->query());
        return view('fees.index', compact('receipts'));
    }

    public function create()
    {
        // Only active students should appear in the selection dropdown by default
        $students = Student::active()->with(['class'])->orderBy('name')->get();
        return view('fees.collect', compact('students'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'date' => 'required|date',
            'admission_fee' => 'required|numeric|min:0',
            'monthly_fee' => 'required|numeric|min:0',
            'exam_fee' => 'required|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0',
        ]);

        try {
            // Process collection through FeeService (recalculates on backend and logs transaction)
            $receipt = $this->feeService->collectFee($validated);

            return redirect()->route('receipts.show', $receipt->id)
                ->with('success', 'Fees collected successfully. Receipt #' . $receipt->receipt_number . ' generated.');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Failed to collect fees: ' . $e->getMessage());
        }
    }

    /**
     * AJAX endpoint to get student details and current fee settings/arrears.
     * Route: /fee-collection/student/{id}
     */
    public function getStudentFees($id)
    {
        $student = Student::with(['class'])->findOrFail($id);

        // Check if student has already paid monthly fee in the current month
        $currentMonthPaid = \App\Models\FeeReceipt::where('student_id', $student->id)
            ->whereMonth('date', now()->month)
            ->whereYear('date', now()->year)
            ->where('monthly_fee', '>', 0)
            ->exists();

        // If student does not have fee values set, load class defaults
        $classFeeSetting = $student->class->feeSetting;
        
        $admissionFee = $student->admission_fee > 0 ? $student->admission_fee : ($classFeeSetting ? $classFeeSetting->admission_fee : 3000.00);
        $monthlyFee = $student->monthly_fee > 0 ? $student->monthly_fee : ($classFeeSetting ? $classFeeSetting->monthly_fee : 2000.00);
        $examFee = $student->exam_fee > 0 ? $student->exam_fee : ($classFeeSetting ? $classFeeSetting->exam_fee : 500.00);

        if ($currentMonthPaid) {
            $monthlyFee = 0;
        }

        return response()->json([
            'student_id' => $student->id,
            'name' => $student->name,
            'father_name' => $student->father_name,
            'class_name' => $student->class->name,
            'arrears' => (float)$student->arrears,
            'current_month_paid' => $currentMonthPaid,
            'default_fees' => [
                'admission_fee' => (float)$admissionFee,
                'monthly_fee' => (float)$monthlyFee,
                'exam_fee' => (float)$examFee,
            ]
        ]);
    }
}
