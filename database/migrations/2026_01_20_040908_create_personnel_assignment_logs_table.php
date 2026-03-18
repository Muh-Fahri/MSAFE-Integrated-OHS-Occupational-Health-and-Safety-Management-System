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
        Schema::create('personnel_assignment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('assignment_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name', 100)->nullable();
            $table->string('status', 50)->nullable();
            $table->string('remarks', 200)->nullable();
            $table->unsignedBigInteger('delegator_uid')->nullable();
            $table->string('event', 50)->nullable();
            $table->timestamps();

            $table->foreign('assignment_id')->references('id')->on('personnel_assignments');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personnel_assignment_logs');
    }
};
