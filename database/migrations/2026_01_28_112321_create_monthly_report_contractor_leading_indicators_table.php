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
        Schema::create('monthly_report_contractor_leading_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained('monthly_report_contractors')->onDelete('cascade');
            $table->string('activity')->nullable();
            $table->string('jumlah_pelaksana')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_report_contractor_leading_indicators');
    }
};
