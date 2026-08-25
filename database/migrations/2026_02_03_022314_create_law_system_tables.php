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
    Schema::create('legal_cases', function ($table) {
        $table->id();
        $table->string('case_number');
        $table->string('law_type');
        $table->string('subject');
        $table->timestamps();
    });

    Schema::create('case_steps', function ($table) {
        $table->id();
        $table->foreignId('legal_case_id')->constrained()->onDelete('cascade');
        $table->integer('step_num');
        $table->text('description')->nullable();
        $table->timestamps();
    });

    Schema::create('case_files', function ($table) {
        $table->id();
        $table->foreignId('case_step_id')->constrained()->onDelete('cascade');
        $table->string('file_path');
        $table->string('file_name');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('law_system_tables');
    }
};
