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
        Schema::create('workplace_check_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('control_id')->nullable();
            $table->unsignedBigInteger('checking_item_id')->nullable();
            $table->mediumText('checking_item_name')->nullable();
            $table->string('result')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('control_id')->references('id')->on('workplace_controls');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workplace_check_items');
    }
};
