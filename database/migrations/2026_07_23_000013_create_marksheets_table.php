<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marksheets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('exam_id')->constrained('exams');
            $table->string('academic_session');
            $table->integer('total_marks');
            $table->integer('obtained_marks');
            $table->decimal('percentage', 5, 2);
            $table->string('grade');
            $table->enum('result', ['PASS', 'FAIL']);
            $table->timestamps();
            
            // A student should have only one marksheet per exam
            $table->unique(['student_id', 'exam_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marksheets');
    }
};
