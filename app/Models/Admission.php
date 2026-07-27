<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admission extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'admission_date',
        'admission_fee',
        'monthly_fee',
        'exam_fee',
        'arrears',
    ];

    protected $casts = [
        'admission_date' => 'date',
        'admission_fee' => 'decimal:2',
        'monthly_fee' => 'decimal:2',
        'exam_fee' => 'decimal:2',
        'arrears' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
