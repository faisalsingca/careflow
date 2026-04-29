<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            if (!Schema::hasColumn('medical_records', 'appointment_id')) {
                $table->foreignId('appointment_id')
                    ->nullable()
                    ->after('id')
                    ->constrained()
                    ->nullOnDelete();
            }
        });

        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement('ALTER TABLE medical_records MODIFY diagnosis VARCHAR(255) NULL');
        } elseif ($driver === 'sqlite') {
            Schema::table('medical_records', function (Blueprint $table) {
                $table->string('diagnosis')->nullable()->change();
            });
        } else {
            Schema::table('medical_records', function (Blueprint $table) {
                $table->string('diagnosis')->nullable()->change();
            });
        }

        Schema::table('medical_records', function (Blueprint $table) {
            if (!collect(Schema::getIndexes('medical_records'))->contains(fn($index) => $index['name'] === 'medical_records_appointment_id_unique')) {
                $table->unique('appointment_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            if (Schema::hasColumn('medical_records', 'appointment_id')) {
                $table->dropUnique('medical_records_appointment_id_unique');
                $table->dropConstrainedForeignId('appointment_id');
            }
        });

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE medical_records MODIFY diagnosis VARCHAR(255) NOT NULL DEFAULT ''");
        } else {
            Schema::table('medical_records', function (Blueprint $table) {
                $table->string('diagnosis')->nullable(false)->default('')->change();
            });
        }
    }
};
