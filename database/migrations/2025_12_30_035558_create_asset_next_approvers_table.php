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
        Schema::create('asset_next_approvers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('asset_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_name')->nullable();
            $table->string('status')->nullable();
            $table->mediumText('remarks')->nullable();
            $table->unsignedBigInteger('delegator_uid')->nullable();
            $table->string('event')->nullable();
            $table->timestamps();

            $table->foreign('asset_id')->references('id')->on('assets');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('delegator_uid')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_next_approvers');
    }
};
