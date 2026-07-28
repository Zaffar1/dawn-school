<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marksheet extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'academic_session',
        'total_marks',
        'obtained_marks',
        'percentage',
        'grade',
        'result',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function marksheetSubjects(): HasMany
    {
        return $this->hasMany(MarksheetSubject::class, 'marksheet_id');
    }

    public function getObtainedMarksInWordsAttribute(): string
    {
        return self::convertNumberToWords((int)$this->obtained_marks);
    }

    public static function convertNumberToWords(int $number): string
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ', ';
        $negative    = 'negative ';
        $dictionary  = array(
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'forty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand'
        );

        if ($number < 0) {
            return $negative . self::convertNumberToWords(abs($number));
        }

        $string = null;

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[(int) $hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . self::convertNumberToWords($remainder);
                }
                break;
            default:
                $string = $number;
                break;
        }

        return ucwords($string);
    }

    public function getPositionAttribute(): string
    {
        // 1. Get class_id of student
        $classId = $this->student->class_id;

        // 2. Query all marksheets of students in the same class, for same exam, same session
        $marksheets = Marksheet::where('exam_id', $this->exam_id)
            ->where('academic_session', $this->academic_session)
            ->whereHas('student', function ($query) use ($classId) {
                $query->where('class_id', $classId);
            })
            ->orderBy('obtained_marks', 'desc')
            ->orderBy('percentage', 'desc')
            ->get();

        // 3. Find our position (rank)
        $rank = 1;
        foreach ($marksheets as $ms) {
            if ($ms->student_id === $this->student_id) {
                return self::formatOrdinal($rank);
            }
            $rank++;
        }

        return '-';
    }

    public static function formatOrdinal(int $number): string
    {
        if (in_array(($number % 100), array(11, 12, 13))) {
            return $number . 'th';
        }
        switch ($number % 10) {
            case 1:  return $number . 'st';
            case 2:  return $number . 'nd';
            case 3:  return $number . 'rd';
            default: return $number . 'th';
        }
    }
}

