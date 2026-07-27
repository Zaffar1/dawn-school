<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marksheet_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('marksheet_id')->constrained('marksheets')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained('subjects');
            $table->integer('total_marks');
            $table->integer('passing_marks');
            $table->integer('obtained_marks');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marksheet_subjects');
    }
};
