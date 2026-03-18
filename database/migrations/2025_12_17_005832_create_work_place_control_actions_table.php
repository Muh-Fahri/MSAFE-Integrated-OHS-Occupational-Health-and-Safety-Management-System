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
        Schema::create('work_place_control_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('control_id')->nullable();
            $table->string('name')->nullable();
            $table->string('category')->nullable();
            $table->string('status')->nullable();
            $table->unsignedBigInteger('assignee_id');
            $table->string('assignee_name')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->foreign('assignee_id')->references('id')->on('users');
            $table->foreign('control_id')->references('id')->on('workplace_controls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_place_control_actions');
    }
};
