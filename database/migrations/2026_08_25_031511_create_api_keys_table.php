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
        Schema::create('api_keys', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // ชื่อระบบภายนอก เช่น "ระบบสารบรรณ สธ.", "ระบบ E-Office"
            $table->string('key', 64)->unique(); // API Key เช่น lss_live_xxxxxxxx...
            $table->json('permissions')->nullable(); // สิทธิ์ เช่น ["cases:read", "cases:write", "orders:read", "orders:write"]
            $table->string('webhook_url')->nullable(); // URL สำหรับยิง Webhook ส่งข้อมูลออก
            $table->json('webhook_events')->nullable(); // Event ที่ให้ส่ง Webhook เช่น ["case.created", "case.closed", "order.created"]
            $table->string('ip_whitelist')->nullable(); // กำหนด IP ที่อนุญาต (ถ้ามี) คั่นด้วยคอมม่า
            $table->boolean('is_active')->default(true); // เปิด/ปิด การใช้งาน
            $table->timestamp('last_used_at')->nullable(); // วันเวลาที่ยิง API ล่าสุด
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('api_keys');
    }
};
