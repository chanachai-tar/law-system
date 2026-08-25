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
        // 1. Indexes for legal_cases table
        Schema::table('legal_cases', function (Blueprint $table) {
            $table->index('status', 'legal_cases_status_index');
            $table->index('incident_date', 'legal_cases_incident_date_index');
            $table->index(['law_type', 'status'], 'legal_cases_law_type_status_index');
            $table->index('created_at', 'legal_cases_created_at_index');
        });

        // 2. Indexes for appointment_orders table
        Schema::table('appointment_orders', function (Blueprint $table) {
            $table->index('order_number', 'appointment_orders_order_number_index');
            $table->index('order_date', 'appointment_orders_order_date_index');
            $table->index('status', 'appointment_orders_status_index');
            $table->index('created_at', 'appointment_orders_created_at_index');
            $table->index(['order_number', 'order_date'], 'appointment_orders_num_date_index');
        });

        // 3. Indexes for case_steps table
        Schema::table('case_steps', function (Blueprint $table) {
            $table->index(['legal_case_id', 'step_num'], 'case_steps_case_step_num_index');
        });

        // 4. Indexes for users table
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'username')) {
                $table->index('username', 'users_username_index');
            }
            if (Schema::hasColumn('users', 'role')) {
                $table->index('role', 'users_role_index');
            }
            if (Schema::hasColumn('users', 'is_active')) {
                $table->index('is_active', 'users_is_active_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('legal_cases', function (Blueprint $table) {
            $table->dropIndex('legal_cases_status_index');
            $table->dropIndex('legal_cases_incident_date_index');
            $table->dropIndex('legal_cases_law_type_status_index');
            $table->dropIndex('legal_cases_created_at_index');
        });

        Schema::table('appointment_orders', function (Blueprint $table) {
            $table->dropIndex('appointment_orders_order_number_index');
            $table->dropIndex('appointment_orders_order_date_index');
            $table->dropIndex('appointment_orders_status_index');
            $table->dropIndex('appointment_orders_created_at_index');
            $table->dropIndex('appointment_orders_num_date_index');
        });

        Schema::table('case_steps', function (Blueprint $table) {
            $table->dropIndex('case_steps_case_step_num_index');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_username_index');
            $table->dropIndex('users_role_index');
            $table->dropIndex('users_is_active_index');
        });
    }
};
