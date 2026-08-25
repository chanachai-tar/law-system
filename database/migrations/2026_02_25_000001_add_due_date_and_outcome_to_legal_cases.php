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
        Schema::table('legal_cases', function (Blueprint $table) {
            if (!Schema::hasColumn('legal_cases', 'due_date')) {
                $table->date('due_date')->nullable()->after('incident_date');
            }
            if (!Schema::hasColumn('legal_cases', 'outcome_summary')) {
                $table->text('outcome_summary')->nullable()->after('status');
            }
            if (!Schema::hasColumn('legal_cases', 'penalty_type')) {
                $table->string('penalty_type')->nullable()->after('outcome_summary');
            }
            if (!Schema::hasColumn('legal_cases', 'damage_amount')) {
                $table->decimal('damage_amount', 12, 2)->nullable()->after('penalty_type');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal_cases', function (Blueprint $table) {
            $table->dropColumn(['due_date', 'outcome_summary', 'penalty_type', 'damage_amount']);
        });
    }
};
