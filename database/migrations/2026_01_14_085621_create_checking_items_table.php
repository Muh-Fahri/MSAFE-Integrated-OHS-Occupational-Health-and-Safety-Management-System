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
        Schema::create('checking_items', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50);
            $table->string('subgroup', 50)->nullable();
            $table->string('name', 250);

            // Grouping Audit Trail
            $table->timestamp('created_at')->nullable();
            $table->string('created_by')->nullable();

            $table->timestamp('updated_at')->nullable();
            $table->string('updated_by')->nullable();

            $table->unique(['group', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checking_items');
    }
};
