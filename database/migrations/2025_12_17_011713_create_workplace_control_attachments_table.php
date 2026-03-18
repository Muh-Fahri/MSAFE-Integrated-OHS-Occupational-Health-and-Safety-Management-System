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
        Schema::create('workplace_control_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('control_id')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();

            $table->foreign('control_id')->references('id')->on('workplace_controls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workplace_control_attachments');
    }
};
