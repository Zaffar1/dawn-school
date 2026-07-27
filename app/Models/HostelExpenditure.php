<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HostelExpenditure extends Model
{
    protected $fillable = [
        'category',
        'title',
        'amount',
        'date',
        'payee_name',
        'billing_month',
        'reference_no',
        'units_consumed',
        'payment_method',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'units_consumed' => 'integer',
    ];
}
