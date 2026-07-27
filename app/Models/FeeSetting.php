<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeeSetting extends Model
{
    protected $fillable = [
        'class_id',
        'admission_fee',
        'monthly_fee',
        'exam_fee',
    ];

    protected $casts = [
        'admission_fee' => 'decimal:2',
        'monthly_fee' => 'decimal:2',
        'exam_fee' => 'decimal:2',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }
}
