<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HostelResident extends Model
{
    protected $fillable = [
        'resident_type',
        'student_id',
        'user_id',
        'name',
        'phone',
        'room_number',
        'monthly_fee',
        'deposit',
        'joining_date',
        'leaving_date',
        'status',
    ];

    protected $casts = [
        'joining_date' => 'date',
        'leaving_date' => 'date',
        'monthly_fee' => 'decimal:2',
        'deposit' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function feePayments(): HasMany
    {
        return $this->hasMany(HostelFeePayment::class)->orderBy('date', 'desc')->orderBy('id', 'desc');
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
