<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeReceipt extends Model
{
    protected $fillable = [
        'receipt_number',
        'student_id',
        'date',
        'admission_fee',
        'monthly_fee',
        'exam_fee',
        'previous_arrears',
        'total_amount',
        'paid_amount',
        'remaining_arrears',
    ];

    protected $casts = [
        'date' => 'date',
        'admission_fee' => 'decimal:2',
        'monthly_fee' => 'decimal:2',
        'exam_fee' => 'decimal:2',
        'previous_arrears' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_arrears' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
