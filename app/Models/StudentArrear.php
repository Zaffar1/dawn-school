<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentArrear extends Model
{
    protected $table = 'student_arrears';

    protected $fillable = [
        'student_id',
        'month',
        'amount',
        'original_amount',
        'payment_status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'original_amount' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
