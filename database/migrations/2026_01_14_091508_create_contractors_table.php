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
        Schema::create('contractors', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 20)->unique();
            $table->string('citizen_id', 16)->unique();
            $table->string('tag_id', 30)->nullable();
            $table->string('full_name', 100);
            $table->string('job_position', 100);
            $table->string('department', 100);
            $table->string('company', 100);
            $table->string('gender', 10)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contractors');
    }
};
