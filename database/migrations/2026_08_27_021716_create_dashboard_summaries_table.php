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
        Schema::create('dashboard_summaries', function (Blueprint $table) {
            $table->id();
            // เก็บวันที่ของการสรุปข้อมูล
            $table->date('summary_date')->unique();
            
            // ข้อมูลสรุปของ LegalCase
            $table->integer('all_count')->default(0);
            $table->integer('pending_count')->default(0);
            $table->integer('completed_count')->default(0);
            
            // ข้อมูลสรุปของ AppointmentOrder
            $table->integer('orders_count')->default(0);
            
            // สำนวนด่วน/เกินกำหนด
            $table->integer('urgent_count')->default(0);
            $table->integer('overdue_count')->default(0);
            
            // ข้อมูลแยกตามประเภท (JSON)
            $table->json('type_counts')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboard_summaries');
    }
};
