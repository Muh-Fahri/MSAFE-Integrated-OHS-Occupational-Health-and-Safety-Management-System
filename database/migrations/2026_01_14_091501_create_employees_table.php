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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id', 50)->unique();
            $table->string('tag_id', 10)->nullable();
            $table->string('full_name', 100);
            $table->string('barcode', 50)->nullable();
            $table->string('division', 100);
            $table->string('company', 100);
            $table->string('organization', 100);
            $table->string('job_position', 100);
            $table->string('job_level', 100);
            $table->date('join_date');
            $table->date('resign_date')->nullable();
            $table->string('employee_status', 100);
            $table->date('end_date')->nullable();
            $table->string('email', 100);
            $table->date('birth_date');
            $table->string('birth_place', 100);
            $table->mediumText('citizen_id_address');
            $table->mediumText('residential_address');
            $table->string('npwp', 100)->nullable();
            $table->string('ptkp_status', 100)->nullable();
            $table->string('employee_tax_status', 100)->nullable();
            $table->string('tax_config', 100)->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('bank_account', 100)->nullable();
            $table->string('bank_account_holder', 100)->nullable();
            $table->string('bpjs_ketenagakerjaan', 100)->nullable();
            $table->string('bpjs_kesehatan', 100)->nullable();
            $table->string('citizen_id', 100)->unique();
            $table->string('mobile_phone', 100)->nullable();
            $table->string('phone', 100)->nullable();
            $table->string('branch_name', 100)->nullable();
            $table->string('religion', 100)->nullable();
            $table->string('gender', 100)->nullable();
            $table->string('marital_status', 100)->nullable();
            $table->string('nationality_code', 100)->nullable();
            $table->string('currency', 100)->nullable();
            $table->string('length_of_service', 100)->nullable();
            $table->string('payment_schedule', 100)->nullable();
            $table->string('approval_line_id', 10)->nullable();
            $table->string('approval_line', 100)->nullable();
            $table->string('grade', 100)->nullable();
            $table->string('class', 100)->nullable();
            $table->string('point_of_hire', 100)->nullable();
            $table->string('point_of_hire_status', 100)->nullable();
            $table->string('point_of_travel', 100)->nullable();
            $table->string('roster', 100)->nullable();
            $table->string('company_email', 100)->nullable();
            $table->string('emergency_contact_name', 100)->nullable();
            $table->string('emergency_contact_relationship', 100)->nullable();
            $table->string('emergency_contact_number', 100)->nullable();
            $table->string('blood_type', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
