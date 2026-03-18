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
        Schema::create('badge_requests', function (Blueprint $table) {
            $table->id();
            $table->date('request_date')->nullable();
            $table->string('request_no')->nullable();
            $table->unsignedBigInteger('requestor_id')->nullable();
            $table->string('requestor_name')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('company_name')->nullable();
            $table->unsignedBigInteger('sub_company_id')->nullable();
            $table->string('sub_company_name')->nullable();
            $table->string('location')->nullable();
            $table->string('taken_by')->nullable();
            $table->text('remarks')->nullable();
            $table->string('last_action')->nullable();
            $table->unsignedBigInteger('last_user_id')->nullable();
            $table->string('last_user_name')->nullable();
            $table->string('next_action')->nullable();
            $table->unsignedBigInteger('next_user_id')->nullable();
            $table->string('next_user_name')->nullable();
            $table->string('status')->nullable();
            $table->integer('approval_level');
            $table->timestamps();

            $table->foreign('requestor_id')->references('id')->on('users');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('last_user_id')->references('id')->on('users');
            $table->foreign('sub_company_id')->references('id')->on('companies');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge_requests');
    }
};
