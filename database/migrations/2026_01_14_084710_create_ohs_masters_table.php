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
        Schema::create('ohs_masters', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->string('code', 30);
            $table->string('name', 100);
            $table->string('value1', 100)->nullable();
            $table->string('value2', 100)->nullable();
            $table->string('value3', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ohs_masters');
    }
};
