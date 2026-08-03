<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add section column to students table
        Schema::table('students', function (Blueprint $table) {
            $table->string('section', 50)->default('A')->after('class_id');
        });

        // 2. Create student_arrears table
        Schema::create('student_arrears', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('month', 7); // Format: YYYY-MM (e.g. 2026-05)
            $table->decimal('amount', 10, 2)->default(0.00); // Current outstanding amount
            $table->decimal('original_amount', 10, 2)->default(0.00); // Original outstanding amount
            $table->enum('payment_status', ['unpaid', 'partially_paid', 'paid'])->default('unpaid');
            $table->timestamps();

            // Add unique index to prevent duplicate month records for the same student
            $table->unique(['student_id', 'month']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_arrears');

        Schema::table('students', function (Blueprint $table) {
            $table->dropColumn('section');
        });
    }
};
