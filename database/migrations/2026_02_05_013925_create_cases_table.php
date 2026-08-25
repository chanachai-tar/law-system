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
        Schema::create('cases', function (Blueprint $table) {
            $table->id();

            // ประเภทกฎหมาย (1=ตส., 2=สล., 3=สว.)
            // ใส่ index เพราะต้องใช้ค้นหา (where) บ่อยตอนรันเลข
            $table->integer('law_type')->index();

            // เลขรันลำดับ (เช่น 1, 2, 3)
            // ใช้สำหรับคำนวณหาเลขถัดไป
            $table->integer('running_no');

            // เลขเคสแบบเต็ม (เช่น ตส. 001/2567)
            // เก็บเป็น string เพื่อความสะดวกในการแสดงผล
            $table->string('case_number')->unique();

            // ตัวอย่างฟิลด์อื่นๆ ที่ควรมี (ปรับเปลี่ยนได้ตามจริง)
            $table->string('subject')->nullable()->comment('เรื่อง/หัวข้อเคส');
            $table->string('to')->nullable()->comment('ถึง');
            $table->text('description')->nullable()->comment('รายละเอียด');
            $table->date('incident_date')->nullable()->comment('วันที่เกิดเหตุ');

            $table->timestamps();

            // สร้าง Composite Index เพื่อให้การ Query หาเลขล่าสุดของแต่ละประเภทในปีนั้นๆ เร็วขึ้น
            $table->index(['law_type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cases');
    }
};
