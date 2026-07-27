<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fee_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->unique()->constrained('classes');
            $table->decimal('admission_fee', 10, 2)->default(3000.00);
            $table->decimal('monthly_fee', 10, 2)->default(2000.00);
            $table->decimal('exam_fee', 10, 2)->default(500.00);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fee_settings');
    }
};
