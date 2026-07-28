<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HostelFeePayment extends Model
{
    protected $fillable = [
        'hostel_resident_id',
        'amount',
        'due_amount',
        'arrears',
        'date',
        'billing_month',
        'payment_method',
        'reference_no',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
        'arrears' => 'decimal:2',
    ];

    public function resident(): BelongsTo
    {
        return $this->belongsTo(HostelResident::class, 'hostel_resident_id');
    }
}
