<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hostel_residents', function (Blueprint $table) {
            $table->decimal('deposit', 10, 2)->default(0.00)->after('monthly_fee');
        });

        Schema::table('hostel_fee_payments', function (Blueprint $table) {
            $table->decimal('due_amount', 10, 2)->default(0.00)->after('amount');
            $table->decimal('arrears', 10, 2)->default(0.00)->after('due_amount');
        });
    }

    public function down(): void
    {
        Schema::table('hostel_fee_payments', function (Blueprint $table) {
            $table->dropColumn(['due_amount', 'arrears']);
        });

        Schema::table('hostel_residents', function (Blueprint $table) {
            $table->dropColumn('deposit');
        });
    }
};
