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
        Schema::create('monthly_report_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('report_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('delegator_uid')->nullable();
            $table->string('event')->nullable();
            $table->timestamps();

            $table->foreign('report_id')->references('id')->on('monthly_reports');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('delegator_uid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_report_logs');
    }
};
