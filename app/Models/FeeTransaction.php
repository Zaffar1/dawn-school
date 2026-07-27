<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeTransaction extends Model
{
    protected $fillable = [
        'date',
        'receipt_number',
        'student_id',
        'admission_fee',
        'monthly_fee',
        'exam_fee',
        'previous_arrears',
        'paid_amount',
        'remaining_arrears',
    ];

    protected $casts = [
        'date' => 'date',
        'admission_fee' => 'decimal:2',
        'monthly_fee' => 'decimal:2',
        'exam_fee' => 'decimal:2',
        'previous_arrears' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_arrears' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
