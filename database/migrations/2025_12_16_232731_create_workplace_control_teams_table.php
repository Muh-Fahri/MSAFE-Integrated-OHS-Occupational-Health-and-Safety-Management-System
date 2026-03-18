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
        Schema::create('workplace_control_teams', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('control_id')->nullable();
            $table->string('name')->nullable();
            $table->string('role')->nullable();
            $table->string('department')->nullable();
            $table->timestamps();

            $table->foreign('control_id')->references('id')->on('workplace_controls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workplace_control_teams');
    }
};
