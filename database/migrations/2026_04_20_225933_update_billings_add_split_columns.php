<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->decimal('doctor_percent', 5, 2)->default(0)->after('amount');
            $table->decimal('doctor_share', 10, 2)->default(0)->after('doctor_percent');
            $table->decimal('clinic_share', 10, 2)->default(0)->after('doctor_share');
        });
    }

    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropColumn(['doctor_percent', 'doctor_share', 'clinic_share']);
        });
    }
};