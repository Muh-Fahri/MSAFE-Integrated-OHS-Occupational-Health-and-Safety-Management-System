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
        Schema::create('monthly_reports', function (Blueprint $table) {
            $table->id();
            $table->date('report_date')->nullable();
            $table->string('report_no');
            $table->unsignedBigInteger('requestor_id')->nullable();
            $table->string('requestor_name')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('cover_text')->nullable();
            $table->text('incident_total')->nullable();
            $table->text('step_unachieved_dept')->nullable();
            $table->text('next_month_strategy')->nullable();
            $table->text('remarks')->nullable();
            $table->string('last_action')->nullable();
            $table->unsignedBigInteger('last_user_id')->nullable();
            $table->string('last_user_name')->nullable();
            $table->string('next_action')->nullable();
            $table->unsignedBigInteger('next_user_id')->nullable();
            $table->string('next_user_name')->nullable();
            $table->string('status')->nullable();
            $table->integer('approval_level');
            $table->string('created_by');
            $table->string('updated_by');
            $table->timestamps();

            $table->foreign('requestor_id')->references('id')->on('users');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('last_user_id')->references('id')->on('users');
            $table->foreign('next_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_reports');
    }
};
