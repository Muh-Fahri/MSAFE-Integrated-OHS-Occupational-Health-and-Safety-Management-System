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
        Schema::create('incident_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('incident_id')->nullable();
            $table->string('name')->nullable();
            $table->unsignedBigInteger('assignee_id')->nullable();
            $table->string('assignee_name')->nullable();
            $table->date('due_date')->nullable();
            $table->timestamps();

            $table->foreign('incident_id')->references('id')->on('incidents');
            $table->foreign('assignee_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incident_actions');
    }
};
