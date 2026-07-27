<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hostel_expenditures', function (Blueprint $table) {
            $table->id();
            $table->string('category'); // expenditure, salary, rent, electric_bill, other
            $table->string('title')->nullable();
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('payee_name')->nullable();
            $table->string('billing_month')->nullable(); // YYYY-MM
            $table->string('reference_no')->nullable();
            $table->integer('units_consumed')->nullable();
            $table->string('payment_method')->default('Cash');
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('hostel_residents', function (Blueprint $table) {
            $table->id();
            $table->string('resident_type'); // student, staff, other
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable(); // linked staff user
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('room_number');
            $table->decimal('monthly_fee', 10, 2);
            $table->date('joining_date');
            $table->date('leaving_date')->nullable();
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });

        Schema::create('hostel_fee_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('hostel_resident_id');
            $table->decimal('amount', 10, 2);
            $table->date('date');
            $table->string('billing_month'); // YYYY-MM
            $table->string('payment_method')->default('Cash');
            $table->string('reference_no')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('hostel_resident_id')->references('id')->on('hostel_residents')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hostel_fee_payments');
        Schema::dropIfExists('hostel_residents');
        Schema::dropIfExists('hostel_expenditures');
    }
};
