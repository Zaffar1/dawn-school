<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Update the school name in existing databases
        DB::table('schools')
            ->where('name', 'SUPER DAWN SCHOOL LAKHI')
            ->update(['name' => 'DAWN PUBLIC SCHOOL / SUPER DAWN SCHOOL SYSTEM LAKHI']);
    }

    public function down(): void
    {
        DB::table('schools')
            ->where('name', 'DAWN PUBLIC SCHOOL / SUPER DAWN SCHOOL SYSTEM LAKHI')
            ->update(['name' => 'SUPER DAWN SCHOOL LAKHI']);
    }
};
