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
        Schema::create('personnel_assignment_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->string('employee_id', 10)->nullable();
            $table->string('employee_name', 100)->nullable();
            $table->string('employee_title', 100)->nullable();
            $table->string('employee_department', 100)->nullable();
            $table->string('assignment_type', 100)->nullable();
            $table->string('assignment_field', 100)->nullable();
            $table->string('file_1_type')->nullable();
            $table->string('file_1_path')->nullable();
            $table->string('file_2_type')->nullable();
            $table->string('file_2_path')->nullable();
            $table->string('file_3_type')->nullable();
            $table->string('file_3_path')->nullable();
            $table->string('file_4_type')->nullable();
            $table->string('file_4_path')->nullable();
            $table->string('file_5_type')->nullable();
            $table->string('file_5_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_assignment_details');
    }
};
