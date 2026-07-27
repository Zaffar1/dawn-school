<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('admission_number')->unique();
            $table->string('name');
            $table->string('father_name');
            $table->date('date_of_birth');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->foreignId('class_id')->constrained('classes');
            $table->string('roll_number');
            $table->string('phone')->nullable();
            $table->text('address');
            $table->date('admission_date');
            $table->string('photo')->nullable();
            $table->decimal('admission_fee', 10, 2)->default(0.00);
            $table->decimal('monthly_fee', 10, 2)->default(0.00);
            $table->decimal('exam_fee', 10, 2)->default(0.00);
            $table->decimal('arrears', 10, 2)->default(0.00);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
