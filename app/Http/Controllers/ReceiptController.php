<?php

namespace App\Http\Controllers;

use App\Models\FeeReceipt;
use App\Models\SchoolClass;
use App\Models\Student;
use App\Services\ReceiptService;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    protected $receiptService;

    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $dateFilter = $request->input('date');
        $classFilter = $request->input('class_id');
        $studentFilter = $request->input('student_id');

        $query = FeeReceipt::with(['student.class']);

        // Search by Receipt Number
        if ($search) {
            $query->where('receipt_number', 'like', "%{$search}%");
        }

        // Filter by Date
        if ($dateFilter) {
            $query->whereDate('date', $dateFilter);
        }

        // Filter by Class
        if ($classFilter) {
            $query->whereHas('student', function ($q) use ($classFilter) {
                $q->where('class_id', $classFilter);
            });
        }

        // Filter by Student
        if ($studentFilter) {
            $query->where('student_id', $studentFilter);
        }

        $receipts = $query->orderBy('date', 'desc')->orderBy('id', 'desc')->paginate(15)->withQueryString();
        $classes = SchoolClass::where('status', 'active')->get();
        $students = Student::active()->orderBy('name')->get();

        return view('receipts.index', compact('receipts', 'classes', 'students', 'search', 'dateFilter', 'classFilter', 'studentFilter'));
    }

    public function show($id)
    {
        $receipt = FeeReceipt::with(['student.class'])->findOrFail($id);
        $school = \App\Models\School::first();
        return view('receipts.show', compact('receipt', 'school'));
    }

    public function pdf($id)
    {
        $receipt = FeeReceipt::findOrFail($id);
        $pdf = $this->receiptService->generatePdf($receipt);
        return $pdf->download("Receipt_{$receipt->receipt_number}.pdf");
    }
}
