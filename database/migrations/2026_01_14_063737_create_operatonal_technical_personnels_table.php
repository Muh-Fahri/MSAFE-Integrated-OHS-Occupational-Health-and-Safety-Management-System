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
        Schema::create('operatonal_technical_personnels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('operational_id')->nullable();
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->timestamps();

            $table->foreign('operational_id')->references('id')->on('operationals');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operatonal_technical_personnels');
    }
};
