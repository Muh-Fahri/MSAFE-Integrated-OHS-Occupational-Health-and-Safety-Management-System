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
        Schema::create('general_tables', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50);
            $table->string('code', 50);
            $table->string('name', 50);
            $table->string('value1', 100)->nullable();
            $table->string('value2', 100)->nullable();
            $table->string('value3', 100)->nullable();
            $table->timestamps();

            $table->unique(['type', 'code']);
            $table->unique(['type', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_tables');
    }
};
