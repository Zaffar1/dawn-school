<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SchoolClass extends Model
{
    protected $table = 'classes';

    protected $fillable = ['name', 'status'];

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    public function activeStudents(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id')->where('status', 'active');
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'class_id');
    }

    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'class_id');
    }

    public function feeSetting(): HasOne
    {
        return $this->hasOne(FeeSetting::class, 'class_id');
    }
}
