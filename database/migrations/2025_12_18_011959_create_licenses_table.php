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
        Schema::create('licenses', function (Blueprint $table) {
            $table->id();
            $table->string('no')->nullable();
            $table->date('date')->nullable();
            $table->string('employee_id');
            $table->string('name')->nullable();
            $table->string('position')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->string('department_name')->nullable();
            $table->unsignedBigInteger('company_id')->nullable();
            $table->string('company_name')->nullable();
            $table->unsignedBigInteger('requestor_id')->nullable();
            $table->string('requestor_name')->nullable();
            $table->unsignedBigInteger('requestor_department_id')->nullable();
            $table->string('requestor_department_name')->nullable();
            $table->enum('type', ['KIMPER', 'KIMPAK'])->nullable();
            $table->mediumText('reason')->nullable();
            $table->unsignedBigInteger('theory_tester_id')->nullable();
            $table->string('theory_tester_name')->nullable();
            $table->unsignedBigInteger('practice_tester_id')->nullable();
            $table->string('practice_tester_name')->nullable();
            $table->unsignedBigInteger('first_aid_trainer_id')->nullable();
            $table->string('first_aid_trainer_name')->nullable();
            $table->unsignedBigInteger('ddc_trainer_id')->nullable();
            $table->string('ddc_trainer_name')->nullable();
            $table->text('remarks')->nullable();
            $table->date('driving_license_expiry_date')->nullable();
            $table->string('last_action')->nullable();
            $table->unsignedBigInteger('last_user_id')->nullable();
            $table->string('last_user_name')->nullable();
            $table->string('last_approval_level')->nullable();
            $table->string('next_action')->nullable();
            $table->unsignedBigInteger('next_user_id')->nullable();
            $table->string('next_user_name')->nullable();
            $table->string('status')->nullable();
            $table->integer('approval_level')->nullable();
            $table->string('license_status')->nullable();
            $table->date('expire_date')->nullable();
            $table->timestamps();

            $table->foreign('department_id')->references('id')->on('departments');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->foreign('requestor_id')->references('id')->on('users');
            $table->foreign('theory_tester_id')->references('id')->on('users');
            $table->foreign('practice_tester_id')->references('id')->on('users');
            $table->foreign('ddc_trainer_id')->references('id')->on('users');
            $table->foreign('last_user_id')->references('id')->on('users');
            $table->foreign('next_user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licenses');
    }
};
