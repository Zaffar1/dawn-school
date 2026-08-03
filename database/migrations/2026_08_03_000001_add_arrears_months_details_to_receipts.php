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
        Schema::table('fee_receipts', function (Blueprint $table) {
            $table->text('arrears_months_details')->nullable()->after('remaining_arrears');
        });

        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->text('arrears_months_details')->nullable()->after('remaining_arrears');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fee_receipts', function (Blueprint $table) {
            $table->dropColumn('arrears_months_details');
        });

        Schema::table('fee_transactions', function (Blueprint $table) {
            $table->dropColumn('arrears_months_details');
        });
    }
};
