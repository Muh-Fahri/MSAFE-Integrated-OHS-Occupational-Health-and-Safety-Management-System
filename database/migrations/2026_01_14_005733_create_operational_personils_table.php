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
        Schema::create('operational_personils', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operational_id')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->string('appointment')->nullable();
            // new
            $table->string('status')->nullable();
            $table->string('next_action')->nullable();
            $table->unsignedBigInteger('next_user_id')->nullable();
            $table->string('next_user_name')->nullable();
            $table->string('last_action')->nullable();
            $table->unsignedBigInteger('last_user_id')->nullable();
            $table->string('last_user_name')->nullable();
            $table->integer('approval_level')->nullable();
            $table->integer('last_approval_level')->nullable();
            // ====
            $table->string('field')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();

            $table->foreign('next_user_id')->references('id')->on('users');
            $table->foreign('operational_id')->references('id')->on('operationals');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('last_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operational_personils');
    }
};
