<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'admission_number',
        'name',
        'father_name',
        'date_of_birth',
        'gender',
        'class_id',
        'section',
        'roll_number',
        'phone',
        'address',
        'admission_date',
        'photo',
        'admission_fee',
        'monthly_fee',
        'exam_fee',
        'arrears',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'admission_date' => 'date',
        'admission_fee' => 'decimal:2',
        'monthly_fee' => 'decimal:2',
        'exam_fee' => 'decimal:2',
        'arrears' => 'decimal:2',
    ];

    public function class(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function studentArrears(): HasMany
    {
        return $this->hasMany(StudentArrear::class)->orderBy('month', 'asc');
    }

    public function admissions(): HasMany
    {
        return $this->hasMany(Admission::class);
    }

    public function feeTransactions(): HasMany
    {
        return $this->hasMany(FeeTransaction::class)->orderBy('date', 'desc')->orderBy('id', 'desc');
    }

    public function feeReceipts(): HasMany
    {
        return $this->hasMany(FeeReceipt::class)->orderBy('date', 'desc')->orderBy('id', 'desc');
    }

    public function marksheets(): HasMany
    {
        return $this->hasMany(Marksheet::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}
