<?php

namespace App\Services;

use App\Models\Student;
use App\Models\FeeTransaction;
use App\Models\FeeReceipt;
use Illuminate\Support\Facades\DB;
use Exception;

class FeeService
{
    protected $receiptService;

    public function __construct(ReceiptService $receiptService)
    {
        $this->receiptService = $receiptService;
    }

    /**
     * Calculate and process fee collection.
     */
    public function collectFee(array $data)
    {
        return DB::transaction(function () use ($data) {
            $student = Student::findOrFail($data['student_id']);
            
            // Backend validation of inputs (never trust frontend totals)
            $admissionFee = isset($data['admission_fee']) ? (float)$data['admission_fee'] : 0.0;
            $monthlyFee = isset($data['monthly_fee']) ? (float)$data['monthly_fee'] : 0.0;
            $examFee = isset($data['exam_fee']) ? (float)$data['exam_fee'] : 0.0;
            $previousArrears = (float)$student->arrears; // Load from DB, not form input for security
            
            $totalAmount = $admissionFee + $monthlyFee + $examFee + $previousArrears;
            $paidAmount = (float)$data['paid_amount'];
            
            if ($paidAmount < 0) {
                throw new Exception("Paid amount cannot be negative.");
            }
            
            $remainingArrears = $totalAmount - $paidAmount;

            // 1. Generate unique receipt number
            $receiptNumber = $this->receiptService->generateReceiptNumber();

            // 2. Create Receipt Record
            $receipt = FeeReceipt::create([
                'receipt_number' => $receiptNumber,
                'student_id' => $student->id,
                'date' => $data['date'] ?? now()->toDateString(),
                'admission_fee' => $admissionFee,
                'monthly_fee' => $monthlyFee,
                'exam_fee' => $examFee,
                'previous_arrears' => $previousArrears,
                'total_amount' => $totalAmount,
                'paid_amount' => $paidAmount,
                'remaining_arrears' => $remainingArrears,
            ]);

            // 3. Create Transaction Ledger Entry
            FeeTransaction::create([
                'date' => $data['date'] ?? now()->toDateString(),
                'receipt_number' => $receiptNumber,
                'student_id' => $student->id,
                'admission_fee' => $admissionFee,
                'monthly_fee' => $monthlyFee,
                'exam_fee' => $examFee,
                'previous_arrears' => $previousArrears,
                'paid_amount' => $paidAmount,
                'remaining_arrears' => $remainingArrears,
            ]);

            // 4. Update student's outstanding arrears
            $student->update([
                'arrears' => $remainingArrears
            ]);

            return $receipt;
        });
    }
}
