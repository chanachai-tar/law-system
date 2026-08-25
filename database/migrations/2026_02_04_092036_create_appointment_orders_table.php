<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('appointment_orders', function (Blueprint $table) {
            $table->id(); // ลำดับ Auto
            $table->string('order_number'); // เลขที่คำสั่ง
            $table->date('order_date'); // ลงวันที่
            $table->string('subject'); // เรื่อง
            $table->string('to')->nullable(); // ถึง (ผู้รับคำสั่ง)
            $table->text('description')->nullable(); // รายละเอียดเพิ่มเติม
            $table->string('owner'); // เจ้าของเรื่อง
            $table->string('status')->default('active'); // สถานะ
            $table->string('file_path')->nullable(); // พาธไฟล์แนบ
            $table->timestamps(); // บันทึกเวลาสร้าง/แก้ไข
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_orders');
    }
};
