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
        Schema::create('license_attachments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('license_id');
            $table->string('name')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_path')->nullable();
            $table->timestamps();


            $table->foreign('license_id')->references('id')->on('licenses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses_attachments');
    }
};
