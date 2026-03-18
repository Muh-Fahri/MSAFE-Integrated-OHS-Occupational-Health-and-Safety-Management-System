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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->integer('parent_id')->default(0);
            $table->foreignId('hse_manager_id')->nullable()->constrained('id')->onDelete('cascade');
            $table->foreignId('pjo_id')->nullable()->constrained('id')->onDelete('cascade');
            $table->string('permit_no')->nullable();
            $table->string('industry')->nullable();
            $table->date('permit_start_date')->nullable();
            $table->date('permit_end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
