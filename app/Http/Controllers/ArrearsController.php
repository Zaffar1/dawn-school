<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\SchoolClass;
use App\Models\StudentArrear;
use App\Services\FeeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;

class ArrearsController extends Controller
{
    protected $feeService;

    public function __construct(FeeService $feeService)
    {
        $this->feeService = $feeService;
    }

    /**
     * Display the Arrears List with totals and filters.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $classFilter = $request->input('class_id');
        $sectionFilter = $request->input('section');
        $monthFilter = $request->input('month');
        $statusFilter = $request->input('payment_status');

        // Main Query
        $query = Student::active()->with([
            'class',
            'studentArrears' => function ($q) {
                $q->orderBy('month', 'asc');
            }
        ]);

        // Filters
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('father_name', 'like', "%{$search}%")
                  ->orWhere('admission_number', 'like', "%{$search}%")
                  ->orWhere('roll_number', 'like', "%{$search}%");
            });
        }

        if ($classFilter) {
            $query->where('class_id', $classFilter);
        }

        if ($sectionFilter) {
            $query->where('section', $sectionFilter);
        }

        if ($monthFilter) {
            $query->whereHas('studentArrears', function ($q) use ($monthFilter) {
                $q->where('month', $monthFilter);
            });
        }

        if ($statusFilter) {
            $query->whereHas('studentArrears', function ($q) use ($statusFilter) {
                $q->where('payment_status', $statusFilter);
            });
        }

        // By default, if no month or status filter is applied, only show students who actually have arrears > 0
        if (!$monthFilter && !$statusFilter) {
            $query->where('arrears', '>', 0);
        }

        $students = $query->orderBy('name')->paginate(15)->withQueryString();

        // Calculate Totals for top statistics cards
        $totalStudents = Student::active()->where('arrears', '>', 0)->count();
        $totalOutstanding = (float) Student::active()->where('arrears', '>', 0)->sum('arrears');

        // Month-wise Outstanding Amount breakdown (only active student arrears)
        $monthWiseBreakdown = StudentArrear::select('month', DB::raw('SUM(amount) as total_amount'))
            ->whereHas('student', function ($q) {
                $q->where('status', 'active');
            })
            ->whereIn('payment_status', ['unpaid', 'partially_paid'])
            ->groupBy('month')
            ->orderBy('month', 'asc')
            ->get();

        // Dropdown Lists for Filters
        $classes = SchoolClass::where('status', 'active')->orderBy('name')->get();
        
        $sections = Student::active()
            ->whereNotNull('section')
            ->where('section', '!=', '')
            ->distinct()
            ->orderBy('section')
            ->pluck('section');

        $distinctMonths = StudentArrear::distinct()
            ->orderBy('month', 'desc')
            ->pluck('month');

        return view('fees.arrears.index', compact(
            'students',
            'classes',
            'sections',
            'distinctMonths',
            'totalStudents',
            'totalOutstanding',
            'monthWiseBreakdown',
            'search',
            'classFilter',
            'sectionFilter',
            'monthFilter',
            'statusFilter'
        ));
    }

    /**
     * Process arrears payment collection via AJAX.
     */
    public function collectPayment(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'amount_to_collect' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'payment_method' => 'required|string|max:100',
        ]);

        $student = Student::findOrFail($request->student_id);

        if ((float)$request->amount_to_collect > (float)$student->arrears) {
            return response()->json([
                'success' => false,
                'message' => 'Collected amount cannot exceed the student\'s total outstanding arrears of Rs. ' . number_format($student->arrears, 2)
            ], 422);
        }

        try {
            // Process collection through FeeService (recalculates and updates month arrears inside)
            $receipt = $this->feeService->collectFee([
                'student_id' => $student->id,
                'date' => $request->payment_date,
                'admission_fee' => 0.00,
                'monthly_fee' => 0.00,
                'exam_fee' => 0.00,
                'paid_amount' => $request->amount_to_collect,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Fees collected successfully. Receipt generated.',
                'receipt_id' => $receipt->id,
                'receipt_number' => $receipt->receipt_number,
                'remaining_arrears' => (float)$receipt->remaining_arrears,
                'allocated_months' => $receipt->allocated_months,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to collect arrears: ' . $e->getMessage()
            ], 500);
        }
    }
}
