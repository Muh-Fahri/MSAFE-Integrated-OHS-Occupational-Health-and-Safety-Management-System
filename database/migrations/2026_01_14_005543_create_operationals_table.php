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
        Schema::create('operationals', function (Blueprint $table) {
            $table->id();
            $table->date('submission_date')->nullable();
            $table->string('no')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('employee_name')->nullable();
            $table->string('title')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->string('appointment');
            $table->string('field')->nullable();
            $table->string('type')->nullable();
            $table->string('attachments')->nullable();
            $table->string('status')->nullable();
            $table->string('next_action')->nullable();
            $table->unsignedBigInteger('next_user_id')->nullable();
            $table->string('next_user_name')->nullable();
            $table->string('last_action')->nullable();
            $table->unsignedBigInteger('requestor_id')->nullable();
            $table->string('requestor_name')->nullable();
            $table->unsignedBigInteger('last_user_id')->nullable();
            $table->string('last_user_name')->nullable();
            $table->integer('approval_level')->nullable();
            $table->integer('last_approval_level')->nullable();
            $table->string('file_path')->nullable();
            $table->string('file_type')->nullable();
            $table->timestamps();

            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('next_user_id')->references('id')->on('users');
            $table->foreign('last_user_id')->references('id')->on('users');
            $table->foreign('requestor_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operationals');
    }
};
