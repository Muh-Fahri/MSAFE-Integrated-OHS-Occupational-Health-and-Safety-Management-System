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
        Schema::create('badge_request_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('request_id')->nullable();
            $table->integer('seq')->nullable();
            $table->string('employee_id')->nullable();
            $table->string('citizen_id')->nullable();
            $table->string('name')->nullable();
            $table->string('title')->nullable();
            $table->double('active_period')->nullable();
            $table->string('status')->nullable();
            $table->date('contract_start_date')->nullable();
            $table->date('contract_end_date')->nullable();
            $table->double('contract_period')->nullable();
            $table->string('time_unit')->nullable();
            $table->string('file_type_photo')->nullable();
            $table->string('file_path_photo')->nullable();
            $table->string('file_type_ftw')->nullable();
            $table->string('file_path_ftw')->nullable();
            $table->string('file_type_mcu')->nullable();
            $table->string('file_path_mcu')->nullable();
            $table->string('file_type_covid')->nullable();
            $table->string('file_path_covid')->nullable();
            $table->string('file_type_ktp')->nullable();
            $table->string('file_path_ktp')->nullable();
            $table->string('file_type_domicile')->nullable();
            $table->string('file_path_domicile')->nullable();
            $table->string('file_type_skck')->nullable();
            $table->string('file_path_skck')->nullable();
            $table->string('file_type_induksi')->nullable();
            $table->string('file_path_induksi')->nullable();
            $table->timestamps();

            $table->foreign('request_id')->references('id')->on('badge_requests');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('badge_request_lines');
    }
};
