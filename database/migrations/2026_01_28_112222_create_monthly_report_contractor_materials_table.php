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
        Schema::create('monthly_report_contractor_materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('monthly_report_contractors')->onDelete('cascade');
            $table->double('materials_qty')->nullable();
            $table->double('remaining_qty')->nullable();
            $table->double('received_qty')->nullable();
            $table->double('used_qty')->nullable();
            $table->string('uom')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_report_contractor_materials');
    }
};
