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
        Schema::create('license_zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('license_id')->nullable();
            $table->string('code')->nullable();
            $table->string('color_code')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('license_id')->references('id')->on('licenses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('license_zones');
    }
};
