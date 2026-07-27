<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarksheetSubject extends Model
{
    protected $fillable = [
        'marksheet_id',
        'subject_id',
        'total_marks',
        'passing_marks',
        'obtained_marks',
    ];

    public function marksheet(): BelongsTo
    {
        return $this->belongsTo(Marksheet::class, 'marksheet_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}
