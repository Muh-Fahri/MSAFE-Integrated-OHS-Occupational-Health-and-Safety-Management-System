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
        Schema::create('hazard_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('hazard_id');
            $table->integer('user_id');
            $table->string('user_name', 100);
            $table->string('status', 30);
            $table->mediumText('remarks')->nullable();
            $table->integer('delegator_uid')->nullable();
            $table->string('event', 50);
            $table->timestamps();
            $table->string('created_by', 100)->nullable();

            $table->unique(['hazard_id', 'user_id', 'status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hazard_logs');
    }
};
