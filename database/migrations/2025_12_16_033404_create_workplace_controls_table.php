<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workplace_controls', function (Blueprint $table) {
            $table->id();

            $table->date('date')->nullable(); // di dump boleh NULL [file:12]
            $table->enum('type', [
                'INSPECTION_VEHICLE',
                'INSPECTION_BUILDING',
                'INSPECTION_ROAD',
                'INSPECTION_DRILLING_AREA',
                'INSPECTION_CONSTRUCTION_AREA',
                'INSPECTION_DUMP_POINT_AREA',
                'INSPECTION_LOADING_POINT_AREA',
                'PLANNED_TASK_OBSERVATION',
            ])->nullable(); // di dump: varchar(30) DEFAULT NULL [file:12]
            $table->string('no', 30)->nullable();
            $table->integer('operator_id')->nullable();
            $table->string('operator_name', 100)->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('department_name', 100)->nullable();
            $table->string('location', 100)->nullable();
            $table->string('vehicle_code', 30)->nullable();
            $table->string('vehicle_type', 100)->nullable();   // boleh kosong kalau bukan vehicle
            $table->string('building_type', 100)->nullable();  // boleh kosong kalau bukan building
            $table->string('site', 100)->nullable();
            $table->string('activity', 200)->nullable();
            $table->string('activity_company', 100)->nullable();
            $table->mediumText('activity_person')->nullable();
            $table->integer('employee_count')->nullable();
            $table->string('area_supervisor', 100)->nullable(); // perbaiki typo
            $table->string('procedure', 200)->nullable();
            $table->string('observation_reason', 250)->nullable();
            $table->unsignedBigInteger('requestor_id')->nullable();
            $table->string('requestor_name', 100)->nullable();
            $table->integer('requestor_department_id')->nullable();
            $table->string('requestor_department_name', 100)->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('requestor_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workplace_controls');
    }
};
