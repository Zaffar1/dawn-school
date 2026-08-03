<?php

namespace App\Services;

use App\Models\Student;
use App\Models\FeeTransaction;
use App\Models\FeeReceipt;
use App\Models\StudentArrear;
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

            // 4. Arrears Allocation Logic
            $paymentRemaining = $paidAmount;
            $transactionDate = $data['date'] ?? now()->toDateString();
            $paymentMonth = date('Y-m', strtotime($transactionDate));
            $allocatedMonths = [];
            $totalArrearsPaid = 0.0;

            if (isset($data['arrears_payment']) && is_array($data['arrears_payment']) && count($data['arrears_payment']) > 0) {
                // Checkbox-based selection model
                foreach ($data['arrears_payment'] as $month => $amountAllocated) {
                    $amountAllocated = (float)$amountAllocated;
                    if ($amountAllocated <= 0) {
                        continue;
                    }

                    $arrear = StudentArrear::where('student_id', $student->id)
                        ->where('month', $month)
                        ->first();

                    if ($arrear) {
                        $monthLabel = date('F Y', strtotime($month . '-01'));
                        $oldAmount = (float)$arrear->amount;
                        $newAmount = max(0.00, $oldAmount - $amountAllocated);

                        $arrear->update([
                            'amount' => $newAmount,
                            'payment_status' => ($newAmount == 0) ? 'paid' : 'partially_paid'
                        ]);

                        $allocatedMonths[] = [
                            'month' => $month,
                            'label' => $monthLabel,
                            'allocated' => $amountAllocated,
                            'status' => ($newAmount == 0) ? 'paid' : 'partially_paid'
                        ];

                        $totalArrearsPaid += $amountAllocated;
                    }
                }

                // Calculate how much went to current fees
                $currentFeesPaid = max(0.0, $paidAmount - $totalArrearsPaid);
                $currentFeesDue = $admissionFee + $monthlyFee + $examFee;

                if ($currentFeesDue > 0) {
                    if ($currentFeesDue > $currentFeesPaid) {
                        $shortfall = $currentFeesDue - $currentFeesPaid;
                        $existingArrear = StudentArrear::where('student_id', $student->id)
                            ->where('month', $paymentMonth)
                            ->first();

                        if ($existingArrear) {
                            $newOriginal = (float)$existingArrear->original_amount + $currentFeesDue;
                            $newAmount = (float)$existingArrear->amount + $shortfall;
                            
                            $existingArrear->update([
                                'original_amount' => $newOriginal,
                                'amount' => $newAmount,
                                'payment_status' => ($newAmount == 0) ? 'paid' : (($newAmount < $newOriginal) ? 'partially_paid' : 'unpaid')
                            ]);
                        } else {
                            StudentArrear::create([
                                'student_id' => $student->id,
                                'month' => $paymentMonth,
                                'amount' => $shortfall,
                                'original_amount' => $currentFeesDue,
                                'payment_status' => ($currentFeesPaid > 0) ? 'partially_paid' : 'unpaid'
                            ]);
                        }
                    } else {
                        // Current fees fully paid
                        $existingArrear = StudentArrear::where('student_id', $student->id)
                            ->where('month', $paymentMonth)
                            ->first();
                        if ($existingArrear) {
                            $existingArrear->update([
                                'amount' => 0.00,
                                'payment_status' => 'paid'
                            ]);
                        }
                    }
                }

                $remainingArrears = $previousArrears - $totalArrearsPaid + max(0.0, $currentFeesDue - $currentFeesPaid);

            } else {
                // Fallback to standard FIFO model
                // A. Allocate payment to oldest unpaid/partially_paid arrears first
                $outstandingArrears = StudentArrear::where('student_id', $student->id)
                    ->whereIn('payment_status', ['unpaid', 'partially_paid'])
                    ->orderBy('month', 'asc')
                    ->get();

                foreach ($outstandingArrears as $arrear) {
                    if ($paymentRemaining <= 0) {
                        break;
                    }

                    $monthLabel = date('F Y', strtotime($arrear->month . '-01'));

                    if ($paymentRemaining >= (float)$arrear->amount) {
                        $amountAllocated = (float)$arrear->amount;
                        $paymentRemaining -= $amountAllocated;
                        $arrear->update([
                            'amount' => 0.00,
                            'payment_status' => 'paid'
                        ]);
                        $allocatedMonths[] = [
                            'month' => $arrear->month,
                            'label' => $monthLabel,
                            'allocated' => $amountAllocated,
                            'status' => 'paid'
                        ];
                    } else {
                        $amountAllocated = $paymentRemaining;
                        $arrear->update([
                            'amount' => (float)$arrear->amount - $paymentRemaining,
                            'payment_status' => 'partially_paid'
                        ]);
                        $paymentRemaining = 0;
                        $allocatedMonths[] = [
                            'month' => $arrear->month,
                            'label' => $monthLabel,
                            'allocated' => $amountAllocated,
                            'status' => 'partially_paid'
                        ];
                    }
                }

                // B. Allocate remaining payment to current month's fees
                $currentFeesDue = $admissionFee + $monthlyFee + $examFee;
                if ($currentFeesDue > 0) {
                    if ($currentFeesDue > $paymentRemaining) {
                        // There is a shortfall in current fees. Log it as arrears for the payment month.
                        $shortfall = $currentFeesDue - $paymentRemaining;
                        
                        // Check if an arrears record already exists for this month (update it if so)
                        $existingArrear = StudentArrear::where('student_id', $student->id)
                            ->where('month', $paymentMonth)
                            ->first();

                        if ($existingArrear) {
                            $newOriginal = (float)$existingArrear->original_amount + $currentFeesDue;
                            $newAmount = (float)$existingArrear->amount + $shortfall;
                            
                            $existingArrear->update([
                                'original_amount' => $newOriginal,
                                'amount' => $newAmount,
                                'payment_status' => ($newAmount == 0) ? 'paid' : (($newAmount < $newOriginal) ? 'partially_paid' : 'unpaid')
                            ]);
                        } else {
                            StudentArrear::create([
                                'student_id' => $student->id,
                                'month' => $paymentMonth,
                                'amount' => $shortfall,
                                'original_amount' => $currentFeesDue,
                                'payment_status' => ($paymentRemaining > 0) ? 'partially_paid' : 'unpaid'
                            ]);
                        }
                    } else {
                        // Current fees are fully covered. If there was a previous arrear record for this month, make sure it is paid.
                        $existingArrear = StudentArrear::where('student_id', $student->id)
                            ->where('month', $paymentMonth)
                            ->first();
                        if ($existingArrear) {
                            $existingArrear->update([
                                'amount' => 0.00,
                                'payment_status' => 'paid'
                            ]);
                        }
                    }
                }

                $remainingArrears = $totalAmount - $paidAmount;
            }

            // Update arrears_months_details in receipt and transaction
            $serializedDetails = count($allocatedMonths) > 0 ? json_encode($allocatedMonths) : null;
            $receipt->update([
                'arrears_months_details' => $serializedDetails
            ]);

            $transaction = FeeTransaction::where('receipt_number', $receiptNumber)->first();
            if ($transaction) {
                $transaction->update([
                    'arrears_months_details' => $serializedDetails
                ]);
            }

            // 5. Update student's outstanding arrears
            $student->update([
                'arrears' => $remainingArrears
            ]);

            $receipt->allocated_months = $allocatedMonths;
            return $receipt;
        });
    }
}
