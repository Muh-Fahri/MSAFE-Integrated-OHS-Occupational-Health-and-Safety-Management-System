<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Symfony\Component\String\s;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('badge_request_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('status')->nullable();
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('delegator_uid')->nullable();
            $table->string('event')->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('badge_requests');
            $table->foreign('user_id')->references('id')->on('users');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge_request_logs');
    }
};
