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
        Schema::create('incident_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->unsignedBigInteger('delegator_uid')->nullable();
            $table->string('event')->nullable();
            $table->timestamps();

            $table->foreign('incident_id')->references('id')->on('incidents');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('delegator_uid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_logs');
    }
};
