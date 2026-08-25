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
    Schema::create('legal_cases', function (Blueprint $table) {
        $table->id();
        $table->integer('law_type')->index(); // ประเภท 1, 2, 3
        $table->integer('running_no');       // คอลัมน์ที่ขาดไป
        $table->string('case_number')->unique();
        $table->string('subject');
        $table->text('description')->nullable();
        $table->date('incident_date')->nullable();
        $table->string('status')->default('pending');
        $table->foreignId('user_id')->constrained('users');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('legal_cases');
    }
};
