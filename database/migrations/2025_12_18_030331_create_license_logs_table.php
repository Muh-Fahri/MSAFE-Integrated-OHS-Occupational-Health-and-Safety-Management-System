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
        Schema::create('license_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('license_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('status')->nullable();
            $table->text('remarks')->nullable();
            $table->uuid('delegator_uid')->nullable();
            $table->string('event')->nullable();
            $table->timestamps();

            $table->foreign('license_id')->references('id')->on('licenses');
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_logs');
    }
};
