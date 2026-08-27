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
        Schema::table('dashboard_summaries', function (Blueprint $table) {
            $table->integer('all_files_count')->default(0)->after('type_counts');
            $table->integer('ts_files_count')->default(0)->after('all_files_count');
            $table->integer('sl_files_count')->default(0)->after('ts_files_count');
            $table->integer('sw_files_count')->default(0)->after('sl_files_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dashboard_summaries', function (Blueprint $table) {
            $table->dropColumn([
                'all_files_count',
                'ts_files_count',
                'sl_files_count',
                'sw_files_count'
            ]);
        });
    }
};
