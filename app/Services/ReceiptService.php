<?php

namespace App\Services;

use App\Models\FeeReceipt;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;

class ReceiptService
{
    /**
     * Generate a unique receipt number.
     * Format: SDS-YYYY-XXXXX (e.g., SDS-2026-00001)
     */
    public function generateReceiptNumber(): string
    {
        $year = date('Y');
        
        // Find the last receipt number for the current year
        $lastReceipt = FeeReceipt::where('receipt_number', 'like', "SDS-{$year}-%")
            ->orderBy('id', 'desc')
            ->first();

        if ($lastReceipt) {
            // Extract the sequence number
            $parts = explode('-', $lastReceipt->receipt_number);
            $sequence = (int)end($parts);
            $nextSequence = str_pad($sequence + 1, 5, '0', STR_PAD_LEFT);
        } else {
            $nextSequence = '00001';
        }

        return "SDS-{$year}-{$nextSequence}";
    }

    /**
     * Generate a PDF download for a receipt.
     */
    public function generatePdf(FeeReceipt $receipt)
    {
        $receipt->load(['student.class']);
        $school = \App\Models\School::first();
        
        $pdf = Pdf::loadView('receipts.pdf', compact('receipt', 'school'))
            ->setPaper('a4', 'portrait');
            
        return $pdf;
    }
}
