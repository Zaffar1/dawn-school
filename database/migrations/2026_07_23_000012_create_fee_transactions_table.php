<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_transactions', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('receipt_number');
            $table->foreignId('student_id')->constrained('students');
            $table->decimal('admission_fee', 10, 2)->default(0.00);
            $table->decimal('monthly_fee', 10, 2)->default(0.00);
            $table->decimal('exam_fee', 10, 2)->default(0.00);
            $table->decimal('previous_arrears', 10, 2)->default(0.00);
            $table->decimal('paid_amount', 10, 2)->default(0.00);
            $table->decimal('remaining_arrears', 10, 2)->default(0.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_transactions');
    }
};
